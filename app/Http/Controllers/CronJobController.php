<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

date_default_timezone_set('Asia/Kolkata');

class CronJobController extends Controller
{
    public function __construct()
    {
    }

    public function sendSampleMail()
    {
 
        // Ship order...
 
        Mail::to('prashobkthamban@gmail.com')->send(new SendMailable());
    }
}
