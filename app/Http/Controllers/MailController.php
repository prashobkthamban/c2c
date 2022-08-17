<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

date_default_timezone_set('Asia/Kolkata');

class MailController extends Controller
{
    public function __construct()
    {
    }

    public function getCsrfToken() {
        return csrf_token();
    }

    public function sendMail(Request $request)
    {
        $data = [
            'to' => $request->get('to'),
            'subject' => $request->get('subject'),
            'view' => $request->get('view'),
            'params' => [
                'heading' => $request->get('heading'),
                'content' => $request->get('content')
            ],
            'attachment' => $request->get('attachment'),
            'fileType' => $request->get('fileType'),
        ];

        Mail::queue(new SendMailable($data));

        return 'OK';
    }
}
