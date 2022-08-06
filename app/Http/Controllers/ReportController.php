<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CdrReport;
use App\Models\CdrArchive;
use App\Models\CdrPbx;
use App\Models\OperatorAccount;
use App\Models\Account;
use App\Models\VoiceEmail;
use App\Models\Blacklist;
use App\Models\Holiday;
use App\Models\Conference;
use App\Models\CdrTag;
use App\Models\OperatorDepartment;
use App\Models\Accountgroup;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

date_default_timezone_set('Asia/Kolkata');

class ReportController extends Controller
{
    private $departmentObj;
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->cdr = new CdrReport();
    }

    public function index(Request $request) {
        $requests = $request->all();
        if (in_array(Auth::user()->usertype, ["admin","reseller"])) {
            $groupId = $request->get('customer');
        } else {
            $groupId = Auth::user()->groupid;
        }
        $dateOptions = [
            '' => 'All',
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'week' => '1 Week',
            'month' => '1 Month',
            'custom' => 'Custom'
        ];
        $customers = getCustomers();
        $operatorAccount = OperatorAccount::find(Auth::user()->operator_id);
        $playRecording = (empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->play == '1')) ? true : false;
        $downloadRecording = (empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->download == '1')) ? true : false;
        return view('home.cdrreport', [
            'customers' => $customers,
            'departments'=> OperatorDepartment::getDepartmentbygroup($groupId),
            'operators'=>OperatorAccount::getOperatorbygroup($groupId),
            'statuses'=> CdrReport::getstatus($groupId),
            'dnidnames'=>CdrReport::getdids($groupId),
            'tags'=>CdrTag::getTag($groupId),
            'account_service'=> Accountgroup::getservicebygroup(),
            'dateOptions' => $dateOptions,
            'requests' => $requests,
            'operatorAccount' => $operatorAccount,
            'playRecording' => $playRecording,
            'downloadRecording' => $downloadRecording,
            'fetchArchive' => '0'
        ]);
    }

    public function cdrDataAjaxLoad(Request $request) {
        if (in_array(Auth::user()->usertype, ["admin","reseller"])) {
            $groupId = $request->get('customer');
        } else {
            $groupId = Auth::user()->groupid;
        }
        $department = $request->get('department');
        $operator = $request->get('operator');
        $tag = $request->get('tag');
        $status = $request->get('status');
        $assigned_to = $request->get('assigned_to');
        $did_no = $request->get('did_no');
        $caller_number = $request->get('caller_number');
        $date = $request->get('date');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $fetchArchive = $request->get('fetchArchive') == '1' ? true : false;
        $mainTable = $fetchArchive ? 'cdr_archive' : 'cdr';
        $searchText = $request->get('search')['value'];

        $sortOrder = $request->get('order')['0'];
        $columnArray = [
            '0' => ['accountgroup.name'],
            //not working at present. need to recheck
            // '1' => [$mainTable . '.number', 'contacts.fname', 'contacts.lname'],
            '2' => [$mainTable . '.datetime'],
            '3' => [$mainTable . '.firstleg', $mainTable . '.secondleg'],
            '4' => [$mainTable . '.creditused'],
            '5' => [$mainTable . '.status'],
            '6' => [$mainTable . '.deptname'],
            '7' => ['operatoraccount.opername'],
        ];
        $sortOrderArray = [];
        foreach ($columnArray[$sortOrder['column']] as $field) {
            $sortOrderArray[$field] = $sortOrder['dir'];
        }

        $limit = $request->get('length');
        $skip = $request->get('start');
        $draw = $request->get('draw');

        $result = CdrReport::getReportAjax($groupId, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date, $fetchArchive, $searchText, $sortOrderArray, $limit, $skip, $draw);
        return new JsonResponse($result);
    }

    public function graphReport(Request $request) {
        $data = array();
        $max = 0;
        $result = CdrReport::getGraphReport($request->all());
        $b_data = array();
        $bar_data = array();
        $ind = array();
        $nd = array();
        $date = null;
        if( !empty($result) ){
            $i = 0;
            foreach ($result as $pkey => $bvalue) {
                $b_data[] = $bvalue->newdate;
                if(!empty($date) && $date == $bvalue->newdate) {
                    $nd["answered"] = $bar_data[$i-1]['answered'];
                    $nd["dialed"] = ($bvalue->status == 'DIALING') ? $bvalue->Count : 0;
                    $nd["date"] = $bvalue->newdate;
                    $bar_data[$i-1] = $nd;
                } else {
                    $ind["answered"] = ($bvalue->status == 'ANSWERED') ? $bvalue->Count : 0;
                    $ind["dialed"] = ($bvalue->status == 'DIALING') ? $bvalue->Count : 0;
                    $ind["date"] = $bvalue->newdate;
                    $bar_data[] = $ind;
                    $i++;
                }
                $date = $bvalue->newdate;
            }
            $dates = array();
            $dialed = array();
            $answered = array();
            foreach($bar_data as $val) {
                array_push($dates, $val['date']);
                array_push($dialed, $val['dialed']);
                array_push($answered, $val['answered']);
            }
        }
        $data['dates'] = $dates;
        $data['dialed'] = $dialed;
        $data['answered'] = $answered;
        return $data;

    }

    public function addContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $contact = [
                'fname' => $request->get('fname') ? $request->get('fname') : '',
                'lname' => $request->get('lname') ? $request->get('lname') : '',
                'phone' => $request->get('phone'),
                'email' => $request->get('email') ? $request->get('email') : '',
                'groupid' => Auth::user()->groupid
            ];
            $contactId = $request->get('contact_id');
            if(!empty($contactId)) {
                DB::table('contacts')
                    ->where('id', $contactId)
                    ->update($contact);
                $data['success'] = 'Contact updated successfully.';
            } else {
                $contactId = DB::table('contacts')->insertGetId($contact);
                $data['success'] = 'Contact added successfully.';
            }
            $data['contactId'] = $contactId;
            $data['callerId'] = $contact['fname'] . ' ' . $contact['lname'];
            $data = array_merge($data, $contact);

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
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function callHistory($number) {
        return CdrReport::where('number', $number)->orderBy('datetime','DESC')->get();
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
                $reminderSeen = $request->get('appoint_status') == 'close' ? '1' : '0';
                $reminder = [
                     'followupdate'=> $date,
                     'appoint_status'=> $request->get('appoint_status'),
                     'reminder_seen' => $reminderSeen
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
                     'reminder_seen' => '0'
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

    // public function deleteReminder($id) {
    //     dd($id);
    //     $res = DB::table('reminders')->where('id',$id)->delete();
    //     toastr()->success('Reminder delete successfully.');
    //     return redirect()->route('Reminder');
    // }

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
            $res = callConfig($request->all());
            if($res['status']) {
                $data['success'] = 'Cdr added successfully.';
            }
        }
        return $data;
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|max:12',
            'message' => 'required'
        ], [
            'number.required' => 'Customer number field is required.',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $res = smsConfig($request->all());
            //dd($res);
            if($res['status']) {
                $data['success'] = 'Message sent successfully.';
            } else {
                $data['error'] = $res['error'];
            }
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
                                'cdrid' => $cdrid
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
        return ['status' => true, 'message' => 'Cdr assigned successfully.'];
    }

    public function deleteComment($id) {
        $data = DB::table('cdr_notes')->where('id',$id)->first();
        $res = DB::table('cdr_notes')->where('id',$id)->delete();
        $notesCount = DB::table('cdr_notes')
            ->where('uniqueid', $data->uniqueid)
            ->count();
        return response()->json([
            'status' => $res,
            'uniqueId' => $data->uniqueid,
            'notesCount' => $notesCount,
        ]);
    }

    public function downloadFile($id, $file) {
        $myFile = '/home/var/spool/asterisk/monitorDONE/MP3/'.$id.'/'.$file;
        return response()->download($myFile);
    }

    // public function cdrreportarchive(Request $request){
    //     $customers = DB::table('accountgroup')->select('id', 'name')->get();
        
    //     $customer = $request->get('customer');
    //     $department = $request->get('department');
    //     $operator = $request->get('operator');
    //     $tag = $request->get('tag');
    //     $status = $request->get('status');
    //     $assigned_to = $request->get('assigned_to');
    //     $did_no = $request->get('did_no');
    //     $caller_number = $request->get('caller_number');
    //     $date = $request->get('date');
    //     $start_date = $request->get('start_date');
    //     $end_date = $request->get('end_date');
    //     $dateOptions = [
    //         '' => 'All',
    //         'today' => 'Today',
    //         'yesterday' => 'Yesterday',
    //         'week' => '1 Week',
    //         'month' => '1 Month',
    //         'custom' => 'Custom'
    //     ];
    //     $result = CdrArchive::getReport($customer, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date);
    //     return view('home.cdrreportarchive', ['customers' => $customers, 'result' => $result,'departments'=> OperatorDepartment::getDepartmentbygroup(Auth::user()->groupid),'operators'=>OperatorAccount::getOperatorbygroup(Auth::user()->groupid),'statuses'=> CdrReport::getstatus(Auth::user()->groupid),'dnidnames'=>CdrReport::getdids(Auth::user()->groupid),'tags'=>CdrTag::getTag(Auth::user()->groupid), 'account_service'=> Accountgroup::getservicebygroup(),'dateOptions' => $dateOptions,'requests' => $request->all()]);
    // }

    // public function cdrreportarchive(Request $request){
    //     $requests = $request->all();
    //     if (in_array(Auth::user()->usertype, ["admin","reseller"])) {
    //         $groupId = $request->get('customer');
    //     } else {
    //         $groupId = Auth::user()->groupid;
    //     }
    //     $dateOptions = [
    //         '' => 'All',
    //         'today' => 'Today',
    //         'yesterday' => 'Yesterday',
    //         'week' => '1 Week',
    //         'month' => '1 Month',
    //         'custom' => 'Custom'
    //     ];
    //     $customers = getCustomers();
    //     $operatorAccount = OperatorAccount::find(Auth::user()->operator_id);
    //     $playRecording = (empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->play == '1')) ? true : false;
    //     $downloadRecording = (empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->download == '1')) ? true : false;
    //     return view('home.cdrreportarchive', [
    //         'customers' => $customers,
    //         'departments'=> OperatorDepartment::getDepartmentbygroup($groupId),
    //         'operators'=>OperatorAccount::getOperatorbygroup($groupId),
    //         'statuses'=> CdrReport::getstatus($groupId),
    //         'dnidnames'=>CdrReport::getdids($groupId),
    //         'tags'=>CdrTag::getTag($groupId),
    //         'account_service'=> Accountgroup::getservicebygroup(),
    //         'dateOptions' => $dateOptions,
    //         'requests' => $requests,
    //         'operatorAccount' => $operatorAccount,
    //         'playRecording' => $playRecording,
    //         'downloadRecording' => $downloadRecording
    //     ]);
    // }
    public function cdrreportarchive(Request $request) {
        $requests = $request->all();
        if (in_array(Auth::user()->usertype, ["admin","reseller"])) {
            $groupId = $request->get('customer');
        } else {
            $groupId = Auth::user()->groupid;
        }
        $dateOptions = [
            '' => 'All',
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'week' => '1 Week',
            'month' => '1 Month',
            'custom' => 'Custom'
        ];
        $customers = getCustomers();
        $operatorAccount = OperatorAccount::find(Auth::user()->operator_id);
        $playRecording = (empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->play == '1')) ? true : false;
        $downloadRecording = (empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->download == '1')) ? true : false;
        return view('home.cdrreport', [
            'customers' => $customers,
            'departments'=> OperatorDepartment::getDepartmentbygroup($groupId),
            'operators'=>OperatorAccount::getOperatorbygroup($groupId),
            'statuses'=> CdrReport::getstatus($groupId),
            'dnidnames'=>CdrReport::getdids($groupId),
            'tags'=>CdrTag::getTag($groupId),
            'account_service'=> Accountgroup::getservicebygroup(),
            'dateOptions' => $dateOptions,
            'requests' => $requests,
            'operatorAccount' => $operatorAccount,
            'playRecording' => $playRecording,
            'downloadRecording' => $downloadRecording,
            'fetchArchive' => '1'
        ]);
    }

    public function cdrreportout() {
        //department - deptname from cdrpbx

        return view('home.cdrreportout', ['result' => CdrPbx::getReport(),
        'departments'=> CdrPbx::get_dept_by_group(),'operators'=>OperatorAccount::getOperatorbygroup(Auth::user()->groupid),'statuses'=> CdrPbx::getstatus(),'dnidnames'=>CdrPbx::getdids(),'tags'=>CdrTag::getTag(Auth::user()->groupid)]);
    }
    public function operator(){
        return view('home.operator', ['result' => OperatorAccount::getReport(),'operators'=>OperatorAccount::getOperatorbygroup(Auth::user()->groupid)]);
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
    public function addConference(Request $request) {
        $validator = Validator::make($request->all(), [
            'moderator' => 'required|max:12',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $conference_data = [
                'moderator' => $request->get('moderator'),
                'groupid' => Auth::user()->groupid,
                'resellerid' => Auth::user()->resellerid,
                'comments' => $request->get('comments'),
                'status' => 'LIVE',
            ];

            $conference = new Conference($conference_data);
            $conference->save();
            if(!empty($conference->id)) {
                $max_conf = Auth::user()->load('accountdetails')->accountdetails->max_no_confrence;
                for($i = 0; $i < $max_conf; $i++)
                {  $j = $i + 1;
                    if($request->get($j) > 6 && is_numeric($request->get($j)))
                    {
                        $conf_log = [
                            'member' => $request->get($j),
                            'confrence_id' => $conference->id,
                            'dialstatus' => 'NOT Dialed'
                        ];
                        DB::table('confrence_member_log')->insert(
                            $conf_log
                        );
                    }
                }
            }
            $data['success'] = 'Conference added successfully.';
        }

        return $data;
    }

    public function editComment(Request $request) {
        if(!empty($request->get('conf_id'))) {
            Conference::where('id', $request->get('conf_id'))->update(['comments' => $request->get('comments')]);
            $data['success'] = 'Comment update successfully.';
        } else {
            $data['error'] = 'Some error occured.';
        }

        return $data;
    }
    public function callDetails($id) {
        return $callDetails = DB::table('confrence_member_log')
            ->where('confrence_id', $id)
            ->get();
    }

    public function cdrtags(){
        return view('home.cdrtags', ['result' => CdrTag::getReport()]);
    }
    // public function livecalls(){
    //     return view('home.livecalls', ['result' => CdrTag::getReport()]);
    // }
    public function cdrexport(Request $request) {
        
        if(Auth::user()->usertype ==  'admin') {
            $groupId = $request->get('customer');
        } else {
            $groupId = Auth::user()->groupid;
        }
        $department = $request->get('department');
        $operator = $request->get('operator');
        $tag = $request->get('tag');
        $status = $request->get('status');
        $assigned_to = $request->get('assigned_to');
        $did_no = $request->get('did_no');
        $caller_number = $request->get('caller_number');
        $date = $request->get('date');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $fetchArchive = $request->get('fetchArchive') == '1' ? true : false;
        $searchText = $request->get('search_text');

        $data = $this->getExportData($fetchArchive, $groupId, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date, $searchText);
        return Excel::create('Report', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download('csv');
        // return Excel::download(new PostExport(), "Report.csv");
    }

    public function getExportData($fetchArchive,$groupId, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date, $searchText)
    {
        $data = CdrReport::getReportAjax($groupId, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date, $fetchArchive, $searchText);
        if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller') {
            $columns = ['DID_no', 'Customer', 'Caller ID Number', 'Caller ID Name', 'Date', 'Totaltime', 'Talktime', 'Status', 'Credit', 'Department', 'Agent'];
        } elseif(Auth::user()->usertype ==  'groupadmin') {
            $columns = ['DID_no', 'Caller ID Number', 'Caller ID Name', 'Email', 'Date', 'Totaltime', 'Talktime', 'Status', 'Credit', 'Department', 'Call Tag', 'Agent', 'Assigned To'];
        } elseif(Auth::user()->usertype ==  'operator') {
            $columns = ['DID_no', 'Caller ID Number', 'Caller ID Name', 'Email', 'Date', 'Totaltime', 'Talktime', 'Status', 'Credit', 'Department', 'Call Tag', 'Agent', 'Assignedto'];
        }

        $result_array = [];
        $notesCount = 0;
        if(!empty($data['data']))
        {
            foreach($data['data'] as $k=>$cdrr) {
                $array = array();
                if(Auth::user()->usertype ==  'admin') {
                    $array = array($cdrr['didNumber'],$cdrr['customerName'],$cdrr['number'], $cdrr['fullName'], $cdrr['dateTime'],$cdrr['totalTime'],$cdrr['talkTime'],$cdrr['status'],$cdrr['creditUsed'],$cdrr['departmentName'], $cdrr['operatorName']);
                } elseif(Auth::user()->usertype == 'reseller') {
                    $array = array($cdrr['didNumber'], $cdrr['customerName'],$cdrr['number'], $cdrr['fullName'], $cdrr['dateTime'],$cdrr['totalTime'],$cdrr['talkTime'],$cdrr['status'],$cdrr['creditUsed'],$cdrr['departmentName'], $cdrr['operatorName']);
                } elseif(Auth::user()->usertype ==  'groupadmin') {
                    $array = array($cdrr['didNumber'], $cdrr['number'], $cdrr['fullName'], $cdrr['email'], $cdrr['dateTime'],$cdrr['totalTime'],$cdrr['talkTime'], $cdrr['status'],$cdrr['creditUsed'],$cdrr['departmentName'],$cdrr['tag'], $cdrr['operatorName'], $cdrr['assignedOperatorName']);
                } elseif(Auth::user()->usertype ==  'operator') {
                    $array = array($cdrr['didNumber'], $cdrr['number'], $cdrr['fullName'], $cdrr['email'], $cdrr['dateTime'],$cdrr['totalTime'],$cdrr['talkTime'],$cdrr['status'],$cdrr['creditUsed'],$cdrr['departmentName'],$cdrr['tag'], $cdrr['operatorName'], $cdrr['assignedOperatorName']);
                }
                $notes = $this->notes($cdrr['uniqueId']);
                $notesCount = count($notes) > $notesCount ? count($notes) : $notesCount;
                if (!empty($notes)) {
                    foreach ($notes as $index => $note) {
                        $array[] = "Comments: " . $note->note . " Date: " . $note->datetime . " Operator: " . $note->operator;
                    }
                }
                //dd($array);
                $result_array[] = $array;
            }
        }
        for ($i = 0 ; $i < $notesCount ; $i++) {
            $columns[] = 'notes_' . ($i+1);
        }
        array_unshift($result_array, $columns);
        return $result_array;
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

    public function cdrCallDetails(Request $request) {
        $cdrId = $request->request->get('cdrId');
        $data = DB::table('cdr_sub')
                ->leftJoin('operatoraccount', 'cdr_sub.operator', 'operatoraccount.id')
                ->select('cdr_sub.date_time', 'operatoraccount.opername', 'cdr_sub.status')
                ->where('cdr_sub.cdr_id', $cdrId)
                ->get();
        $content = View('home.cdr_call_details', ['data' => $data])->render();

        return new JsonResponse(['status' => true, 'content' => $content]);
    }

    public function fetchDepartments(Request $request) {
        $groupId = $request->request->get('groupId');
        $data = OperatorDepartment::getDepartmentbygroup($groupId);

        return new JsonResponse(['status' => true, 'data' => $data]);
    }

    public function fetchOperators(Request $request) {
        $groupId = $request->request->get('groupId');
        $data = OperatorAccount::getOperatorbygroup($groupId);

        return new JsonResponse(['status' => true, 'data' => $data]);
    }

    public function fetchTags(Request $request) {
        $groupId = $request->request->get('groupId');
        $data = CdrTag::getTag($groupId);

        return new JsonResponse(['status' => true, 'data' => $data]);
    }

    public function fetchDidNumbers(Request $request) {
        $groupId = $request->request->get('groupId');
        $data = CdrReport::getdids($groupId);

        return new JsonResponse(['status' => true, 'data' => $data]);
    }
}
