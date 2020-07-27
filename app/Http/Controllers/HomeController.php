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
        $onemonthdate= date("m/d/Y", strtotime("-1 month"));
        $sdate = isset($_REQUEST['dfrom']) ? date('m/d/Y',strtotime($_REQUEST['dfrom'])) : $onemonthdate;
        $edate = isset($_REQUEST['dto']) ? date('m/d/Y',strtotime($_REQUEST['dto'])) : date('m/d/Y');
        $qedate = date("Y-m-d",strtotime($edate));
        $qsdate = date("Y-m-d",strtotime($sdate));

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
            
            $incoming_calls = CdrReport::select(DB::raw('count(*) as count, status'))->with('leadCdr')
            ->whereIn('status', ['ANSWERED', 'MISSED', 'AFTEROFFICE'])
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('status')
            ->get();
           
            $operator_leads = CdrReport::select(DB::raw('count(cdrreport_lead.cdrreport_id) as total'), 'cdrreport_lead.lead_stage')
            ->where('cdr.groupid', Auth::user()->groupid)
            ->where('cdr.operatorid', '!=', '0')
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->join('cdrreport_lead', 'cdrreport_lead.cdrreport_id', '=', 'cdr.cdrid')
            ->whereIn('cdrreport_lead.lead_stage', ['New', 'Demo', 'Under review', 'Converted', 'Contacted'])
            ->groupBy('cdrreport_lead.lead_stage')
            ->get();

            $operator_calls = CdrReport::select(DB::raw('count(cdrreport_lead.cdrreport_id) as lead_count'), DB::raw('count(cdrreport_lead.lead_stage) as lead_status'), DB::raw('count(*) as user_count, cdr.status'), DB::raw('count(*) as total_count, cdr.operatorid'), 'account.username', 'cdr.groupid', 'cdr.operatorid','cdr.status', 'cdrreport_lead.lead_stage')
            ->where('cdr.groupid', Auth::user()->groupid)
            ->where('cdr.operatorid', '!=', '0')
            ->join('account', 'account.operator_id', '=', 'cdr.operatorid')
            ->leftjoin('cdrreport_lead', 'cdrreport_lead.cdrreport_id', '=', 'cdr.cdrid')
            ->whereIn('cdr.status', ['ANSWERED', 'MISSED', 'AFTEROFFICE', 'LIVECALL'])
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('cdr.operatorid', 'cdr.status')
            ->get();

           // dd($operator_calls);
            $opcallList = [];
            $lead_count = 0;
            foreach($operator_calls as $listOne) {
                $opcallList[$listOne->username][$listOne->status] = $listOne->user_count;
                $lead_count = $lead_count + $listOne->lead_count;
                $opcallList[$listOne->username]['lead_count'] = $lead_count;
                $opcallList[$listOne->username]['completed'] = 0;
            }
            
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
            foreach($departments as $key => $dept) {
                if(count($insight_ivr) > 0) {
                    for($i = 0; $i < count($insight_ivr); $i++) {
                        if($insight_ivr[$i]->dept_name == $dept->dept_name) {
                            $deptNames[] = $dept->dept_name;
                            $insightData[$key]['deptname'] = $dept->dept_name;
                            $insightData[$key]['count'] = $insight_ivr[$i]->count;
                        } else {
                            if ( !in_array($dept->dept_name, $deptNames) ) { 
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

            $group_admin = '';

            $users_list = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid', Auth::user()->groupid)
                        ->get();

            $lead_count = $operator_lead_stage = $predict_cost  = $proposal = $invoice = array();
            foreach ($users_list as $key => $value) {
                
                $lead_count[$value->id] = DB::table('cdrreport_lead')
                        ->select('cdrreport_lead.operatorid')
                        ->where('operatorid','=',$value->id)
                        ->get()->count();

                $operator_lead_stage[$value->opername] = DB::table('cdrreport_lead')
                //,DB::raw('group_concat(cdrreport_lead.lead_stage) as stage')
                        ->select(DB::raw('COUNT(lead_stage) as lead_count'),'lead_stage','operatoraccount.opername')
                        ->where('operatorid','=',$value->id)
                        ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                        ->groupBy('cdrreport_lead.lead_stage','operatoraccount.opername')
                        //->groupBy('cdrreport_lead.lead_stage')
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
                        $pro[0]->proposal_total = 0;
                        $pro[0]->opername = $value->opername;
                    }
                }

                foreach ($invoice as $key => $pro) {
                    if ($pro[0]->opername == '') {
                        $pro[0]->invoice_total = 0;
                        $pro[0]->opername = $value->opername;
                    }
                }

            }

            $remainders = DB::table('lead_reminders')
                ->where('user_id','=',Auth::user()->id)
                ->get();

            $level_1 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '1')->get()->count();
            $level_2 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '2')->get()->count();
            $level_3 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '3')->get()->count();
            $level_4 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '4')->get()->count();
            $level_5 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '5')->get()->count();
            $level_6_7 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '>=', '6')->get()->count();

        }

        else if (Auth::user()->usertype == 'reseller') {
            
            $group_admin = DB::table('accountgroup')->where('resellerid','=',Auth::user()->resellerid)->get();

            $groupid = DB::table('resellergroup')->where('id',Auth::user()->resellerid)->first();

            
            $de = json_decode($groupid->associated_groups);

            foreach ($de as $key => $de_gpid) {
                $users_list[] = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid',$de_gpid)
                        ->get();
            }
            
            $lead_count = $operator_lead_stage = $predict_cost  = $proposal = $invoice = array();

            foreach ($users_list as $key => $value) {
                foreach ($value as $key => $new_value) {
                    
                
                    $lead_count[$new_value->id] = DB::table('cdrreport_lead')
                            ->select('cdrreport_lead.operatorid')
                            ->where('operatorid','=',$new_value->id)
                            ->get()->count();

                    $operator_lead_stage[$new_value->opername] = DB::table('cdrreport_lead')
                    //,DB::raw('group_concat(cdrreport_lead.lead_stage) as stage')
                            ->select(DB::raw('COUNT(lead_stage) as lead_count'),'lead_stage','operatoraccount.opername')
                            ->where('operatorid','=',$new_value->id)
                            ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                            ->groupBy('cdrreport_lead.lead_stage','operatoraccount.opername')
                            //->groupBy('cdrreport_lead.lead_stage')
                            ->get();

                    $predict_cost[$new_value->id] = DB::table('cdrreport_lead')
                                    ->select(DB::raw('SUM(total_amount) as pre_cost'),'operatoraccount.opername')
                                    ->where('operatorid','=',$new_value->id)
                                    ->whereNotIn('lead_stage', ['converted'])
                                    ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                                    ->get();

                    $proposal[$new_value->id] = DB::table('proposal')
                                    ->select(DB::raw('SUM(grand_total) as proposal_total'),'operatoraccount.opername')
                                    ->where('operator_id','=',$new_value->id)
                                    ->leftJoin('operatoraccount','operatoraccount.id','=','proposal.operator_id')
                                    ->get();

                    $invoice[$new_value->id] = DB::table('invoice')
                                    ->select(DB::raw('SUM(grand_total) as invoice_total'),'operatoraccount.opername')
                                    ->where('operator_id','=',$new_value->id)
                                    ->leftJoin('operatoraccount','operatoraccount.id','=','invoice.operator_id')
                                    ->get();

                    foreach ($predict_cost as $key => $pc) {
                        if ($pc[0]->opername == '') {
                            $pc[0]->pre_cost = 0;
                            $pc[0]->opername = $new_value->opername;
                        }
                    }

                    foreach ($proposal as $key => $pro) {
                        if ($pro[0]->opername == '') {
                            $pro[0]->proposal_total = 0;
                            $pro[0]->opername = $new_value->opername;
                        }
                    }

                    foreach ($invoice as $key => $pro) {
                        if ($pro[0]->opername == '') {
                            $pro[0]->invoice_total = 0;
                            $pro[0]->opername = $new_value->opername;
                        }
                    }
                }

            }

            $remainders = DB::table('lead_reminders')
                ->where('user_id','=',Auth::user()->id)
                ->get();

            /*echo "<pre>";
            print_r($users_list);exit();*/

        }
        else if (Auth::user()->usertype == 'operator') 
        {
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

/*        echo "<pre>";
        print_r(Auth::user());exit;*/

        $nousers = '';
        $inusers = '';
        $announcements = DB::table('dashbord_annuounce')->orderBy('id', 'desc')->get();
        return view('home.dashboard', compact('incoming_calls', 'operator_leads', 'opcallList', 'insight_ivr','insightData', 'announcements', 'activeoperator', 'g_callstoday', 'o_callstoday', 'callstoday', 'g_activecalls', 'activecalls', 'ivranswer', 'ivrmissed', 'sdate', 'edate', 'nousers', 'inusers','level_1','level_2','level_3','level_4','level_5','level_6','level_7','todo_lists','users_list','remainders','lead_count','operator_lead_stage','predict_cost','proposal','invoice','group_admin'));
    }

    public function dashboard() {
        $onemonthdate= date("m/d/Y", strtotime("-2 month"));
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
            ->whereIn('cdr.status', ['ANSWERED','MISSED','AFTEROFFICE'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('cdr.status')
            ->get();
        $p_data = array();
        $total = 0;
        
        if( ! empty($piechart) ){
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
            ->whereIn('cdr.status', ['ANSWERED','MISSED'])
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
        if( !empty($barchart) ){
            $i = 0;
            foreach ($barchart as $pkey => $bvalue) {
                $b_data[] = $bvalue->newdate;
                if(!empty($date) && $date == $bvalue->newdate) {
                    $nd["answered"] = $bar_data[$i-1]['answered'];
                    $nd["missed"] = ($bvalue->status == 'MISSED') ? $bvalue->Count : 0;
                    $nd["date"] = $bvalue->newdate;
                    $bar_data[$i-1] = $nd;
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
            foreach($bar_data as $val) {
                array_push($dates, $val['date']);
                array_push($missed, $val['missed']);
                array_push($answered, $val['answered']);
            }
        }    
        //dd(array_unique($b_data));
        $barstacked = DB::table('cdr')
            ->select('cdr.status', DB::raw('HOUR(cdr.datetime) as time') ,DB::raw('count(*) as totalresult'))
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'cdr.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'cdr.operatorid', '=', 'operatoraccount.id')
            ->whereIn('cdr.status', ['ANSWERED','MISSED'])
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $qsdate)
            ->whereDate('cdr.datetime', '<=', $qedate)
            ->groupBy('time')
            ->groupBy('cdr.status')
            ->get();

        //dd($barstacked);

        $answered_bar = array();
        $missed_bar = array();
        foreach ($barstacked as $key => $value) 
        {
            if ($value->status == 'ANSWERED') 
            {
                $answered_bar[$value->time] = $value->totalresult;
            }
            else
            {
                $missed_bar[$value->time] = $value->totalresult;
            }
        }   

        for ($i=1; $i <= 24; $i++) 
        { 
            if (!array_key_exists($i,$answered_bar)) 
            {
                $answered_bar[$i] = 0;
            }
            if (!array_key_exists($i,$missed_bar)) 
            {
                $missed_bar[$i] = 0;
            }
        }
        ksort($answered_bar);
        ksort($missed_bar);
        
        $new_ans = array();
        $new_miss = array();

        foreach($answered_bar as $x => $x_value)
        {
            $new_ans[$x] = $x_value;
        }

        foreach ($missed_bar as $m => $m_value) 
        {
            $new_miss[$m] = $m_value;
        }
        
        $crm_total_leads = DB::table('cdrreport_lead')
            ->select('lead_stage',DB::raw('count(*) as totalresult'))
            ->where('group_id', Auth::user()->groupid)
            ->whereDate('inserted_date', '>=', $qsdate)
            ->whereDate('inserted_date', '<=', $qedate)
            ->groupBy('lead_stage')
            ->get();

        //dd($crm_total_leads);

        $crm_data = array();
        $total_crm = 0;
        
        if( ! empty($crm_total_leads) ){
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
        
        return view('home.dashboard_1', compact('p_data', 'series', 'sdate', 'edate', 'dates', 'missed', 'answered','new_ans','new_miss','crm_data'));
    }

    public function CRMData(Request $request) {

        //print_r($request->all());exit;

        $users_list = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid', $request->groupadmin_id)
                        ->get();

        $lead_count = array();
        $operator_lead_stage = array();
        $predict_cost = array();
        $proposal = array();
        $invoice = array();

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

            if ($request->date_from != '' && $request->date_to != '') {

                $predict_cost[$value->id] = DB::table('cdrreport_lead')
                    ->select(DB::raw('SUM(total_amount) as pre_cost'),'operatoraccount.opername')
                    ->where('operatorid','=',$value->id)
                    ->where('cdrreport_lead.inserted_date','>=',$request->date_from)
                    ->where('cdrreport_lead.inserted_date','<=',$request->date_to)
                    ->whereNotIn('lead_stage', ['converted'])
                    ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                    ->get();

                $proposal[$value->id] = DB::table('proposal')
                    ->select(DB::raw('SUM(grand_total) as proposal_total'),'operatoraccount.opername')
                    ->where('operator_id','=',$value->id)
                    ->where('proposal.inserted_date','>=',$request->date_from)
                    ->where('proposal.inserted_date','<=',$request->date_to)
                    ->leftJoin('operatoraccount','operatoraccount.id','=','proposal.operator_id')
                    ->get();

                $invoice[$value->id] = DB::table('invoice')
                    ->select(DB::raw('SUM(grand_total) as invoice_total'),'operatoraccount.opername')
                    ->where('operator_id','=',$value->id)
                    ->where('invoice.inserted_date','>=',$request->date_from)
                    ->where('invoice.inserted_date','<=',$request->date_to)
                    ->leftJoin('operatoraccount','operatoraccount.id','=','invoice.operator_id')
                    ->get();
            }
            else {

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
            }

        
            foreach ($predict_cost as $key => $pc) {
                if ($pc[0]->opername == '') {
                    $pc[0]->pre_cost = 0;
                    $pc[0]->opername = $value->opername;
                }
            }

            foreach ($proposal as $key => $pro) {
                if ($pro[0]->opername == '') {
                    $pro[0]->proposal_total = 0;
                    $pro[0]->opername = $value->opername;
                }
            }

            foreach ($invoice as $key => $pro) {
                if ($pro[0]->opername == '') {
                    $pro[0]->invoice_total = 0;
                    $pro[0]->opername = $value->opername;
                }
            }

            }


        echo json_encode(array('users_list' => $users_list, 'lead_count' => $lead_count,'operator_lead_stage' => $operator_lead_stage,'predict_cost' => $predict_cost,'proposal' => $proposal,'invoice' => $invoice));
        
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
        return view('home.cdrtags', ['result' => CdrTag::getReport(), 'tags'=>CdrTag::getTag()]);
    }

    public function tagStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'tag' => 'required'
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $tag = [
                'tag'=> $request->get('tag'),
                'groupid'=> Auth::user()->groupid,
            ];
            DB::table('cdr_tags')->insert($tag); 
            $data['success'] = 'Cdr Tag added successfully.';
        } 
       return $data; 
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

    public function smsApi() {
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

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $sms_data = [
                'name' => $request->get('name'),
                'url'=> $request->get('url'),
                'mobile_param_name'=> $request->get('mobile_param_name'),
                'user_param_name'=> $request->get('user_param_name'),
                'password_parm_name'=> $request->get('password_parm_name'),
                'sender_param_name'=> $request->get('sender_param_name'),
                'message_para_name'=> $request->get('message_para_name')
            ];

            if(!empty($request->get('id'))) {
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

    public function deleteItem($id, $table) {
        $field = ($table == 'cdr') ? 'cdrid' : 'id';
        $res = DB::table($table)->where($field,$id)->delete();
        if($table == 'ivr_menu') {
            DB::table('ast_ivrmenu_language')->where('ivr_menu_id',$id)->delete();
        } elseif($table == 'operatoraccount' && $id != null) {
            DB::table('operator_dept_assgin')->where('operatorid',$id)->delete();
            DB::table('account')->where('operator_id',$id)->delete();
        }
        return $res;
    }

    public function pushApi() {
        $result = DB::table('pushapi')->select('pushapi.*', 'accountgroup.name')
        ->leftJoin('accountgroup', 'accountgroup.id', '=', 'pushapi.groupid')->orderBy('id', 'desc')
        ->paginate(10);
        return view('home.push_api', compact('result'));
    }

    public function getData($table, $id) {
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

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $push_api_data = [
                'groupid'=> $request->get('groupid'),
                'type'=> $request->get('type'),
                'apitype'=> $request->get('apitype'),
                'api'=> $request->get('api'),
                'postvalues'=> $request->get('postvalues')
            ];

            if(!empty($request->get('id'))) {
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
        $now = date("Y-m-d H:i").":00";

        $datetime = DB::table('todotask')->where('status','!=','Done')->where('user_id',Auth::user()->id)->get();
        //echo $now;
        $new_array = array();
        foreach ($datetime as $key => $value) 
        {
            //print_r($value->date);
            $str_to = strtotime($value->date); 
            $now_str = strtotime($now);
            if ($str_to == $now_str) 
            {
                $new_array[] = array('title' => $value->title, 'date' => $value->date, 're_value' => '1');
            }
            else
            {
                $new_array[] = array('title' => '', 'date' => '', 're_value' => '0');
            }
        }
        echo json_encode($new_array);
        
    }

    public function emailConfig() {
        $result = DB::table('email_config')
        ->select('email_config.*', 'accountgroup.name')
        ->leftJoin('accountgroup', 'email_config.groupid', '=', 'accountgroup.id')
        ->orderBy('email_config.id', 'desc')
        ->paginate(10);
        //dd($result);
        return view('home.email_config', compact('result'));
    }

    public function addConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'smtp_host' => 'required',
            'smtp_user' => 'required',
            'smtp_pass' => 'required'
        ]);  

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $config = [
                'groupid' => $request->get('groupid'),
                'smtp_host'=> $request->get('smtp_host'),
                'smtp_user'=> $request->get('smtp_user'),
                'smtp_pass'=> $request->get('smtp_pass')
            ];

            if(!empty($request->get('id'))) {
                DB::table('email_config')->where('id', $request->get('id'))->update($config);
                $data['success'] = 'Email config update successfully.';
            } else {
                    DB::table('email_config')->insert(
                        $config
                    );
                $data['success'] = 'Email config added successfully.';   
            }
                     
        } 
        return $data;
    }

}
