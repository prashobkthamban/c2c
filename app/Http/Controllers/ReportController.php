<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\CdrReport;
use App\Models\CdrArchive;
use App\Models\CdrPbx;
use App\Models\OperatorAccount;
use App\Models\Contact;
use App\Models\VoiceEmail;
use App\Models\Blacklist;
use App\Models\Holiday;
use App\Models\Conference;
use App\Models\CdrTag;
use App\Models\CurChannelUsed;


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
    }
    public function index(){
        return view('home.cdrreport', ['result' => CdrReport::getReport()]);
    }
    public function cdrreportarchive(){
        return view('home.cdrreportarchive', ['result' => CdrArchive::getReport()]);
    }
    public function cdrreportout(){
        return view('home.cdrreportout', ['result' => CdrPbx::getReport()]);
    }
    public function operator(){
        return view('home.operator', ['result' => OperatorAccount::getReport()]);
    }
    public function contacts(){
        return view('home.contacts', ['result' => Contact::getReport()]);
    }
    public function voicemail(){
        return view('home.voicemail', ['result' => VoiceEmail::getReport()]);
    }
    public function blacklist(){
        return view('home.blacklist', ['result' => Blacklist::getReport()]);
    }
    public function holiday(){
        return view('home.holiday', ['result' => Holiday::getReport()]);
    }
    public function conference(){
        return view('home.conference', ['result' => Conference::getReport()]);
    }
    public function cdrtags(){
        return view('home.cdrtags', ['result' => CdrTag::getReport()]);
    }
    public function livecalls(){
        return view('home.livecalls', ['result' => CdrTag::getReport()]);
    }
}
