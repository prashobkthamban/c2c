<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CdrTag;
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
         if(!Auth::check()){
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
        if(Auth::user()->usertype == 'admin') {
            $nousers = DB::table('accountgroup')->count(); 
            $inusers = DB::table('accountgroup')
            ->whereDate('enddate', '<', date("Y-m-d"))
            ->where('status', 'Inactive')
            ->count(); 
        } 

        $g_callstoday = $o_callstoday = $callstoday = $g_activecalls = $activecalls = 0;

        $activeoperator = DB::table('operatoraccount')
            ->where('operatoraccount.groupid', Auth::user()->groupid)
            ->where('operatoraccount.oper_status', 'Online')
            ->count(); 
        if(Auth::user()->usertype == 'groupadmin') {
            $g_callstoday = DB::table('cdr')
            ->where('groupid', Auth::user()->groupid)
            ->whereDate('datetime', '=', date("Y-m-d"))
            ->count();
        } else if(Auth::user()->usertype == 'operator') {
            $o_callstoday = DB::table('cdr')
            ->where('operatorid', Auth::user()->id)
            ->whereDate('datetime', '=', date("Y-m-d"))
            ->count();
        } else if(Auth::user()->usertype == 'admin'){
            $callstoday = DB::table('cdr')
            ->whereDate('datetime', '=', date("Y-m-d"))
            ->count();
        }
         
        if(Auth::user()->usertype == 'groupadmin') {
            $g_activecalls = DB::table('cur_channel_used')
            ->where('groupid', Auth::user()->groupid)
            ->count();
        } else if(Auth::user()->usertype == 'admin'){
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
        $onemonthdate= date("m/d/Y", strtotime("-1 month"));
        $sdate = isset($_REQUEST['dfrom']) ? date('m/d/Y',strtotime($_REQUEST['dfrom'])) : $onemonthdate;
        $edate = isset($_REQUEST['dto']) ? date('m/d/Y',strtotime($_REQUEST['dto'])) : date('m/d/Y');
        //dd($sdate);
        $qedate = date("Y-m-d",strtotime($edate));
        $qsdate = date("Y-m-d",strtotime($sdate));
    
        $piechart = DB::table('cdr')
            ->select('cdr.status', DB::raw('count(*) as totalresult'))
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'cdr.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'cdr.operatorid', '=', 'operatoraccount.id')
            ->whereIn('cdr.status', ['answrd','DIALING'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('cdr.status')
            ->get();
        $p_data = array();
        if( ! empty($piechart) ){
            foreach ($piechart as $key => $value) {
                $ind = array();
                $ind["name"] = $value->status;
                $ind["value"] = $value->totalresult;
                $p_data[] = $ind;
            }
        }

        $barchart = DB::table('cdr')
            ->select('cdr.status', DB::raw('date(cdr.datetime) as mydate'), DB::raw('day(cdr.datetime) as Day'), DB::raw('hour(cdr.datetime) as Hour'), DB::raw('count(cdr.cdrid) as Count'))
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'cdr.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'cdr.operatorid', '=', 'operatoraccount.id')
            ->whereIn('cdr.status', ['answrd','DIALING','MISSED'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('cdr.status')
            ->get();
        
        $b_data = array();
        if( ! empty($barchart) ){
            foreach ($barchart as $pkey => $bvalue) {
                $ind = array();
                $b_data[$bvalue->status][] = $bvalue;
            }

            $series = array();
            foreach ($b_data as $pd => $bbvalue) {
                $ind = array();
                $ind["label"]   = $pd;
                $ia = array();
                //usort($bbvalue, 'sortByOrder');
               
                foreach ($bbvalue as $bbb) {
                    $ia[] = array($bbb->Hour,$bbb->Count);
                }
               //ksort($ia);
                $ind["data"]    = $ia;
                $cl = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                $ind["color"]   = $cl;
                $ind["bars"]    = array('fillColor' => $cl);
                $series[] = $ind;
            }
        } 

        $level_1 = DB::table('lead_stages')->where('levels', '=', '1')->get()->count();
        $level_2 = DB::table('lead_stages')->where('levels', '=', '2')->get()->count();
        $level_3 = DB::table('lead_stages')->where('levels', '=', '3')->get()->count();
        $level_4 = DB::table('lead_stages')->where('levels', '=', '4')->get()->count();
        $level_5 = DB::table('lead_stages')->where('levels', '=', '5')->get()->count();
        $level_6 = DB::table('lead_stages')->where('levels', '=', '6')->get()->count();
        $level_7 = DB::table('lead_stages')->where('levels', '=', '7')->get()->count();

        $todo_lists = DB::table('todotask')
                    ->select('*')
                    ->where('status','!=','Done')
                    ->where('user_id','=',Auth::user()->id)
                    ->orderBy('id', 'desc')
                    ->paginate(10);
                    
            $users_list = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid', Auth::user()->groupid)
                        ->get();

            $lead_count = array();
            
            foreach ($users_list as $key => $value) {
                
                $lead_count[$value->id] = DB::table('cdrreport_lead')
                        ->select('cdrreport_lead.operatorid')
                        ->where('operatorid','=',$value->id)
                        ->get()->count();
            }

        $remainders = DB::table('lead_reminders')
                    ->where('user_id','=',Auth::user()->id)
                    ->get();

        if (Auth::user()->usertype == 'groupadmin') {

            $users_list = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid', Auth::user()->groupid)
                        ->get();

            //echo "<pre>";
            //print_r($users_list);

            $lead_count = array();
            foreach ($users_list as $key => $value) {
                
                $lead_count[$value->id] = DB::table('cdrreport_lead')
                        ->select('cdrreport_lead.operatorid')
                        ->where('operatorid','=',$value->id)
                        ->get()->count();

                $operator_lead_stage[$value->id] = DB::table('cdrreport_lead')
                        ->select(DB::raw('COUNT(lead_stage) as lead_count'),'lead_stage','operatoraccount.opername')
                        ->where('operatorid','=',$value->id)
                        ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                        ->groupBy('cdrreport_lead.lead_stage','operatoraccount.opername')
                        ->get();

                $predict_cost[$value->id] = DB::table('cdrreport_lead')
                                ->select(DB::raw('SUM(total_amount) as pre_cost'),'operatoraccount.opername')
                                ->where('operatorid','=',$value->id)
                                ->whereNotIn('lead_stage', ['converted'])
                                ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                                ->get();

                $proposal[$value->id] = DB::table('proposal')
                                ->select(DB::raw('SUM(grand_total) as proposal_total'),'operatoraccount.opername')
                                ->where('operator_id','=',$value->id)
                                ->leftJoin('operatoraccount','operatoraccount.id','=','proposal.operator_id')
                                ->get();

                $invoice[$value->id] = DB::table('invoice')
                                ->select(DB::raw('SUM(grand_total) as invoice_total'),'operatoraccount.opername')
                                ->where('operator_id','=',$value->id)
                                ->leftJoin('operatoraccount','operatoraccount.id','=','invoice.operator_id')
                                ->get();

                foreach ($predict_cost as $key => $pc) {
                    if ($pc[0]->opername == '') {
                        $pc[0]->pre_cost = 0;
                        $pc[0]->opername = $value->opername;
                    }
                }

                foreach ($proposal as $key => $pro) {
                    if ($pro[0]->opername == '') {
                        $pro[0]->pre_cost = 0;
                        $pro[0]->opername = $value->opername;
                    }
                }

                foreach ($invoice as $key => $pro) {
                    if ($pro[0]->opername == '') {
                        $pro[0]->pre_cost = 0;
                        $pro[0]->opername = $value->opername;
                    }
                }

            }
            //print_r($invoice);exit;

            $remainders = DB::table('lead_reminders')
                        ->where('user_id','=',Auth::user()->id)
                        ->get();

        }
        else if (Auth::user()->usertype == '') {
            $level_1 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '1')->get()->count();
            $level_2 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '2')->get()->count();
            $level_3 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '3')->get()->count();
            $level_4 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '4')->get()->count();
            $level_5 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '5')->get()->count();
            $level_6_7 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '>=', '6')->get()->count();
            
            $users_list = '';

            $remainders = DB::table('lead_reminders')
                        ->where('user_id','=',Auth::user()->id)
                        ->get();

            $lead_count = '';
        }
        $nousers = '';
        $inusers = '';
        return view('home.dashboard', compact('activeoperator', 'g_callstoday', 'o_callstoday', 'callstoday', 'g_activecalls', 'activecalls', 'ivranswer', 'ivrmissed', 'p_data', 'series', 'sdate', 'edate', 'nousers', 'inusers','level_1','level_2','level_3','level_4','level_5','level_6','level_7','todo_lists','users_list','remainders','lead_count','operator_lead_stage','predict_cost','proposal','invoice'));
    }

    public function callSummary() {
        $date = date("Y-m-d");
        $result = DB::table('cdr')
            ->select('accountgroup.id', 'accountgroup.name', 'cdr.cdrid as calls', 'cdr.firstleg as total', 'cdr.secondleg as outgoing' )
            ->where('cdr.datetime', 'like', $date . '%')
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->orderBy('id', 'desc')->paginate(10);
            //dd($summary);
        return view('home.call_summary', compact('result'));
    }

    public function dashboardNote() {
        $result = DB::table('dashbord_annuounce')
            ->orderBy('id', 'desc')->paginate(10);
            //dd($result);
        return view('home.dashboard_note', compact('result'));
    }

    public function addAnnouncement(Request $request) 
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'msg' => 'required',
        ], [
            'msg.required' => 'Announcement field is required'
        ]);     

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $msg = ['user' => Auth::user()->username,
                     'msg'=> $request->get('msg'),
                     'date' => NOW()
                    ];

            DB::table('dashbord_annuounce')->insert($msg);
            $data['success'] = 'Announcement added successfully.';
        } 
         return $data;
    }

    public function deleteAnnouncement($id) {
        DB::table('dashbord_annuounce')->where('id',$id)->delete();
        toastr()->success('Announcement delete successfully.');
        return redirect()->route('dashboardNote');
    }

    public function cdrTags() {
        return view('home.cdrtags', ['result' => CdrTag::getReport()]);
    }

    public function deleteRecord($id, $name) {
        DB::table($name)->where('id',$id)->delete();
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
        $edit_todo->date = $request->date;

        //print_r($edit_todo);exit;
        $edit_todo->save();
        toastr()->success('ToDo Updated successfully.');
        return Redirect::back();
    }

    public function destroy($id)
    {
        DB::table('todotask')->where('id',$id)->delete();
        $message = toastr()->success('Deleted successfully.');
        return Redirect::back();
    }

    public function UpdateStatus($id)
    {
        $check_data = DB::table('todotask')->where('id',$id)->get();
        //print_r($check_data[0]->status);exit;
        if ($check_data[0]->status == 'Hold') {
            $status = 'Pending';
        }
        else{
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
}
