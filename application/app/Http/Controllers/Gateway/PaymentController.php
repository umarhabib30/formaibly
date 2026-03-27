<?php

namespace App\Http\Controllers\Gateway;

use App\Models\Plan;
use App\Models\User;
use App\Models\Deposit;
use App\Constants\Status;
use App\Lib\FormProcessor;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Models\UserNotification;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{

    public function creditPurchase()
    {
        $pageTitle = 'Buy-Credit';
        $user = User::where('id', auth()->id())->first();
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        return view('UserTemplate::payment.credit_purchase', compact('gatewayCurrency', 'pageTitle', 'user'));
    }

    public function creditInsert(Request $request)
    {
        $request->validate([
            'amount'          => 'required|numeric|gt:0',
            'method_code'     => 'nullable|required_unless:gateway,balance',
            'currency'        => 'nullable|required_unless:gateway,balance',
            'gateway'         => 'required',
            'credit_purchase' => 'required'
        ]);

        $totalAmount = $request->credit_purchase * gs()->per_credit_price;

        $user = auth()->user();
        if ($request->gateway == 'balance') {
            return $this->handleBalanceCreditPurchase($user, $request->credit_purchase, $totalAmount);
        } else {
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }

            if ($gate->min_amount > $totalAmount || $gate->max_amount < $totalAmount) {
                $notify[] = ['error', 'Please follow deposit limit'];
                return back()->withNotify($notify);
            }


            $charge    = $gate->fixed_charge + ($totalAmount * $gate->percent_charge / 100);
            $payable   = $totalAmount + $charge;
            $final_amo = $payable * $gate->rate;

            $data                     = new Deposit();
            $data->user_id            = $user->id;
            $data->method_code        = $gate->method_code;
            $data->method_currency    = strtoupper($gate->currency);
            $data->amount             = $totalAmount;
            $data->charge             = $charge;
            $data->rate               = $gate->rate;
            $data->final_amo          = $final_amo;
            $data->is_credit_purchase = 1;
            $data->number_of_credit   = $request->credit_purchase;
            $data->btc_amo            = 0;
            $data->btc_wallet         = "";
            $data->trx                = getTrx();
            $data->try                = 0;
            $data->status             = 0;
            $data->save();
            session()->put('Track', $data->trx);
            return to_route('user.deposit.confirm');
        }
    }

    public function planPayment($id)
    {
        $pageTitle = 'Plan Subscription Payment';
        $user = auth()->user();
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();

        $plan = Plan::where('id', $id)->where('status', Status::PLAN_ENABLE)->first();

        if (!$plan) {
            $notify[] = ['error', 'Plan is not valid'];
            return back()->withNotify($notify);
        }

        // Check if the same plan is already purchased and still active
        if ($plan) {
            $existSamePlanBuyAndActive = Subscription::where('plan_id', $plan->id)
               ->where('user_id',$user->id)
                ->where('expired_at', '>=', now())
                ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
                ->first();

            if ($existSamePlanBuyAndActive) {
                $notify[] = ['error', 'You already have an active subscription for this plan'];
                return back()->withNotify($notify);
            }
        }

        // Check if the user already has an active plan and if the existing plan price is greater than the requested plan price
        if ($plan) {
            $existPlanBuyAndActive = Subscription::with('plan')
                ->where('user_id', $user->id)
                ->where('expired_at', '>=', now())
                ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
                ->first();

            if ($existPlanBuyAndActive && $existPlanBuyAndActive->plan && $existPlanBuyAndActive->plan->price > $plan->price) {
                $notify[] = ['error', 'You already have a higher plan. Please upgrade instead of downgrading.'];
                return back()->withNotify($notify);
            }
        }


        //check plan is free or not
        if ($plan->price <= 0) {
            $this->createSubscription($user, $plan);
            $notify[] = ['success', 'Plan Subscription Payment successfully'];
            return back()->withNotify($notify);
        }


        return view('UserTemplate::payment.plan_payment', compact('gatewayCurrency', 'pageTitle', 'user', 'plan'));
    }

    public function storePlanPayment(Request $request)
    {
        $request->validate([
            'plan_id'     => 'required|exists:plans,id',
            'amount'      => 'required|numeric|gt:0',
            'method_code' => 'nullable|required_unless:gateway,balance',
            'currency'    => 'nullable|required_unless:gateway,balance',
            'gateway'     => 'required'
        ]);


        $user = auth()->user();
        $plan = Plan::where('id', $request->plan_id)->where('status', Status::PLAN_ENABLE)->first();

        if (!$plan) {
            $notify[] = ['error', 'Plan is not valid'];
            return back()->withNotify($notify);
        }

        if ($request->gateway == 'balance') {
            // Check balance
            if ($user->balance < $plan->price) {
                $notify[] = ['error', 'Insufficient Balance'];
                return back()->withNotify($notify);
            }

            return $this->handleBalancePlanPayment($user, $plan);
        } else {
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }

            if ($gate->min_amount > $plan->price || $gate->max_amount < $plan->price) {
                $notify[] = ['error', 'Please follow deposit limit'];
                return back()->withNotify($notify);
            }

            $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
            $payable = $request->amount + $charge;
            $final_amo = $payable * $gate->rate;

            // Create Subscription 
            $subscription             = new Subscription();
            $subscription->plan_id    = $plan->id;
            $subscription->user_id    = $user->id;
            $subscription->amount     = $plan->price;
            $subscription->started_at = now();
            $subscription->expired_at = $plan->period == 'monthly' ? now()->addMonth() : now()->addYear();
            $subscription->status     = Status::PLAN_SUBSCRIPTION_INITIAL;
            $subscription->save();

            $data                  = new Deposit();
            $data->user_id         = $user->id;
            $data->subscription_id = $subscription->id;
            $data->method_code     = $gate->method_code;
            $data->method_currency = strtoupper($gate->currency);
            $data->amount          = $plan->price;
            $data->charge          = $charge;
            $data->rate            = $gate->rate;
            $data->final_amo       = $final_amo;
            $data->btc_amo         = 0;
            $data->btc_wallet      = "";
            $data->trx             = getTrx();
            $data->try             = 0;
            $data->status          = 0;
            $data->save();


            session()->put('Track', $data->trx);
            return to_route('user.deposit.confirm');
        }
    }

    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);


        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge    = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable   = $request->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data                  = new Deposit();
        $data->user_id         = $user->id;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amo       = $final_amo;
        $data->btc_amo         = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->try             = 0;
        $data->status          = 0;
        $data->save();
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }


    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (isset($data->session)) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }
        $type = $deposit->subscription_id ? 'Plan Subscription Payment' : ($deposit->is_credit_purchase ? 'Credit Payment' : 'Deposit Payment');

        $pageTitle = $type;
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {
            $pageTitle = $data->subscription_id ? 'Plan Subscription Payment' : ($data->is_credit_purchase ? 'Credit Payment' : 'Deposit Payment');
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }


    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway', 'subscription')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        if ($data->subscription) {
            $data->subscription->status = Status::PLAN_SUBSCRIPTION_PENDING;
            $data->subscription->save();
        }

        $type = $data->subscription_id ? 'Plan Subscription Payment' : ($data->is_credit_purchase ? 'Credit Payment' : 'Deposit Payment');

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = $type . 'request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        $notifyData = [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amo),
            'amount'          => showAmount($data->amount),
            'charge'          => showAmount($data->charge),
            'rate'            => showAmount($data->rate),
        ];

        if ($data->is_credit_purchase) {
            $notifyData['purchase_credit_count'] = $data->number_of_credit;
            notify($data->user, "CREDIT_PAYMENT_REQUEST", $notifyData);
        } elseif ($data->subscription) {
            $notifyData['trx'] = $data->trx;
            notify($data->user, "PLAN_SUBSCRIPTION_PAYMENT_REQUEST", $notifyData);
        } else {
            $notifyData['trx'] = $data->trx;
            notify($data->user, "DEPOSIT_REQUEST", $notifyData);
        }

        $notify[] = ['success', "Your {$type} request has been taken"];
        return to_route('user.deposit.history')->withNotify($notify);
    }


    public static function userDataUpdate($deposit, $isManual = null)
    {

        if ($deposit->status == Status::PAYMENT_PENDING || $deposit->status == Status::PAYMENT_INITIATE) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);

            if ($deposit->is_credit_purchase) {
                $user->credit += $deposit->number_of_credit;
                $user->save();
            } elseif ($deposit->subscription_id) {
                $deposit->subscription->status = Status::PLAN_SUBSCRIPTION_APPROVED;
                $deposit->subscription->save();
            } else {
                $user->balance += $deposit->amount;
                $user->save();
            }

            $type                      = $deposit->subscription_id ? 'Plan Subscription Payment' : ($deposit->is_credit_purchase ? 'Credit Payment' : 'Deposit Payment');
            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->post_credit  = $user->credit;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = $deposit->subscription_id ? '-' : ($deposit->is_credit_purchase ? '-' : '+');
            $transaction->details      = $type . ' Via from ' . $deposit->gatewayCurrency()->name;
            $transaction->trx          = $deposit->trx;
            $transaction->remark       = strtolower($type);
            $transaction->save();

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = $type . ' successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            $userNotification            = new UserNotification();
            $userNotification->user_id   = $user->id;
            $userNotification->title     = $type . 'successful via ' . $deposit->gatewayCurrency()->name;
            $userNotification->click_url = urlPath('user.deposit.history', ['search' => $transaction->trx]);
            $userNotification->save();

            // ✅ User notification
            $notifyData = [
                'method_name'     => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amo),
                'charge'          => showAmount($deposit->charge),
                'rate'            => showAmount($deposit->rate),
                'post_balance'    => showAmount($user->balance),
            ];

            if ($deposit->is_credit_purchase) {
                $notifyData['number_of_credit'] = $deposit->number_of_credit;
                notify($user, $isManual ? 'CREDIT_PAYMENT_APPROVE' : 'CREDIT_PAYMENT_COMPLETE', $notifyData);
            } elseif ($deposit->subscription_id) {
                
                //Plan has credit add
                if ($deposit->subscription?->plan->credit) {
                    $user->credit += $deposit->subscription?->plan?->credit;
                    $user->save();
                }

                // 🔹 Deactivate all previous active subscriptions
                $allPreviousSubscriptions = Subscription::where('user_id', $user->id)
                    ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
                    ->where('id', '!=', $deposit->subscription->id)
                    ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
                    ->get();

                foreach ($allPreviousSubscriptions as $key => $value) {
                    $value->status = Status::PLAN_SUBSCRIPTION_DISABLE;
                    $value->save();
                }

                $notifyData['trx'] = $deposit->trx;
                notify($user, $isManual ? 'PLAN_SUBSCRIPTION_PAYMENT_APPROVE' : 'PLAN_SUBSCRIPTION_PAYMENT_COMPLETE', $notifyData);
            } else {
                $notifyData['trx'] = $deposit->trx;
                notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', $notifyData);
            }
        }
    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }
        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    private function handleBalanceCreditPurchase(User $user, int $creditPurchase, float $totalAmount)
    {
        // Check balance
        if ($user->balance < $totalAmount) {
            $notify[] = ['error', 'Insufficient Balance'];
            return back()->withNotify($notify);
        }

        // Deduct balance and add credit
        $user->balance -= $totalAmount;
        $user->credit += $creditPurchase;
        $user->save();

        // Create transaction
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $totalAmount;
        $transaction->post_balance = $user->balance;
        $transaction->post_credit  = $user->credit;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Payment With Balance to Purchase Credit';
        $transaction->trx          = $trx;
        $transaction->remark       = 'Credit Purchase Payment';
        $transaction->save();

        // Create user notification
        $userNotification = new UserNotification();
        $userNotification->user_id   = $user->id;
        $userNotification->title     = 'Balance with Purchase Credit';
        $userNotification->click_url = urlPath('user.transactions', ['search' => $transaction->trx]);
        $userNotification->save();

        $notify[] = ['success', 'Credit Payment successfully'];
        return to_route('user.transactions')->withNotify($notify);
    }


    private function handleBalancePlanPayment(User $user, object $planData)
    {

        $user = $user;
        $planData = $planData;

        $this->createSubscription($user, $planData);

        $user->balance -= $planData->price;
        $user->save();

        $notify[] = ['success', 'Plan Subscription Payment successfully'];
        return to_route('user.transactions')->withNotify($notify);
    }


    private function createSubscription(User $user, object $planData)
    {

        // 🔹 Deactivate all previous active subscriptions
        $allPreviousSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', Status::PLAN_SUBSCRIPTION_APPROVED)
            ->get();
        foreach ($allPreviousSubscriptions as $key => $value) {
            $value->status = Status::PLAN_SUBSCRIPTION_DISABLE;
            $value->save();
        }

        // Create Subscription 
        $subscription             = new Subscription();
        $subscription->plan_id    = $planData->id;
        $subscription->user_id    = $user->id;
        $subscription->amount     = $planData->price;
        $subscription->started_at = now();
        $subscription->expired_at = $planData->period == 'monthly' ? now()->addMonth() : now()->addYear();
        $subscription->status     = Status::PLAN_ENABLE;
        $subscription->save();

        if ($planData->credit) {
            $user->credit += $planData->credit;
            $user->save();
        }

        // Create transaction
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $planData->price;
        $transaction->post_balance = $user->balance;
        $transaction->post_credit  = $user->credit;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Payment With Balance to Plan Subscription Payment';
        $transaction->trx          = $trx;
        $transaction->remark       = 'Plan Subscription Payment';
        $transaction->save();

        // Create notification
        $userNotification = new UserNotification();
        $userNotification->user_id   = $user->id;
        $userNotification->title     = 'Balance with Plan Subscription Payment';
        $userNotification->click_url = urlPath('user.transactions', ['search' => $transaction->trx]);
        $userNotification->save();
        return 0;
    }
}
