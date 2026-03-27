<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function log(Request $request, $status = 'all')
    {
        $withdrawalData = $this->withdrawalData($status != 'all' ? $status : null, true);
        $items = $withdrawalData['data'];
        $summery = $withdrawalData['summery'];
        $successful = $summery['successful'];
        $pending = $summery['pending'];
        $rejected = $summery['rejected'];
        $logCount = $summery['total'];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.withdraw_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Withdrawals';
        return view('Admin::withdraw.withdrawals', compact('pageTitle', 'items', 'successful', 'pending', 'rejected', 'logCount'));
    }

    protected function withdrawalData($scope = null, $summery = false)
    {
        $baseQuery = $scope ? Withdrawal::$scope() : Withdrawal::query();
        $dataQuery =  Withdrawal::query();

        $summaryQuery = clone $dataQuery;

        $successfulSummery = (clone $summaryQuery)->where('status', 1)->sum('amount');
        $pendingSummery    = (clone $summaryQuery)->where('status', 2)->sum('amount');
        $rejectedSummery   = (clone $summaryQuery)->where('status', 3)->sum('amount');
        $totalCount        = $summaryQuery->count();

        $withdrawals = $baseQuery->with(['user', 'method'])->searchable(['trx', 'user:username'])->dateFilter()->orderBy('id', 'desc')->paginate(getPaginate());

        return [
            'data' => $withdrawals,
            'summery' => [
                'successful' => $successfulSummery,
                'pending'    => $pendingSummery,
                'rejected'   => $rejectedSummery,
                'total'      => $totalCount,
            ]
        ];
    }


    public function details($id)
    {
        $general = gs();
        $withdrawal = Withdrawal::where('id',$id)->where('status', '!=', 0)->with(['user','method'])->firstOrFail();
        $pageTitle = 'Withdraw Request of ' .showAmount($withdrawal->amount) . ' '.$general->cur_text;
        $details = $withdrawal->withdraw_information ? json_encode($withdrawal->withdraw_information) : null;

        return view('Admin::withdraw.detail', compact('pageTitle', 'withdrawal','details'));
    }


    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $withdraw = Withdrawal::where('id',$request->id)->where('status',2)->with('user')->firstOrFail();
        $withdraw->status = 1;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount),
            'amount' => showAmount($withdraw->amount),
            'charge' => showAmount($withdraw->charge),
            'rate' => showAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'admin_details' => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal approved successfully'];
        return to_route('admin.withdraw.log')->withNotify($notify);
    }


    public function reject(Request $request)
    {
        $general = gs();
        $request->validate(['id' => 'required|integer']);
        $withdraw = Withdrawal::where('id',$request->id)->where('status',2)->with('user')->firstOrFail();

        $withdraw->status = 3;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        $user = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '+';
        $transaction->remark = 'withdraw_reject';
        $transaction->details = showAmount($withdraw->amount) . ' ' . $general->cur_text . ' Refunded from withdrawal rejection';
        $transaction->trx = $withdraw->trx;
        $transaction->save();


        notify($user, 'WITHDRAW_REJECT', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount),
            'amount' => showAmount($withdraw->amount),
            'charge' => showAmount($withdraw->charge),
            'rate' => showAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance),
            'admin_details' => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal rejected successfully'];
        return to_route('admin.withdraw.log')->withNotify($notify);
    }

}
