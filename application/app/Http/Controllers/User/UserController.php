<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Models\Form;
use App\Lib\FormProcessor;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Lib\GoogleAuthenticator;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder;
use App\Models\FormBuilderAnswer;
use App\Models\Subscription;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user                  = auth()->user();
        $totalForms            = FormBuilder::where('user_id', $user->id)->count();
        $totalSubmissions      = FormBuilderAnswer::where('user_id', $user->id)->count();
        $transactions          = Transaction::where('user_id', $user->id)->latest()->paginate(getPaginate(10));
        $subscription          = Subscription::with('plan')->where('user_id', $user->id)->where('expired_at','>',now())->where('status',Status::PLAN_SUBSCRIPTION_APPROVED)->orderBy('id','desc')->first();
        return view('UserTemplate::dashboard', compact('pageTitle', 'transactions', 'totalForms', 'totalSubmissions','subscription'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Payments History';
        $deposits = auth()->user()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx', $request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('UserTemplate::deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view('UserTemplate::twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->where('user_id', auth()->id())->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx', 'amount'])->dateFilter()->filter(['trx_type', 'remark'])->latest()->paginate(getPaginate());
        return view('UserTemplate::transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'kyc')->first();
        return view('UserTemplate::kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('UserTemplate::kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'Profile Setting';
        return view('UserTemplate::user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country' => $user->address->country,
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'city' => $request->city,
        ];
        $user->reg_step = 1;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function notification($id)
    {
        $user = auth()->user();
        $notification = UserNotification::where('user_id', $user->id)->where('id', $id)->first();
        if (!$notification) {
            $notify[] = ['error', 'Notification not found'];
            return back()->withNotify($notify);
        }

        $notification->read_status = 0;
        $notification->save();
        return redirect($notification->click_url);
    }

    public function notificationAll(Request $request)
    {
        $search = $request->get('search');
        $user = auth()->user();
        $pageTitle = 'All Notifications';
        $query = UserNotification::searchable(['title'])->where('user_id', $user->id);
        $notifications = $query->paginate(getPaginate());
        return view('UserTemplate::notification.index', compact('pageTitle', 'user', 'notifications'));
    }
}
