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
use App\Models\Dids;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PostExport;



class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->cdr = new CdrReport();
    }

    public function index(){
        //$cdr = new CdrReport();
        //echo 'dfsdf00fdcgvb';die;
        //$user = CdrReport::where('assignedto' , Auth::user()->groupid)->get();
        //dd(CdrReport::getReport());
        return view('home.cdrreport', ['result' => CdrReport::getReport(),'departments'=> OperatorDepartment::getDepartmentbygroup(),'operators'=>OperatorAccount::getOperatorbygroup(),'statuses'=> CdrReport::getstatus(),'dnidnames'=>CdrReport::getdids(),'tags'=>CdrTag::getTag(), 'account_service'=> Accountgroup::getservicebygroup()]);
    }

    public function graphReport(Request $request) {
        // $validator = Validator::make($request->all(), [
        //     'fname' => 'required',
        //     'lname' => 'required',
        //     'email' => 'required|email',
        // ]); 
        $data = array();
        $max = 0;
        $result = CdrReport::getGraphReport($request->all());
        //dd($result);
        foreach($result as $row) {
            if($request->get('status') == null)
                $data['label'] = 'both';
            else 
                $data['label'] = ''; 
            $data[$row->status][Carbon::parse($row->datetime)->format('Y-m-d')] = $row->total;
            $max = ($max < $row->total) ? $row->total : $max;
        }
        //dd($data);
        $data['max'] = $max;
        return $data;
    
    }

    public function addContact(Request $request) 
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $contact = ['fname' => $request->get('fname'),
                     'lname'=> $request->get('lname'),
                     'phone'=> $request->get('phone'),
                     'email'=> $request->get('email'),
                     'groupid'=> $request->get('groupid')
                    ];
            if(!empty($request->get('contact_id'))) {
                DB::table('contacts')
                    ->where('id', $request->get('contact_id'))
                    ->update($contact);
                $data['success'] = 'Contact update successfully.';
                $data['fname'] = $request->get('fname');
            } else {
                DB::table('contacts')->insert($contact);
                $data['success'] = 'Contact added successfully.';
                $data['fname'] = $request->get('fname');
            }
           
        } 
         return $data;
    }
    
    public function addCdrTag(Request $request) 
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'tag' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $tag = ['tag' => $request->get('tag')];
            DB::table('cdr')
                ->where('cdrid', $request->get('cdrid'))
                ->update($tag);
            $data['success'] = 'Tag update successfully.';
            $data['tag'] = $request->get('tag');
        } 
         return $data;
    }

    public function addNote(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $note = ['operator' => Auth::user()->username,
                     'note'=> $request->get('note'),
                     'uniqueid'=> $request->get('uniqueid'), 
                     'datetime' => NOW()
                    ];
            $id = DB::table('cdr_notes')->insertGetId($note);
            $data['result'] = $note;
            $data['id'] = $id;
            $data['success'] = 'Note added successfully.';
        } 
         return $data;
    }

    public function notes($id) {
        return $notes = DB::table('cdr_notes')
            ->where('uniqueid', $id)
            ->get();
    }

    public function callHistory($number) {
        return CdrReport::where('number', $number)->get();
    }

    public function addReminder(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'startdate' => 'required',
            'starttime' => 'required',
        ], [
            'startdate.required' => 'Reminder date is required',
            'starttime.required' => 'Reminder time is required'
        ]);   

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $date = Carbon::parse($request->get('startdate'))->format('Y-m-d')." ".$request->get('starttime').":00";
            if(!empty($request->get('id'))) {
                $reminder = [
                     'followupdate'=> $date,
                     'appoint_status'=> $request->get('appoint_status'),
                    ]; 
                DB::table('reminders')
                    ->where('id', $request->get('id'))
                    ->update($reminder);
                $data['success'] = 'Reminder update successfully.';
            } else {
                $cdr_query = DB::table('cdr')->where('cdrid', $request->get('cdr_id'))->get();
                $reminder = ['number' => $cdr_query[0]->number,
                     'groupid'=> Auth::user()->groupid,
                     'resellerid'=> $cdr_query[0]->resellerid,
                     'operatorid'=> Auth::user()->id,
                     'followupdate'=> $date,
                     'appoint_status'=> 'live',
                     'follower'=> Auth::user()->username,
                     'recordedfilename'=> $cdr_query[0]->recordedfilename,
                     'calldate'=> $cdr_query[0]->datetime,
                     'deptname'=> $cdr_query[0]->deptname,
                     'uniqueid'=> $cdr_query[0]->uniqueid,
                     'secondleg'=> $cdr_query[0]->secondleg,
                     'assignedto'=> $cdr_query[0]->assignedto,
                    ]; 
                DB::table('reminders')->insert($reminder);
                $data['success'] = 'Reminder added successfully.';
            }
            
        } 
         return $data;
    }

    public function getReminder($id) {
        return $reminder = DB::table('reminders')->where('id', $id)->get();
    }

    public function deleteReminder($id) {
        $res = DB::table('reminders')->where('id',$id)->delete();
        toastr()->success('Reminder delete successfully.');
        return redirect()->route('Reminder');
    }

    public function addCdr(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|max:12',
            'phone' => 'required|max:12'
        ], [
            'number.required' => 'Customer number field is required.',
            'phone.required' => 'Operator number field is required.'
        ]);    
          
        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $did = Dids::where('assignedto' , Auth::user()->groupid)->select('c2cpri', 'c2ccallerid')->get();
 
            //dd($did[0]->c2ccallerid);
            $operatorid = (Auth::user()->usertype == 'groupadmin') ? '' : Auth::user()->id;
            $cdr = ['number' => $request->get('number'),
                    'did_no' => !empty($did[0]) ? $did[0]->c2ccallerid : '',
                    'groupid' => Auth::user()->groupid,
                    'resellerid' => Auth::user()->resellerid,
                    'operatorid' => $operatorid,
                    'deptname' => 'C2C',
                    'status' => 'DIALING',
                    ];
                
                DB::table('cdr')->insert($cdr);
                $data['success'] = 'Cdr added successfully.';
        } 
        return $data;
    }

    public function assignCdr(Request $request) 
    {
        foreach($_POST['cdr_id'] as $cdrid) {
            if(isset($_POST['opr_id']) && $_POST['opr_id'] !== '0') {
                 DB::table('cdr')
                ->where('cdrid', $cdrid)
                ->update(['assignedto' => $_POST['opr_id']]);

                $oprAccount = DB::table('operatoraccount')->select('deviceid')->where('id',$_POST['opr_id'])->get();
            
                if($oprAccount[0]->deviceid != null) {

                    $cdr = DB::table('cdr')->select('number', 'datetime', 'deptname', 'status')->where('cdrid', $cdrid)->get();
                    $cdrrow = $cdr[0];
                    
                    $message='There is new call assigned to you from ' .$cdrrow->number. ' called at ' .$cdrrow->datetime. ' to ' .$cdrrow->deptname. ' call status: ' .$cdrrow->status;
                    $assigncdr = ['message' => $message,
                                'operatorid' => $_POST['opr_id'],
                                'status' => 0,
                                'deviceid' => $oprAccount[0]->deviceid,
                                'cdrid' => $_POST['cdr_id']
                            ];
                    DB::table('assigncdr_app_notify')->insert($assigncdr);
                }

                if($_POST['type'] == 'E') {
                $assign_email = ['cdrid' => $cdrid,
                            'operatorid' => $_POST['opr_id']
                        ];
                DB::table('assigncdr_email_operator')->insert($assign_email);
                }

                if($_POST['type'] == 'S') {
                    $assign_sms  = ['cdrid' => $cdrid,
                                'operatorid' => $_POST['opr_id'],
                                'groupid' => Auth::user()->groupid
                            ];
                    DB::table('assigncdr_sms_operator')->insert($assign_sms);
                }
               
            } else {
                DB::table('cdr')
                ->where('cdrid', $cdrid)
                ->update(['assignedto' => '']);
            }

        }
        $data['success'] = 'Cdr Assign successfully.';
        return $data;
    }

    public function deleteComment($id) {
        $res = DB::table('cdr_notes')->where('id',$id)->delete();
        return response()->json([
            'status' => $res
        ]);
    }

    public function downloadFile($id, $file) {
        //dd($file);
        $myFile = '/var/spool/asterisk/monitorDONE/MP3/'.$id.'/'.$file;
        return response()->download($myFile);
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
    // public function contacts(){
    //     return view('home.contacts', ['result' => Contact::getReport()]);
    // }
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
    // public function livecalls(){
    //     return view('home.livecalls', ['result' => CdrTag::getReport()]);
    // }
    public function cdrexport()
    {
        return Excel::download(new PostExport(), "Report.csv");
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
