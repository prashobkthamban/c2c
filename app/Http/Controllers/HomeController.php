<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CdrTag;
use App\Models\CdrReport;
use App\Models\ToDoTask;

date_default_timezone_set('Asia/Kolkata');

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        if (!Auth::check()) {
            return redirect('login');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = date("Y-m-d");
        //dd($today);
        $onemonthdate = date("m/d/Y", strtotime("-1 month"));
        $sdate = isset($_REQUEST['dfrom']) ? date('m/d/Y', strtotime($_REQUEST['dfrom'])) : $onemonthdate;
        $edate = isset($_REQUEST['dto']) ? date('m/d/Y', strtotime($_REQUEST['dto'])) : date('m/d/Y');
        $qedate = date("Y-m-d", strtotime($edate));
        $qsdate = date("Y-m-d", strtotime($sdate));

        $incoming_calls = [];
        $opcallList = [];
        $insight_ivr = [];
        $insightData = [];
        $announcements = [];
        $activeoperator = [];
        $g_callstoday = [];
        $g_activecalls = [];
        $activecalls = [];
        $ivranswer = [];
        $ivrmissed = [];
        // $sdate = [];
        // $edate = [];
        $nousers = [];
        $inusers = [];
        $level_1 = [];
        $level_2 = [];
        $level_3 = [];
        $level_4 = [];
        $level_5 = [];
        $level_6 = [];
        $level_7 = [];
        $todo_lists = [];
        $remainders = [];
        $group_admin = [];

        if (Auth::user()->usertype == 'admin') {
            $nousers = DB::table('accountgroup')->count();
            $inusers = DB::table('accountgroup')
                ->whereDate('enddate', '<', date("Y-m-d"))
                ->where('status', 'Inactive')
                ->count();
        }

        $g_callstoday = $g_activecalls = $activecalls = 0;

        $activeoperator = DB::table('operatoraccount')
            ->where('operatoraccount.groupid', Auth::user()->groupid)
            ->where('operatoraccount.oper_status', 'Online')
            ->count();
        if (Auth::user()->usertype == 'groupadmin') {
            $g_callstoday = DB::table('cdr')
                ->where('groupid', Auth::user()->groupid)
                ->whereDate('cdr.datetime', '=', $today)
                ->count();

            $incoming_calls = CdrReport::select(DB::raw('count(*) as count, status'))
                ->whereIn('status', ['ANSWERED', 'MISSED', 'AFTEROFFICE'])
                ->whereDate('cdr.datetime', '>=', $qsdate)
                ->whereDate('cdr.datetime', '<=', $qedate)
                ->groupBy('status')
                ->get();

            $insight_ivr = DB::table('cdr')
                ->select(DB::raw('count(*) as count, deptname as dept_name'))
                ->where('deptname', '!=', '')
                ->where('groupid', Auth::user()->groupid)
                ->whereDate('cdr.datetime', '>=', $qsdate)
                ->whereDate('cdr.datetime', '<=', $qedate)
                ->groupBy('deptname')
                ->get();
            $departments = DB::table('operatordepartment')
                ->select('dept_name')
                ->where('groupid', Auth::user()->groupid)
                ->get();
            //dd($insight_ivr);
            $insightData = array();
            $deptNames = array();
            foreach ($departments as $key => $dept) {
                if (count($insight_ivr) > 0) {
                    for ($i = 0; $i < count($insight_ivr); $i++) {
                        if ($insight_ivr[$i]->dept_name == $dept->dept_name) {
                            $deptNames[] = $dept->dept_name;
                            $insightData[$key]['deptname'] = $dept->dept_name;
                            $insightData[$key]['count'] = $insight_ivr[$i]->count;
                        } else {
                            if (!in_array($dept->dept_name, $deptNames)) {
                                $deptNames[] = $dept->dept_name;
                                $insightData[$key]['deptname'] = $dept->dept_name;
                                $insightData[$key]['count'] = 0;
                            }
                        }
                    }
                } else {
                    $insightData[$key]['deptname'] = $dept->dept_name;
                    $insightData[$key]['count'] = 0;
                }
            }
        } else if (Auth::user()->usertype == 'operator') {
            $g_callstoday = DB::table('cdr')
                ->where('operatorid', Auth::user()->operator_id)
                ->whereDate('cdr.datetime', '=', $today)
                ->count();
        } else if (Auth::user()->usertype == 'admin') {
            $g_callstoday = DB::table('cdr')
                ->whereDate('cdr.datetime', '=', $today)
                ->count();
        }

        if (Auth::user()->usertype == 'groupadmin') {
            $g_activecalls = DB::table('cur_channel_used')
                ->where('groupid', Auth::user()->groupid)
                ->count();
        } else if (Auth::user()->usertype == 'admin') {
            $activecalls = DB::table('cur_channel_used')
                ->count();
        }
        $ivranswer = DB::table('cdr')
            ->where('groupid', Auth::user()->groupid)
            ->where('status', 'ANSWERED')
            ->whereDate('datetime', '=', date("Y-m-d"))
            ->count();
        $ivrmissed = DB::table('cdr')
            ->where('groupid', Auth::user()->groupid)
            ->where('status', 'MISSED')
            ->whereDate('datetime', '=', date("Y-m-d"))
            ->count();

        $todo_lists = DB::table('todotask')
            ->select('*')
            ->where('status', '!=', 'Done')
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        if (Auth::user()->usertype == 'groupadmin') {

            $group_admin = '';
        } else if (Auth::user()->usertype == 'reseller') {

            $group_admin = DB::table('accountgroup')->where('resellerid', '=', Auth::user()->resellerid)->get();

            $groupid = DB::table('resellergroup')->where('id', Auth::user()->resellerid)->first();


            $de = json_decode($groupid->associated_groups);
        }

        $announcements = DB::table('dashbord_annuounce')->orderBy('id', 'desc')->get();
        return view('home.dashboard', compact('incoming_calls', 'insight_ivr', 'insightData', 'announcements', 'activeoperator', 'g_callstoday', 'g_activecalls', 'activecalls', 'ivranswer', 'ivrmissed', 'sdate', 'edate', 'nousers', 'inusers', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5', 'level_6', 'level_7', 'todo_lists', 'remainders', 'group_admin'));
    }

    public function dashboard()
    {
        $onemonthdate = date("m/d/Y", strtotime("-2 month"));
        $sdate = isset($_REQUEST['dfrom']) ? date('m/d/Y', strtotime($_REQUEST['dfrom'])) : $onemonthdate;
        $edate = isset($_REQUEST['dto']) ? date('m/d/Y', strtotime($_REQUEST['dto'])) : date('m/d/Y');
        //dd($sdate);
        $qedate = date("Y-m-d", strtotime($edate));
        $qsdate = date("Y-m-d", strtotime($sdate));

        $piechart = DB::table('cdr')
            ->select('cdr.status', DB::raw('count(*) as totalresult'))
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'cdr.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'cdr.operatorid', '=', 'operatoraccount.id')
            ->whereIn('cdr.status', ['ANSWERED', 'MISSED', 'AFTEROFFICE'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('cdr.status')
            ->get();
        $p_data = array();
        $total = 0;

        if (!empty($piechart)) {
            foreach ($piechart as $key => $value) {
                $ind = array();
                $ind["name"] = $value->status;
                $ind["value"] = $value->totalresult;
                $total += $value->totalresult;
                $p_data[] = $ind;
            }
        }
        $p_data[] = [
            'name' => 'Total',
            'value' => $total
        ];
        //dd($total);
        $barchart = DB::table('cdr')
            ->select('cdr.status', DB::raw('DATE(cdr.datetime) as newdate'), DB::raw('count(cdr.cdrid) as Count'))
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'cdr.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'cdr.operatorid', '=', 'operatoraccount.id')
            ->whereIn('cdr.status', ['ANSWERED', 'MISSED'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('newdate')
            ->groupBy('status')
            ->get();
        //echo Auth::user()->groupid; 3
        //dd($barchart);
        $b_data = array();
        $bar_data = array();
        $ind = array();
        $nd = array();
        $date = null;
        if (!empty($barchart)) {
            $i = 0;
            foreach ($barchart as $pkey => $bvalue) {
                $b_data[] = $bvalue->newdate;
                if (!empty($date) && $date == $bvalue->newdate) {
                    $nd["answered"] = $bar_data[$i - 1]['answered'];
                    $nd["missed"] = ($bvalue->status == 'MISSED') ? $bvalue->Count : 0;
                    $nd["date"] = $bvalue->newdate;
                    $bar_data[$i - 1] = $nd;
                } else {
                    $ind["answered"] = ($bvalue->status == 'ANSWERED') ? $bvalue->Count : 0;
                    $ind["missed"] = ($bvalue->status == 'MISSED') ? $bvalue->Count : 0;
                    $ind["date"] = $bvalue->newdate;
                    $bar_data[] = $ind;
                    $i++;
                }
                $date = $bvalue->newdate;
            }
            // dd($bar_data);
            // //for(array_unique($b_data))
            $dates = array();
            $missed = array();
            $answered = array();
            foreach ($bar_data as $val) {
                array_push($dates, $val['date']);
                array_push($missed, $val['missed']);
                array_push($answered, $val['answered']);
            }
        }
        //dd(array_unique($b_data));
        $barstacked = DB::table('cdr')
            ->select('cdr.status', DB::raw('HOUR(cdr.datetime) as time'), DB::raw('count(*) as totalresult'))
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'cdr.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'cdr.operatorid', '=', 'operatoraccount.id')
            ->whereIn('cdr.status', ['ANSWERED', 'MISSED'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('time')
            ->groupBy('cdr.status')
            ->get();

        //dd($barstacked);

        $answered_bar = array();
        $missed_bar = array();
        foreach ($barstacked as $key => $value) {
            if ($value->status == 'ANSWERED') {
                $answered_bar[$value->time] = $value->totalresult;
            } else {
                $missed_bar[$value->time] = $value->totalresult;
            }
        }

        for ($i = 1; $i <= 24; $i++) {
            if (!array_key_exists($i, $answered_bar)) {
                $answered_bar[$i] = 0;
            }
            if (!array_key_exists($i, $missed_bar)) {
                $missed_bar[$i] = 0;
            }
        }
        ksort($answered_bar);
        ksort($missed_bar);

        $new_ans = array();
        $new_miss = array();

        foreach ($answered_bar as $x => $x_value) {
            $new_ans[$x] = $x_value;
        }

        foreach ($missed_bar as $m => $m_value) {
            $new_miss[$m] = $m_value;
        }

        $crm_total_leads = [];

        $crm_data = array();
        $total_crm = 0;

        if (!empty($crm_total_leads)) {
            foreach ($crm_total_leads as $key => $value) {
                $ind_crm = array();
                $ind_crm["name"] = $value->lead_stage;
                $ind_crm["value"] = $value->totalresult;
                $total_crm += $value->totalresult;
                $crm_data[] = $ind_crm;
            }
        }
        $crm_data[] = [
            'name' => 'Total',
            'value' => $total_crm
        ];

        //dd($crm_data);

        return view('home.dashboard_1', compact('p_data', 'sdate', 'edate', 'dates', 'missed', 'answered', 'new_ans', 'new_miss', 'crm_data'));
    }

    public function callSummary()
    {
        $date = date("Y-m-d");
        $result = DB::table('cdr')
            ->select('accountgroup.id', 'accountgroup.name', 'cdr.cdrid as calls', 'cdr.firstleg as total', 'cdr.secondleg as outgoing')
            ->where('cdr.datetime', 'like', $date . '%')
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->orderBy('id', 'desc')->paginate(10);
        //dd($summary);
        return view('home.call_summary', compact('result'));
    }

    public function dashboardNote()
    {
        $result = DB::table('dashbord_annuounce')
            ->orderBy('id', 'desc')->paginate(10);
        //dd($result);
        return view('home.dashboard_note', compact('result'));
    }

    public function addAnnouncement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'msg' => 'required',
        ], [
            'msg.required' => 'Announcement field is required'
        ]);

        if ($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $msg = [
                'user' => Auth::user()->username,
                'msg' => $request->get('msg'),
                'date' => NOW()
            ];

            DB::table('dashbord_annuounce')->insert($msg);
            $data['success'] = 'Announcement added successfully.';
        }
        return $data;
    }

    public function deleteAnnouncement($id)
    {
        DB::table('dashbord_annuounce')->where('id', $id)->delete();
        toastr()->success('Announcement delete successfully.');
        return redirect()->route('dashboardNote');
    }

    public function cdrTags()
    {
        return view('home.cdrtags', ['result' => CdrTag::getReport(), 'tags' => CdrTag::getTag()]);
    }

    public function tagStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag' => 'required'
        ]);

        if ($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $tag = [
                'tag' => $request->get('tag'),
                'groupid' => Auth::user()->groupid,
            ];
            DB::table('cdr_tags')->insert($tag);
            $data['success'] = 'Cdr Tag added successfully.';
        }
        return $data;
    }

    public function deleteRecord($id, $name)
    {
        DB::table($name)->where('id', $id)->delete();
        toastr()->success('Record delete successfully.');
        return redirect()->route('cdrTags');
    }

    public function ToDoTaskAdd(Request $request)
    {
        //print_r($request->all());exit;

        $now = date("Y-m-d H:i:s");

        $add_todo = new ToDoTask([
            'user_id' => Auth::user()->id,
            'title' => $request->get('task'),
            'date' => $request->get('datetime'),
            'inserted_date' => $now,
        ]);

        //dd($add_todo);exit;
        $add_todo->save();
        toastr()->success('ToDo added successfully.');
        return Redirect::back();
    }

    public function ToDoTaskEdit(Request $request)
    {
        //print_r($request->all());
        $edit = DB::table('todotask')->where('id', $request->get('myid'))->get();
        echo json_encode($edit);
    }

    public function ToDoTaskUpdate(Request $request)
    {
        print_r($request->all());
        $id = $request->get('todo_id');
        //print_r($request->all());
        $edit_todo = ToDoTask::find($id);

        $edit_todo->title = $request->task;
        $edit_todo->date = $request->datetime;

        //print_r($edit_todo);exit;
        $edit_todo->save();
        toastr()->success('ToDo Updated successfully.');
        return Redirect::back();
    }

    public function destroy($id)
    {
        DB::table('todotask')->where('id', $id)->delete();
        $message = toastr()->success('Deleted successfully.');
        return Redirect::back();
    }

    public function UpdateStatus($id)
    {
        $check_data = DB::table('todotask')->where('id', $id)->get();
        //print_r($check_data[0]->status);exit;
        if ($check_data[0]->status == 'Hold') {
            $status = 'Pending';
        } else {
            $status = 'Hold';
        }
        $edit_todo = ToDoTask::find($id);
        $edit_todo->status = $status;
        $edit_todo->save();
        toastr()->success('Status Updated successfully.');
        return Redirect::back();
    }

    public function UpdateToDo($id)
    {
        $edit_todo = ToDoTask::find($id);
        $edit_todo->status = 'Done';
        $edit_todo->save();
        toastr()->success('Status Updated successfully.');
        return Redirect::back();
    }

    public function smsApi()
    {
        $result = DB::table('sms_api_gateways')
            ->orderBy('id', 'desc')->paginate(10);
        return view('home.sms_api', compact('result'));
    }

    public function addSmsApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'url' => 'required',
            'mobile_param_name' => 'required',
            'user_param_name' => 'required',
            'password_parm_name' => 'required',
            'sender_param_name' => 'required',
            'message_para_name' => 'required',
        ]);

        if ($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $sms_data = [
                'name' => $request->get('name'),
                'url' => $request->get('url'),
                'mobile_param_name' => $request->get('mobile_param_name'),
                'user_param_name' => $request->get('user_param_name'),
                'password_parm_name' => $request->get('password_parm_name'),
                'sender_param_name' => $request->get('sender_param_name'),
                'message_para_name' => $request->get('message_para_name')
            ];

            if (!empty($request->get('id'))) {
                DB::table('sms_api_gateways')->where('id', $request->get('id'))->update($sms_data);
                $data['success'] = 'Sms api update successfully.';
            } else {
                DB::table('sms_api_gateways')->insert(
                    $sms_data
                );
                $data['success'] = 'Sms api added successfully.';
            }
        }
        return $data;
    }

    public function deleteItem($id, $table)
    {
        $field = ($table == 'cdr') ? 'cdrid' : 'id';
        $res = DB::table($table)->where($field, $id)->delete();
        if ($table == 'ivr_menu') {
            DB::table('ast_ivrmenu_language')->where('ivr_menu_id', $id)->delete();
        } elseif ($table == 'operatoraccount' && $id != null) {
            DB::table('operator_dept_assgin')->where('operatorid', $id)->delete();
            DB::table('account')->where('operator_id', $id)->delete();
        } elseif ($table == 'resellergroup' && $id != null) {
            DB::table('account')->where('resellerid', $id)->delete();
        }
        return $res;
    }

    public function pushApi()
    {
        $result = DB::table('pushapi')->select('pushapi.*', 'accountgroup.name')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'pushapi.groupid')->orderBy('id', 'desc')
            ->paginate(10);
        return view('home.push_api', compact('result'));
    }

    public function getData($table, $id)
    {
        return $result = DB::table($table)->where('id', $id)->get();
    }

    public function addPushApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'apitype' => 'required',
            'api' => 'required',
            'postvalues' => 'required',
        ]);

        if ($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $push_api_data = [
                'groupid' => $request->get('groupid'),
                'type' => $request->get('type'),
                'apitype' => $request->get('apitype'),
                'api' => $request->get('api'),
                'postvalues' => $request->get('postvalues'),
                'token_bearer' => $request->get('token_bearer')
            ];

            if (!empty($request->get('id'))) {
                DB::table('pushapi')->where('id', $request->get('id'))->update($push_api_data);
                $data['success'] = 'Push api update successfully.';
            } else {
                DB::table('pushapi')->insert(
                    $push_api_data
                );
                $data['success'] = 'Push api added successfully.';
            }
        }
        return $data;
    }

    public function NotificationToDo()
    {
        $now = date("Y-m-d H:i") . ":00";

        $datetime = DB::table('todotask')->where('status', '!=', 'Done')->where('user_id', Auth::user()->id)->get();
        //echo $now;
        $new_array = array();
        foreach ($datetime as $key => $value) {
            //print_r($value->date);
            $str_to = strtotime($value->date);
            $now_str = strtotime($now);
            if ($str_to == $now_str) {
                $new_array[] = array('title' => $value->title, 'date' => $value->date, 're_value' => '1');
            } else {
                $new_array[] = array('title' => '', 'date' => '', 're_value' => '0');
            }
        }
        echo json_encode($new_array);
    }
}
