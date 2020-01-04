<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CdrTag;

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
            ->whereIn('cdr.status', ['answrd','DIALING'])
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
        //dd($series);
        //dd($piechart);
        return view('home.dashboard', compact('activeoperator', 'g_callstoday', 'o_callstoday', 'callstoday', 'g_activecalls', 'activecalls', 'ivranswer', 'ivrmissed', 'p_data', 'series', 'sdate', 'edate', 'nousers', 'inusers'));
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
}
