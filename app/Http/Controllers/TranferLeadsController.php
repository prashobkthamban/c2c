<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Lead_Products;
use App\Models\CdrReport_Lead;
use App\Models\Product;
use App\Models\lead_stages;
use App\Models\Lead_Mail;
use App\Models\Lead_CallLog;
use App\Models\Lead_Msg;
use App\Models\Lead_Notes;
use App\Models\Converted;
use App\Models\Lead_activity;
use App\Models\Lead_Reminder;
use App\Models\Proposal;
use App\Models\Product_details;

use Illuminate\Support\Facades\Mail;

use File;

/*use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\ToModel;*/

use Excel;

date_default_timezone_set('Asia/Kolkata'); 

class TranferLeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->leads = new CdrReport_Lead();
    }

    public function index(){

        $users_lists = DB::table('operatoraccount')
                    ->select('operatoraccount.*')->where('groupid', Auth::user()->groupid)
                    ->get();

        return view('cdr.transfer_lead',compact('users_lists'));
    }

    public function transferleads(Request $request) {

        DB::table('cdrreport_lead')->where('operatorid', $request->get('transfer_from'))->update(['operatorid' => $request->get('transfer_to')]);

        $message = toastr()->success('Lead successfully Transfer.');
        return Redirect::back()->with('message');
   }

}




















?>