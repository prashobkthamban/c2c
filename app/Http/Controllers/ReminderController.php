<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Reminder;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
    }
    public function index(){
        return view('home.reminder', ['result' => Reminder::getReport()]);
    }
}
