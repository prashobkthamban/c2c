<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IUserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct(IUserService $userService)
    {
        $this->middleware('auth');
        if (!Auth::check()) {
            return redirect('login');
        }
        $this->userService = $userService;
    }

    public function test()
    {
        $users = $this->userService->getAllUsers();
        print_r($users);
        die;
    }

    public function billing(Request $request)
    {

        $requests = $request->all();
        $groupId = $request->get('customer');
        $query = DB::table('billing')
            ->leftJoin('accountgroup', 'billing.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'billing.resellerid', '=', 'resellergroup.id')
            ->leftJoin('dids', 'billing.groupid', '=', 'dids.assignedto');

        if (Auth::user()->usertype == 'reseller') {
            $query->where('billing.resellerid', Auth::user()->resellerid)->orWhere('billing.resellerid', '0');
        } elseif (Auth::user()->usertype != 'admin') {
            $query->where('billing.groupid', Auth::user()->groupid);
        }

        if (isset($groupId)) {
            $query->where('billing.groupid', $groupId);
        }

        $query->select('billing.*', 'accountgroup.name', 'resellergroup.resellername', 'dids.rdins', 'dids.rdnid')->orderBy('id', 'desc');
        $result = $query->get();

        if (Auth::user()->usertype == 'operator') {
            $lead_allowed = DB::table('operatoraccount')->where('opername', Auth::user()->username)->select('lead_access')->first();
            $total_access_leads = $lead_allowed->lead_access;
        } else {
            $total_access_leads = (Auth::user()->load('accountdetails')->accountdetails != null) ? Auth::user()->load('accountdetails')->accountdetails->leads_access : '';
        }

        $apiKey = urlencode('624AD-63599');

        // Prepare data for POST request
        $data = array('apikey' => $apiKey);

        // Send the POST request with cURL
        $ch = curl_init('http://smsdnd.voiceetc.co.in/sms-panel/api/http/index.php?username=demosms&apikey=624AD-63599&apirequest=CreditCheck&route=RouteName&format=JSON');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return view('service.billing_list', compact('result', 'total_access_leads', 'response', 'requests'));
    }

    public function billDetails($groupid)
    {
        return DB::table('billing_log')->where('billing_log.groupid', $groupid)->get();
    }

    public function getBilling($id)
    {
        return DB::table('billing')
            ->leftJoin('accountgroup', 'billing.groupid', '=', 'accountgroup.id')
            ->where('billing.id', $id)->select('billing.*', 'accountgroup.name')->get();
    }

    public function editBilling(Request $request)
    {
        $rules = [
            //'main_balance' => 'required',
        ];
        //dd($request->all());
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $today = date("Y-m-d");
            if ($request->get('billingmode') == 'prepaid') {
                if ($request->get('creditlimit') != NULL) {
                    if ($request->get('main_bal') == NULL) {
                        $prepaidbalance = $request->get('creditlimit');
                        $reset = 'Reset Done';
                    } else {
                        $prepaidbalance = $request->get('main_bal') + $request->get('creditlimit');
                        $reset = '';
                    }
                    $limit = 0;
                    $type = 'Recharge';
                    $comments = 'Previous Balance=' . $request->get('main_bal') . ' - Click to call balance ' . $request->get('c2c_balance') . ' ---- ' . $reset;
                }
            } else {
                $limit = $request->get('creditlimit');
                if ($request->get('creditlimit') == NULL) {
                    $prepaidbalance = '';
                    $reset = 'Reset Done';
                } else {
                    $prepaidbalance = "";
                    $reset = '';
                }
                $type = 'Credit Limit';
                $comments = 'Previous Balance=' . $request->get('main_bal') . ' - Click to call balance ' . $request->get('c2c_balance') . ' -- bill date=' . $request->get('billdate') . ' ---- ' . $reset;
            }

            $billing_log = [
                'groupid' => $request->get('groupid'),
                'amount' => $request->get('creditlimit'),
                'bill_cycle' => $today,
                'datetime' => NOW(),
                'type' => $type,
                'username' => Auth::user()->username,
                'comments' => $comments
            ];

            $billing = [
                'main_balance' => $prepaidbalance,
                'call_pulse_setup' => $request->get('call_pulse_setup'),
                'c2c_pulse_setup' => $request->get('c2c_pulse_setup'),
                'c2c_balance' => $request->get('c2c_balance'),
                'billingmode' => $request->get('billingmode'),
                'billdate' => $request->get('billdate'),
                'creditlimit' => $limit,
            ];

            // if($request->get('billingmode')=='postpaid')
            // {
            //     unset($billing['main_balance']);
            // }
            //dd($request->all());
            DB::table('billing')
                ->where('id', $request->get('id'))
                ->update($billing);
            DB::table('billing_log')->insert($billing_log);
            $data['success'] = 'Billing updated successfully.';
        }
        return $data;
    }

    public function accessLogs(Request $request)
    {
        return view('service.access_logs');
    }

    public function accessLogsAjaxLoad(Request $request) {
        $searchText = $request->get('search')['value'];

        $sortOrder = $request->get('order')['0'];
        $columnArray = [
            '0' => ['ast_login_log.username'],
            '1' => ['ast_login_log.password'],
            '2' => ['ast_login_log.usertype'],
            '3' => ['accountgroup.name'],
            '4' => ['ast_login_log.ipaddress'],
            '5' => ['ast_login_log.status'],
            '6' => ['ast_login_log.login_time']
        ];
        $sortOrderArray = [];
        foreach ($columnArray[$sortOrder['column']] as $field) {
            $sortOrderArray[$field] = $sortOrder['dir'];
        }
        $userTypeArray = [
            'admin' => 'super admin',
            'reseller' => 'coperate admin',
            'groupadmin' => 'group admin',
            'operator' => 'operator'
        ];

        $limit = $request->get('length');
        $skip = $request->get('start');
        $draw = $request->get('draw');
        $data = DB::table('ast_login_log')
                ->select('ast_login_log.*', 'accountgroup.name')
                ->leftJoin('accountgroup', 'ast_login_log.groupid', '=', 'accountgroup.id');
        $recordsTotal = $data->count();
        if(!empty($searchText)) {
            $revTypeArray = array_flip($userTypeArray);
            $searchText = strtolower(trim($searchText));
            $searchText = isset($revTypeArray[$searchText]) ? $revTypeArray[$searchText] : $searchText;
            $data->where(DB::raw('lower(accountgroup.name)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(ast_login_log.username)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(ast_login_log.password)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(ast_login_log.userType)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(ast_login_log.ipaddress)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(ast_login_log.status)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(ast_login_log.login_time)'), 'like', '%' . $searchText . '%')
            ;
        }
        $recordsFiltered = $data->count();

        if (count($sortOrderArray) > 0) {
            foreach ($sortOrderArray as $field => $order) {
                $data->orderBy($field, $order);
            }
        }

        if ($limit > 0) {
            $data->skip($skip)
                ->take($limit);
        }
        $results = $data->get();

        $dataArray = [];
        if(count($results) > 0) {
            foreach($results as $result) {
                $dataArray[] = [
                    'id' => $result->id,
                    'userName' => $result->username,
                    'password' => $result->password,
                    'userType' => isset($userTypeArray[$result->usertype]) ? ucwords($userTypeArray[$result->usertype]) : "",
                    'customerName' => ($result->usertype == 'reseller') ? '[Coperate Admin]' : (($result->usertype =='admin') ? '[Super Admin]' : (!empty($result->usertype) ? $result->name : "")),
                    'ipAddress' => $result->ipaddress,
                    'status' => $result->status,
                    'loginTime' => $result->login_time
                ];
            }
        }

        $result = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $dataArray
        ];
        
        return new JsonResponse($result);
    }

    public function liveCalls()
    {
        return view('service.live_calls');
    }

    public function liveCallDataAjaxLoad(Request $request) {
        $searchText = $request->get('search')['value'];
        $sortOrder = $request->get('order')['0'];
        $columnArray = [
            '0' => ['accountgroup.name'],
            '1' => ['cur_channel_used.callerid'],
            '2' => ['cur_channel_used.time'],
            '3' => ['cur_channel_used.DID'],
            '4' => ['operatordepartment.dept_name'],
            '5' => ['operatoraccount.opername'],
            '6' => [''],
            '7' => ['cur_channel_used.call_status'],
            '8' => ['cur_channel_used.dial_statergy'],
            '9' => ['cur_channel_used.status_change_time']
        ];
        $sortOrderArray = [];
        foreach ($columnArray[$sortOrder['column']] as $field) {
            $sortOrderArray[$field] = $sortOrder['dir'];
        }

        $limit = $request->get('length');
        $skip = $request->get('start');
        $draw = $request->get('draw');
        $result = $this->getLiveCallData($searchText, $sortOrderArray, $limit, $skip, $draw);
        return new JsonResponse($result);
    }

    public function liveCallConsoleAjaxLoad(Request $request) {
        $searchText = $request->get('search')['value'];
        $sortOrder = $request->get('order')['0'];
        $columnArray = [
            '0' => ['accountgroup.name'],
            '1' => ['cur_channel_used.callerid'],
            '2' => ['operatordepartment.dept_name'],
            '3' => ['operatoraccount.opername'],
            '4' => ['cur_channel_used.status_change_time'],
            '5' => ['cur_channel_used.call_status']
        ];
        $sortOrderArray = [];
        foreach ($columnArray[$sortOrder['column']] as $field) {
            $sortOrderArray[$field] = $sortOrder['dir'];
        }

        $limit = $request->get('length');
        $skip = $request->get('start');
        $draw = $request->get('draw');
        $result = $this->getLiveCallData($searchText, $sortOrderArray, $limit, $skip, $draw);
        
        return new JsonResponse($result);
    }

    public function getLiveCallData($searchText, $sortOrderArray, $limit, $skip, $draw) {
        $data = DB::table('cur_channel_used')
                ->leftJoin('accountgroup', 'cur_channel_used.groupid', '=', 'accountgroup.id')
                ->leftJoin('operatoraccount', 'cur_channel_used.operatorid', '=', 'operatoraccount.id')
                ->leftJoin('operatordepartment', 'cur_channel_used.departmentid', '=', 'operatordepartment.id')
                ->where('cur_channel_used.calltype', 'ivr');
        if (Auth::user()->usertype == 'groupadmin') {
            $groupAdminIds = [Auth::user()->groupid];
        } else if (Auth::user()->usertype == 'reseller') {
            $groupAdminIds =  DB::table('accountgroup')->where('resellerid', Auth::user()->resellerid)->pluck('id');
        }
        if (in_array(Auth::user()->usertype, ["groupadmin", "reseller"])) {
            $data->whereIn('cur_channel_used.groupid', $groupAdminIds);
        } else if (Auth::user()->usertype == 'operator') {
            $data->where('cur_channel_used.operatorid', Auth::user()->operator_id);
        }
        $data->select('cur_channel_used.*', 'accountgroup.name', 'operatoraccount.opername', 'operatordepartment.dept_name');
        $recordsTotal = $data->count();
        if(!empty($searchText)) {
            $searchText = strtolower(trim($searchText));
            $data->where(DB::raw('lower(accountgroup.name)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(cur_channel_used.time)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(cur_channel_used.DID)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(operatordepartment.dept_name)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(operatoraccount.opername)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(cur_channel_used.call_status)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(cur_channel_used.dial_statergy)'), 'like', '%' . $searchText . '%')
            ;
        }
        $recordsFiltered = $data->count();

        if (count($sortOrderArray) > 0) {
            foreach ($sortOrderArray as $field => $order) {
                $data->orderBy($field, $order);
            }
        }

        if ($limit > 0) {
            $data->skip($skip)
                ->take($limit);
        }
        $results = $data->get();

        $dataArray = [];
        if(count($results) > 0) {
            foreach($results as $result) {
                $contactName = getConatctName($result->callerid);
                $firstName = count($contactName) == null ? $result->callerid :  $contactName[0]->fname;
                $pushApi = DB::table('pushapi')
                            ->whereIn('apitype', ['AgentPopup', 'webhook'])
                            ->where('groupid', $result->groupid)
                            ->first();
                $webHookLink = '';
                if (!empty($pushApi) && in_array($pushApi->apitype, ['AgentPopup', 'webhook'])) {
                    $webHookLink = str_replace('{CALLERID}', substr($result->callerid, -10), $pushApi->api);
                }
                $date1 = date_create();
                $date2 = date_create($result->status_change_time);
                $interval = date_diff($date1,$date2);
                $dataArray[] = [
                    'id' => $result->id,
                    'customerName' => $result->name,
                    'firstName' => $firstName,
                    'callTime' => $result->time,
                    'didNumber' => $result->DID,
                    'departmentName' => $result->dept_name,
                    'operatorName' => $result->opername,
                    'webHookLink' => $webHookLink,
                    'callStatus' => $result->call_status,
                    'dialStatergy' => $result->dial_statergy,
                    'duration' => $interval->format('%H:%i:%s')
                ];
            }
        }

        return [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $dataArray
        ];
    }

    public function gateway()
    {
        $result = DB::table('prigateway')->where('delete_status', '0')->orderBy('id', 'desc')->paginate(10);
        return view('service.gateway', compact('result'));
    }

    public function addGateway(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Gprovider' => 'required',
            'Gchannel' => 'required',
        ]);
        if ($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $gateway_data = [
                'Gprovider' => $request->get('Gprovider'),
                'Gchannel' => $request->get('Gchannel'),
                'billingdate' => date('Y-m-d', strtotime($request->get('billingdate'))),
                'used_units' => $request->get('used_units'),
                'pluse_rate' => $request->get('pluse_rate'),
                'dial_prefix' => $request->get('dial_prefix'),
                'sip_header' => $request->get('sip_header'),
            ];

            if (empty($request->get('id'))) {
                DB::table('prigateway')->insert($gateway_data);
                $data['success'] = 'Pri Gateway added successfully.';
            } else {
                DB::table('prigateway')
                    ->where('id', $request->get('id'))
                    ->update($gateway_data);
                $data['success'] = 'Pri Gateway updated successfully.';
            }
        }
        return $data;
    }

    public function getPriGateway($id)
    {
        $data =  DB::table('prigateway')->where('prigateway.id', $id)->get();
        $data[0]->billingdate = date('d-m-Y', strtotime($data[0]->billingdate));
        return $data;
    }

    public function prilog($id)
    {
        return $result = DB::table('pri_gateway_log')
            ->where('pri_id', $id)
            ->get();
    }

    public function listenToLiveCall(Request $request)
    {
        $groupId = Auth::user()->groupid;
        $dids = DB::table('dids')->where('assignedto', $groupId)->first();
        $didnumber = $dids->did;
        $gatewayid = $dids->outgoing_gatewayid;
        $didnumber = $dids->set_did_no;

        $prigateway = DB::table('prigateway')->where('id', $gatewayid)->first();
        $span = $prigateway->Gchannel;
        $dialPrefix = $prigateway->dial_prefix;

        $phoneNumber = $request->get('number');
        $option = $request->get('option');

        $curChannelUsed = DB::table('cur_channel_used')->where('id', $request->get('cur_channel_used_id'))->first();
        $operatorChannel = $curChannelUsed->operator_channel;
        $callerId = $curChannelUsed->callerid;

        $phoneNumber = $dialPrefix . substr($phoneNumber, -10);
        $cdrData = [
            'did_no' => $didnumber,
            'groupid' => $groupId,
            'resellerid' => Auth::user()->resellerid,
            'operatorid' => Auth::user()->operator_id,
            'datetime' => NOW(),
            'deptname' => 'LISTEN',
            'status' => 'Listen',
            'number' => $phoneNumber,
        ];
        $cdrId = DB::table('cdr')->insertGetId($cdrData);

        $phone1 = $phoneNumber . "-" . $cdrId . "-" . $didnumber . "-" . $groupId;

        $manager = DB::table('asterisk_manager')->where('id', 1)->first();
        $strHost = $manager->ip;
        $strUser = $manager->username;
        $strSecret = $manager->password;

        $errno = "";
        $errstr = "";
        $timeout = "30";

        $socket = fsockopen("$strHost", "5038", $errno, $errstr, $timeout);
        fputs($socket, "Action: Login\r\n");
        fputs($socket, "UserName: $strUser\r\n");
        fputs($socket, "Secret: $strSecret\r\n\r\n");

        fputs($socket, "Action: Originate\r\n");
        fputs($socket, "Variable: span=$span\r\n");
        fputs($socket, "Variable: inchannel=$operatorChannel\r\n");

        fputs($socket, "Channel: local/" . $phone1 . "@ast_ivrc2clisen\r\n");
        fputs($socket, "Context: ast_ivrlisten\r\n");
        fputs($socket, "Exten: 22\r\n");
        fputs($socket, "Variable: option=$option\r\n");

        fputs($socket, "Callerid: $callerId\r\n");
        fputs($socket, "Priority: 1\r\n");
        fputs($socket, "Timeout: 30000\r\n\r\n");

        fputs($socket, "Action: Logoff\r\n\r\n");
        $wrets = '';
        while (!feof($socket)) {
            $wrets .= fread($socket, 4096);
        }
        fclose($socket);
        $data['success'] = 'Configuration added successfully.';
        return $data;
    }
}
