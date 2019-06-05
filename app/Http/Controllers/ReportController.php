<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
use App\Models\OperatorDepartment;
use App\Models\Accountgroup;

//use Excel;



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
        return view('home.cdrreport', ['result' => CdrReport::getReport(),'departments'=> OperatorDepartment::getDepartmentbygroup(),'operators'=>OperatorAccount::getOperatorbygroup(),'statuses'=> CdrReport::getstatus(),'dnidnames'=>CdrReport::getdids(),'tags'=>CdrTag::getTag()]);
    }
    public function cdrreportarchive(){
        return view('home.cdrreportarchive', ['result' => CdrArchive::getReport()]);
    }
    public function cdrreportout(){
        //department - deptname from cdrpbx
        
        return view('home.cdrreportout', ['result' => CdrPbx::getReport(),'departments'=> CdrPbx::get_dept_by_group(),'operators'=>OperatorAccount::getOperatorbygroup(),'statuses'=> CdrPbx::getstatus(),'dnidnames'=>CdrPbx::getdids(),'tags'=>CdrTag::getTag()]);
    }
    public function operator(){
        return view('home.operator', ['result' => OperatorAccount::getReport(),'operators'=>OperatorAccount::getOperatorbygroup()]);
    }
    public function contacts(){
        return view('home.contacts', ['result' => Contact::getReport()]);
    }
    public function voicemail(){
        return view('home.voicemail', ['result' => VoiceEmail::getReport(),'departments'=> VoiceEmail::get_dept_by_group(),'dnidnames'=>VoiceEmail::getdids()]);
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
    public function cdrexport()
    {
           

            if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
            {
                $columns = 'DiD_num,Customer, Caller, Date,Totaltime, Talktime, Status, Credit, Department, Operator,OperatorNumber';
            }
            elseif(Auth::user()->usertype ==  'groupadmin')
            {
                $columns = 'DID_no,Caller , Date ,Totaltime ,Talktime , Status , Credit, Department,Call_tag, Operator,Assignedto';
            }
            elseif(Auth::user()->usertype ==  'operator')
            {
                $columns = 'DID_no,Caller , Date, Totaltime ,Talktime, Status ,Credit, Department,Call_tag,Assignedto ';
            }

            $cdrexports = CdrReport::cdrExport();
        
        

            $result_array = array( explode(',',$columns));
            if(!empty($cdrexports))
            {
                foreach($cdrexports as $k=>$cdrr) {
                    $array = array();
                    if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
                    {
                        $array = array($cdrr->did_no,$cdrr->name,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg,$cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->opername ,$cdrr->phonenumber);
                    }
                    elseif(Auth::user()->usertype ==  'groupadmin')
                    {
                        $array = array($cdrr->did_no,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg, $cdrr->status,($cdrr->creditused != '') ? $cdrr->creditused :"a" ,($cdrr->deptname != '') ? $cdrr->deptname : "s",($cdrr->tag != '' ) ? $cdrr->tag : "d",($cdrr->opername != '' ) ? $cdrr->opername : "f",($cdrr->assignedto != '') ? $cdrr->assignedto : "g");
                    }
                    elseif(Auth::user()->usertype ==  'operator')
                    {
                        $array = array($cdrr->did_no,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg,$cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->tag,$cdrr->opername);
                        
                    }
                   
                    $result_array[] = $array;
                }
            }
            //print "<pre>";
           // print_r($result_array);
         return;
        // $collection = collect($result_array);
        // return Excel::download($result_array, 'Report.csv');

            
            
    }

    public function cdroutexport()
    {
           

            if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
            {
                $columns = 'Unique_ID,DiD_num,Customer, Caller, Date,Totaltime, Talktime, Status, Credit, Department, Operator,OperatorNumber';

            }
            elseif(Auth::user()->usertype ==  'groupadmin')
            {
                $columns = 'Unique_ID,DID_no,Caller , Date ,Totaltime ,Talktime , Status , Credit, Department, Operator,Assignedto';
            }
            elseif(Auth::user()->usertype ==  'operator')
            {
                $columns = 'Unique_ID,DID_no,Caller , Date, Totaltime ,Talktime, Status ,Credit, Department,Assignedto ';
            }

            $cdrexports = CdrPbx::cdroutExport();
        
     

            $result_array = array( $columns);
            if(!empty($cdrexports))
            {
                foreach($cdrexports as $k=>$cdrr) {
                    $array = array();
                    if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
                    {
                        $array = array($cdrr->uniqueid,$cdrr->did_no,$cdr->name,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg,$cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->opername ,$cdrr->phonenumber);
                    }
                    elseif(Auth::user()->usertype ==  'groupadmin')
                    {
                        $array = array($cdrr->uniqueid,$cdrr->did_no,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg, $cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->opername,$cdrr->assignedto);
                    }
                    elseif(Auth::user()->usertype ==  'operator')
                    {
                        $array = array($cdrr->uniqueid,$cdrr->did_no,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdr->secondleg,$cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->opername);
                        
                    }
                   
                    $result_array[] = $array;
                }
            }
           //
           // return;
            $collection = collect($result_array);
           
            return Excel::download($collection, 'Report.csv');
            
    }

    public function operatordept()
    {
        $account_group = Accountgroup::getdetailsbygroup();
        $oper_dept = ($account_group->operator_dpt == 'Yes') ? 1 : 0;
        $c2c = ($account_group->c2c == 'Yes') ? 1 : 0;
        $result =  OperatorDepartment::getReport($oper_dept,$c2c);
        return view('home.operator_dept', ['result' => $result]);
    }
}
