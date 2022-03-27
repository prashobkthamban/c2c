<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
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
    public function index(Request $request)
    {

        $this->graphTodaysCalls();
        if (Auth::user()->usertype == 'admin') {
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
        } else {
            $startDate = ($request->get('from_date') ? date('Y-m-d', strtotime($request->get('from_date'))) : date('Y-m-d', strtotime("-1 month"))) . ' 00:00:00';
            $endDate = ($request->get('to_date') ? date('Y-m-d', strtotime($request->get('to_date'))) : date('Y-m-d')) . ' 23:59:59';

            $incomingCallData = $operatorCallData = $departmentData = $customerCallData = [];
            $todaysData = $this->getTodaysData();
            if (Auth::user()->usertype == 'groupadmin') {
                $incomingCallData = $this->getIncomingCallData($startDate, $endDate);
                $operatorCallData = $this->getOperatorCallData($startDate, $endDate);
                $departmentData = $this->getDepartmentData($startDate, $endDate);
            } else if (Auth::user()->usertype == 'reseller') {
                $customerCallData = $this->getCustomerCallData($startDate, $endDate);
            } else if (Auth::user()->usertype == 'operator') {
                $departmentData = $this->getDepartmentData($startDate, $endDate);
            }

            $startDate = date('d-M-Y', strtotime($startDate));
            $endDate = date('d-M-Y', strtotime($endDate));

            return view('home.dashboard_new', compact('todaysData', 'startDate', 'endDate', 'incomingCallData', 'operatorCallData', 'departmentData', 'customerCallData'));
        }
    }

    private function getTodaysData() {
        $startDate = date('Y-m-d') . ' 00:00:00';
        $endDate = date('Y-m-d') . ' 23:59:59';
        if (Auth::user()->usertype == 'groupadmin') {
            $groupAdminIds = [Auth::user()->groupid];
        } else if (Auth::user()->usertype == 'reseller') {
            $groupAdminIds =  DB::table('accountgroup')->where('resellerid', Auth::user()->resellerid)->pluck('id');
        }
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $activeOperators = DB::table('operatoraccount')
                ->whereIn('operatoraccount.groupid', $groupAdminIds)
                ->where('operatoraccount.oper_status', 'Online')
                ->count();
        }
        $qry = DB::table('cur_channel_used');
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $qry->whereIn('groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $qry->where('operatorid', Auth::user()->operator_id);
        }
        $liveCalls = $qry->count();
        $query = DB::table('cdr')
            ->select(
                DB::raw('count(IF(status = "ANSWERED", 1, NULL)) as answeredCalls'),
                DB::raw('count(IF(status = "MISSED", 1, NULL)) as missedCalls'),
                DB::raw('count(IF(status = "AFTEROFFICE", 1, NULL)) as afterOfficeCalls'),
                DB::raw('count(*) as totalCalls')
            );
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $query->whereIn('cdr.groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $query->where('cdr.operatorid', Auth::user()->operator_id);
        }
        $cdrData = $query->where('cdr.deptname', '!=', '')
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->get();

        $totalCalls = (count($cdrData) > 0) ? $cdrData[0]->totalCalls : '0';
        $answeredCalls = (count($cdrData) > 0) ? $cdrData[0]->answeredCalls : '0';
        $missedCalls = (count($cdrData) > 0) ? $cdrData[0]->missedCalls : '0';
        $afterOfficeCalls = (count($cdrData) > 0) ? $cdrData[0]->afterOfficeCalls : '0';
        $data = [
            ["title" => "Today's Total Call", "count" => $totalCalls],
            ["title" => "Live Call", "count" => $liveCalls],
            ["title" => "Answered Call", "count" => $answeredCalls],
            ["title" => "Missed Call", "count" => $missedCalls],
            ["title" => "After Office/Voicemail", "count" => $afterOfficeCalls]
        ];
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            array_unshift($data, ["title" => "Active Operators", "count" => $activeOperators]);
        }

        return $data;
    }

    private function getIncomingCallData($startDate, $endDate) {
        $cdrData = DB::table('cdr')
            ->select(
                DB::raw('count(IF(status = "ANSWERED", 1, NULL)) as answeredCalls'),
                DB::raw('count(IF(status = "MISSED", 1, NULL)) as missedCalls'),
                DB::raw('count(IF(status = "AFTEROFFICE", 1, NULL)) as afterOfficeCalls'),
                DB::raw('count(*) as totalCalls')
            )
            ->where('cdr.deptname', '!=', '')
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->get();

        $data = [];
        if (count($cdrData) > 0) {
            $data = [
                ["label" => "Answered", "count" => $cdrData[0]->answeredCalls, "label_class" => "badge-success"],
                ["label" => "Missed", "count" => $cdrData[0]->missedCalls, "label_class" => "badge-danger"],
                ["label" => "After Office", "count" => $cdrData[0]->afterOfficeCalls, "label_class" => "badge-warning"],
                ["label" => "Total", "count" => $cdrData[0]->totalCalls, "label_class" => "badge-info"]
            ];
        }

        return $data;
    }

    private function getOperatorCallData($startDate, $endDate) {
        $data = DB::table('cdr')
            ->leftJoin('operatoraccount', 'operatoraccount.id', 'cdr.operatorid')
            ->select(
                'operatoraccount.opername',
                DB::raw('count(IF(status = "ANSWERED", 1, NULL)) as answeredCalls'),
                DB::raw('count(IF(status = "MISSED", 1, NULL)) as missedCalls'),
                DB::raw('count(*) as totalCalls')
            )
            ->where('cdr.deptname', '!=', '')
            ->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->groupBy('cdr.operatorid')
            ->get();

        return $data;
    }

    private function getDepartmentData($startDate, $endDate) {

        if (Auth::user()->usertype == 'groupadmin') {
            $groupAdminIds = [Auth::user()->groupid];
        } else if (Auth::user()->usertype == 'reseller') {
            $groupAdminIds =  DB::table('accountgroup')->where('resellerid', Auth::user()->resellerid)->pluck('id');
        } else if (Auth::user()->usertype == 'operator') {
            $departmentIds =  DB::table('operator_dept_assgin')->where('operatorid', Auth::user()->operator_id)->pluck('departmentid');
        }
        $query = DB::table('cdr')
            ->select('deptname', DB::raw('count(IF(status = "ANSWERED", 1, NULL)) as answeredCalls'), DB::raw('count(IF(status = "MISSED", 1, NULL)) as missedCalls'))
            ->where('cdr.deptname', '!=', '');
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $query->whereIn('cdr.groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $query->where('cdr.operatorid', Auth::user()->operator_id);
        }
        $cdrData = $query->where('cdr.groupid', Auth::user()->groupid)
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->groupBy('deptname')
            ->get();
        $qry = DB::table('operatordepartment')
            ->select('dept_name');
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $qry->whereIn('groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $qry->whereIn('id', $departmentIds);
        }
        $departments = $qry->get();

        $data = [];
        if (count($cdrData) > 0) {
            foreach ($cdrData as $cdr) {
                $data[$cdr->deptname] = [
                    "answeredCalls" => $cdr->answeredCalls,
                    "missedCalls" => $cdr->missedCalls
                ];
            }
        }
        foreach ($departments as $key => $dept) {
            if (!isset($data[$dept->dept_name])) {
                $data[$dept->dept_name] = [
                    "answeredCalls" => "0",
                    "missedCalls" => "0"
                ];
            }
        }

        return $data;
    }

    /**
     * Get call data of all the groups under the logged in reseller
     */
    private function getCustomerCallData($startDate, $endDate) {
        $data = DB::table('cdr')
            ->leftJoin('accountgroup', 'accountgroup.id', 'cdr.groupid')
            ->select(
                'accountgroup.name as group_name',
                DB::raw('count(IF(cdr.status = "ANSWERED", 1, NULL)) as answeredCalls'),
                DB::raw('count(IF(cdr.status = "MISSED", 1, NULL)) as missedCalls'),
                DB::raw('count(IF(cdr.status = "AFTEROFFICE", 1, NULL)) as afterOfficeCalls'),
                DB::raw('count(*) as totalCalls')
            )
            ->where('cdr.deptname', '!=', '')
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->where('accountgroup.resellerid', Auth::user()->resellerid)
            ->groupBy('cdr.groupid')
            ->get();

        return $data;
    }

    public function chartTodaysCalls() {
        $startDate = date('Y-m-d') . ' 00:00:00';
        // $startDate = date('Y-m-d', strtotime("-24 month")) . ' 00:00:00';
        $endDate = date('Y-m-d') . ' 23:59:59';
        if (Auth::user()->usertype == 'groupadmin') {
            $groupAdminIds = [Auth::user()->groupid];
        } else if (Auth::user()->usertype == 'reseller') {
            $groupAdminIds =  DB::table('accountgroup')->where('resellerid', Auth::user()->resellerid)->pluck('id');
        }
        $qry = DB::table('cur_channel_used');
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $qry->whereIn('groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $qry->where('operatorid', Auth::user()->operator_id);
        }
        $liveCalls = $qry->count();
        $query = DB::table('cdr')
            ->select(
                DB::raw('count(IF(status = "ANSWERED", 1, NULL)) as answeredCalls'),
                DB::raw('count(IF(status = "MISSED", 1, NULL)) as missedCalls'),
                DB::raw('count(IF(status = "AFTEROFFICE", 1, NULL)) as afterOfficeCalls')
            );
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $query->whereIn('cdr.groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $query->where('cdr.operatorid', Auth::user()->operator_id);
        }
        $cdrData = $query->where('cdr.deptname', '!=', '')
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->get();

        $answeredCalls = (count($cdrData) > 0) ? $cdrData[0]->answeredCalls : '0';
        $missedCalls = (count($cdrData) > 0) ? $cdrData[0]->missedCalls : '0';
        $afterOfficeCalls = (count($cdrData) > 0) ? $cdrData[0]->afterOfficeCalls : '0';

        $data = [
            "datasets" => [[
                "data" => [$answeredCalls, $missedCalls, $afterOfficeCalls, $liveCalls],
                "backgroundColor" => [
                    "#4198d7",
                    "#e55759",
                    "#d8b655",
                    "#46d39a"
                ],
                "borderColor" => [
                    "#4198d7",
                    "#e55759",
                    "#d8b655",
                    "#46d39a"
                ],
            ]],
            // These labels appear in the legend and in the tooltips when hovering different arcs
            "labels" => [
                'Answered',
                'Missed',
                'After Office/Voicemail',
                'Live',
            ]
        ];
        return new JsonResponse(['data' => $data]);
    }

    public function graphTodaysCalls() {
        $startDate = date('Y-m-d') . ' 00:00:00';
        // $startDate = date('Y-m-d', strtotime("-24 month")) . ' 00:00:00';
        $endDate = date('Y-m-d') . ' 23:59:59';
        if (Auth::user()->usertype == 'groupadmin') {
            $groupAdminIds = [Auth::user()->groupid];
        } else if (Auth::user()->usertype == 'reseller') {
            $groupAdminIds =  DB::table('accountgroup')->where('resellerid', Auth::user()->resellerid)->pluck('id');
        }
        $query = DB::table('cdr')
            ->select(
                DB::raw('HOUR(datetime) hr'),
                DB::raw('count(IF(status = "ANSWERED", 1, NULL)) as answeredCalls'),
                DB::raw('count(IF(status = "MISSED", 1, NULL)) as missedCalls'),
                DB::raw('count(IF(status = "AFTEROFFICE", 1, NULL)) as afterOfficeCalls'),
                DB::raw('count(*) as totalCalls')
            );
        if (in_array(Auth::user()->usertype, ["groupadmin","reseller"])) {
            $query->whereIn('cdr.groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $query->where('cdr.operatorid', Auth::user()->operator_id);
        }
        $cdrData = $query->where('cdr.deptname', '!=', '')
            ->whereDate('cdr.datetime', '>=', $startDate)
            ->whereDate('cdr.datetime', '<=', $endDate)
            ->groupBy('hr')
            ->get();
        $data = $this->formGraphData(12);
        if(!empty($cdrData)) {
            foreach($cdrData as $cdr) {
                $hr = $cdr->hr;
                if (isset($data[$hr])) {
                    $data[$hr]["totalCalls"] = $cdr->totalCalls;
                    $data[$hr]["answeredCalls"] = $cdr->answeredCalls;
                    $data[$hr]["missedCalls"] = $cdr->missedCalls;
                    $data[$hr]["afterOfficeCalls"] = $cdr->afterOfficeCalls;
                }
            }
        }

        $marketingOverviewData = [
            "labels" => array_column($data, "time"),
            "datasets" => [
                [
                    "label" => "Total",
                    "data" => array_column($data, "totalCalls"),
                    "backgroundColor" => "#7a61ba",
                    "borderColor" => [
                        '#7a61ba'
                    ],
                    "borderWidth" => 0,
                    "fill" => true, // 3: no fill
                ],[
                    "label" => "Answered",
                    "data" => array_column($data, "answeredCalls"),
                    "backgroundColor" => "#4198d7",
                    "borderColor" => [
                        '#4198d7'
                    ],
                    "borderWidth" => 0,
                    "fill" => true, // 3: no fill
                ],[
                    "label" => "Missed",
                    "data" => array_column($data, "missedCalls"),
                    "backgroundColor" => "#e55759",
                    "borderColor" => [
                        '#e55759'
                    ],
                    "borderWidth" => 0,
                    "fill" => true, // 3: no fill
                ],[
                    "label" => "After Office/Voicemail",
                    "data" => array_column($data, "afterOfficeCalls"),
                    "backgroundColor" => "#d8b655",
                    "borderColor" => [
                        '#d8b655'
                    ],
                    "borderWidth" => 0,
                    "fill" => true, // 3: no fill
                ]
            ]
        ];

        return new JsonResponse(['marketingOverviewData' => $marketingOverviewData]);
    }

    public function formTimeString($hr) {
        $hrA = $hr > 11 ? ":00 PM" : ":00 AM";
        $hr = $hr > 12 ? $hr - 12 : $hr;
        $hr = $hr == 0 ? "12" : $hr;
        return $hr . $hrA;
    }

    private function formGraphData($noOfHours) {
        $hr = date('H');
        $data = [];
        while($noOfHours > 0 && $hr >= 0) {
            $nextHr = $hr + 1;
            $timeString = $this->formTimeString($hr) . "-" . $this->formTimeString($nextHr);
            $data[$hr] =  [
                "time" => $timeString,
                "totalCalls" => 0,
                "answeredCalls" => 0,
                "missedCalls" => 0,
                "afterOfficeCalls" => 0,
            ];
            $hr--;
            $noOfHours--;
        }
        return $data;
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
        return view('home.cdrtags', ['result' => CdrTag::getReport(), 'tags' => CdrTag::getTag(Auth::user()->groupid)]);
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
        $query = DB::table('pushapi')->select('pushapi.*', 'accountgroup.name')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'pushapi.groupid');
        if (Auth::user()->usertype == 'groupadmin') {
            $query->where('groupid', Auth::user()->groupid);
        }
        $result = $query->orderBy('id', 'desc')
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
                'token_bearer' => $request->get('token_bearer') ? $request->get('token_bearer') : ""
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
