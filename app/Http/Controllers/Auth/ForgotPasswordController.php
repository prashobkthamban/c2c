<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgotPasswordCustom() {
        $messages = [];
        return view('auth.passwords.custom_reset', compact('messages'));
    }

    public function forgotPasswordSendMail(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'username' => 'required',
            'email' => 'required'
        ]);
        $message = ['messages' => []];
        if($validator->fails()) {
            $message = $validator->messages()->messages(); 
            return view('auth.passwords.custom_reset', compact('message'));
        }
        $name = $request->get('name');
        $mobile = $request->get('mobile');
        $userName = $request->get('username');
        $email = $request->get('email');
        $userData = DB::table('account')
                    ->where('username', $userName)
                    ->where('email', $email)
                    ->where('phone_number', $mobile)
                    ->first();
        if (empty($userData)) {
            $messages = ['status' => 'danger', 'message' => 'Invalid details!'];
            return view('auth.passwords.custom_reset', compact('messages'));
        }

        $data = [
            'fromAddress' => 'admin@ivrmanager.in',
            'fromName' => 'Admin',
            'to' => 'support@voiceetc.co.in',
            'subject' => 'Password Reset Request',
            'view' => 'emails.forgot_password',
            'params' => [
                'name' => $name,
                'mobile' => $mobile,
                'userName' => $userName,
                'email' => $email
            ]
        ];

        Mail::queue(new SendMailable($data));

        $messages = ['status' => 'success', 'message' => 'Your details submited successfuly. Our team will get back to you shortly.'];
        return view('auth.passwords.custom_reset', compact('messages'));
    }
}
