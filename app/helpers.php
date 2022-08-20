<?php

function getResellers() {
	$resellers = DB::table('resellergroup')->pluck('resellername', 'id');
    return $resellers;
}

function getAccountgroups($usertype=null, $reseller= null) {
    //dd($reseller);
	if(Auth::user()->usertype == 'reseller') {
        $cust =  DB::table('accountgroup')->where('resellerid', Auth::user()->resellerid)->pluck('name', 'id');
    } else {
        $cust =  DB::table('accountgroup')->pluck('name', 'id');
    }
    return $cust;
}

function getCustomerResellerId($groupid) {
    $accountGroup =  DB::table('accountgroup')->where('id', $groupid)->first();
    if(!empty($accountGroup)) {
        return ['resellerid' => $accountGroup->resellerid];
    }
    return ['resellerid' => '0'];
}

function getOperator() {
    $opAcc =  DB::table('operatoraccount')->where('groupid', Auth::user()->groupid)->pluck('opername','id');
    return $opAcc;
}

function getOperatorList() {
    $opAcc =  DB::table('operatoraccount')->select('account.usertype', 'account.id', 'operatoraccount.opername', 'operatoraccount.groupid')->where('operatoraccount.groupid', Auth::user()->groupid)->leftJoin('account', 'operatoraccount.id', '=', 'account.operator_id')->get();
    //dd($opAcc);
    return $opAcc;
}

function getAdminList() {
    $adminAcc =  DB::table('account')->select('account.username', 'account.id')
    ->where('account.usertype', 'admin')
    ->get();
    return $adminAcc;
}

function getGroupList() {
    if(Auth::user()->usertype == 'operator') {
        $gpAcc =  DB::table('accountgroup')->select('accountgroup.name', 'account.id')
        ->where('account.usertype', 'groupadmin')
        ->where('accountgroup.id', Auth::user()->groupid)
        ->leftJoin('account', 'accountgroup.id', '=', 'account.groupid')->get();
    } else {
        $gpAcc =  DB::table('accountgroup')->select('accountgroup.name', 'account.id')
        ->leftJoin('account', 'accountgroup.id', '=', 'account.groupid')
        ->where('account.usertype', 'groupadmin')
        ->get();
    }
    
    return $gpAcc;
}

function shiftList() {
    return $opr_shift = DB::table('operator_shifts')->where('groupid', Auth::user()->groupid)->pluck('shift_name','id');
}

function getAccountgroupdetails($groupid = null) {
    if($groupid != null) {
       $acc_details = DB::table('ivr_menu')->where('groupid', $groupid)->pluck('ivr_level_name', 'id'); 
    } else {
        $acc_details = DB::table('ivr_menu')->pluck('ivr_level_name', 'id'); 
    } 
	
    return $acc_details;
}

function getDidList($groupid = null) {
    $data = [];
    if($groupid != null) {
        $did_list = App\Models\Dids::with(['extradid'])->where('assignedto', $groupid)->get();
        foreach($did_list as $key => $listItem) {
            $data[$key]['id'] = $listItem->id;
            $data[$key]['did'] = $listItem->did;
            foreach($listItem->extradid as $key => $extraItem) {
                $data[$key+1]['id'] = $extraItem->id;
                $data[$key+1]['did'] = $extraItem->did_no;
            }
        }
    } else {
        $data = DB::table('dids')->pluck('did', 'id'); 
    }
    return $data;
}

function getDepartmentList($groupid) {
    $dept_list = DB::table('operatordepartment')->where('groupid', $groupid)->where('complaint', 'Yes')->orderBy('adddate', 'desc')->pluck('dept_name', 'id'); 
    if(Auth::user()->usertype == 'admin') {
        $dept_list->prepend('Miss Call', '0');
    }
    
    return $dept_list;
}

function unreadNotification() {
    $not_list1 = DB::table('notifications')->where('send_to_id', Auth::user()->id)->where('send_to_id', Auth::user()->id)->where('grp_readstatus', '0')->get();
    $not_list2 = DB::table('notifications')->where('send_from_id', Auth::user()->id)->where('adm_read_status', '0')->get();
    $data['not_count'] = $not_list1->count() + $not_list2->count();
    $data['not_list'] = array_merge($not_list1->toArray(),$not_list2->toArray());
    return $data;
}
function getExtensions($usertype=null, $groupid= null) {
    if($usertype != 'reseller') {
        $ext =  DB::table('pbx_chan_sip_extensions')->where('groupid', $groupid)->select('pbx_chan_sip_extensions.extension')->get();
    } else {
        $ext =  DB::table('pbx_chan_sip_extensions')->select('pbx_chan_sip_extensions.extension')->get();
    }
    return $ext;
}

function getRinggroups($usertype=null, $groupid= null) {
    if($usertype != 'reseller') {
        $ring =  DB::table('pbx_ringgroups')->where('groupid', $groupid)->select('ringgroup', 'description')->get();
    } else {
        $ring =  DB::table('pbx_ringgroups')->select('pbx_ringgroups.extension')->get();
    }
    return $ring;
}

function getConatctName($callerid) {
    $contact = DB::table('contacts')->where('groupid', Auth::user()->groupid)->where('phone', $callerid)->select('contacts.fname')->get(); 
    return $contact;
}

function smsConfig($params) {
    $apiid=Auth::user()->load('accountdetails')->accountdetails['sms_api_gateway_id'];
    $smssuport=Auth::user()->load('accountdetails')->accountdetails['sms_support'];
    $sms_api_user=Auth::user()->load('accountdetails')->accountdetails['sms_api_user'];
    $sms_api_pass=Auth::user()->load('accountdetails')->accountdetails['sms_api_pass'];
    $sms_api_senderid=Auth::user()->load('accountdetails')->accountdetails['sms_api_senderid'];
    if($smssuport !== 'No')
    {
        $resultapi = DB::table('sms_api_gateways')->where('id',$apiid)->first();
        if (empty($resultapi))
        {
            $data['error'] = 'SMS APi details NOt found.';
            $data['status'] = 0;
        } else
        { 
            //url 2 mobile_param_name 3 user_param_name 4 password_parm_name 5 sender_param_name 6
            $url =$resultapi->url;
            $mobile_param=$resultapi->mobile_param_name;
            $user_param=$resultapi->user_param_name;
            $password_param=$resultapi->password_parm_name;
            $sender_param=$resultapi->sender_param_name;
            $message_param=$resultapi->message_para_name;
            $fullapi=$url."".$user_param."=".$sms_api_user."&".$password_param."=".$sms_api_pass."&".$sender_param."=".$sms_api_senderid."&".$message_param."=".$params['message'];

            $url=$fullapi."&response=Y&".$mobile_param."=".$params['number'];
            //dd($url);
            $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                $resp1= $curl_scraped_page;
            //dd(Auth::user());
            $sms = ['number' => $params['number'],
                    'groupid' => Auth::user()->groupid,
                    'operatorid' => Auth::user()->operator_id,
                    'message' => $params['message'],
                    'response' => $resp1,
                    ];
                
            DB::table('bulksms')->insert($sms);
            $res = (array) json_decode($resp1);
            //dd($res['status']);
            if($res['status'] == 'error') {
                $data['error'] = $res['message'];
                $data['status'] = 0;
            } else {
                $data['success'] = 'Sms sent successfully';
                $data['status'] = 1;
            }
            
        }
    } else {
        $data['error'] = 'SMS support Not enabled.';
        $data['status'] = 0;
    }
    return $data;
}

function callConfig($params) {
    //global $locate;
    $wrets = '';
    $did = DB::table('dids')->where('assignedto',Auth::user()->groupid)->select('did', 'c2cpri', 'c2ccallerid')->first();
    $didnumber=$did->did;
    $gatewayid=$did->c2cpri;
    $didnumber=$did->c2ccallerid;
    $prigateway = DB::table('prigateway')->where('id',$gatewayid)->select('Gchannel','dial_prefix')->first();
    $span = $prigateway->Gchannel;
    $dialPrefix = $prigateway->dial_prefix;
    $groupid = Auth::user()->groupid;
    $dept = DB::table('operatordepartment')->where('groupid',$groupid)->where('C2C',1)->select('dept_name')->first();
    $dptname=$dept->dept_name;
    if($dptname == NULL)
    {
        $dptname='C2C';
    }

    if(Auth::user()->usertype != 'admin')
    {
        $billing = DB::table('billing')->where('groupid',$groupid)->select('c2c_balance')->first();
        if($billing->c2c_balance > 1) {
            $time=floor($billing->c2c_balance / 2);
            $time=$time*60;
        } else {
            $credit='0';
        }
    }

    $params['number']=preg_replace('/[^a-zA-Z0-9]/', '',$params['number']);  
    $params['phone']=preg_replace('/[^a-zA-Z0-9]/', '',$params['phone']);  

    if($params['callf'] == 'cust')
    {
        $phone2 = $dialPrefix.substr($params['phone'],-10);// leg1 operator
        $phone1 = $dialPrefix.substr($params['number'],-10);//leg2 customer number
    }
    else
    {
        $phone1 = $dialPrefix.substr($params['phone'],-10);//leg1 operator
        $phone2 = $dialPrefix.substr($params['number'],-10);//leg2 customer number
    }

    $rand_no = substr(str_shuffle("0123456789"), 0, 4);
    $rand_no = time().'.'.$rand_no;
    $cdr = ['number' => $params['number'],
            'did_no' => $didnumber,
            'groupid' => Auth::user()->groupid,
            'resellerid' => Auth::user()->resellerid,
            'operatorid' => Auth::user()->operator_id,
            'deptname' => 'C2C',
            'status' => 'DIALING',
            'userid' => Auth::user()->id,
            'uniqueid' => $rand_no
            ];               
    $cdrid = DB::table('cdr')->insertGetId($cdr);
 
    $phone1=$phone1."-".$cdrid."-".$didnumber."-".$groupid;
    $phone2=$phone2."-".$cdrid."-".$didnumber."-".$groupid."-".$time;
    $manager = DB::table('asterisk_manager')->where('id', 1)->first();
    //dd($phone1 .  ' '. $phone2);
    $strHost = $manager->ip;
    $strUser = $manager->username;
    $strSecret = $manager->password;
    //dd($strHost . ' '. $strUser . ' ' . $strSecret);
    $errno = "";
    $errstr = "";
    $timeout = "30";
    $socket = fsockopen("$strHost","5038", $errno, $errstr, $timeout);
    fputs($socket, "Action: Login\r\n");
    fputs($socket, "UserName: $strUser\r\n");
    fputs($socket, "Secret: $strSecret\r\n\r\n");
    fputs($socket, "Action: Originate\r\n");
    fputs($socket, "Variable: span=$span\r\n");

    fputs($socket, "Channel: local/".$phone1."@ast_ivrc2cleg1\r\n");
    fputs($socket, "Context: ast_ivrc2cleg2\r\n");
    fputs($socket, "Variable: groupid=$groupid\r\n");
    fputs($socket, "Exten: ".$phone2."\r\n");
    fputs($socket, "Callerid: $didnumber\r\n");
    fputs($socket, "Priority: 1\r\n");
    fputs($socket, "Timeout: 30000\r\n\r\n");

    fputs($socket, "Action: Logoff\r\n\r\n");
    while (!feof($socket)) {
        $wrets .= fread($socket, 1000);
    }
        fclose($socket);
        $data['success'] = 'Cdr added successfully.';
        $data['status'] = 1;
        return $data;
        //return $wrets;
}

function getReminderCount(){
    $data = DB::table('reminders')->select('id');
    $data->where('reminders.operatorid', Auth::user()->id);
    $data->where('reminders.appoint_status', 'live');
    $data->where('reminders.reminder_seen', '0');
    $data->whereBetween('followupdate',[date('Y-m-d') . ' 00:00:00',date('Y-m-d H:i:s')]);
    $result = $data->count();
    return $result;
}

function getCustomers() {
    $query = DB::table('accountgroup')->select('id', 'name');
    if (Auth::user()->usertype == 'reseller' && !empty(Auth::user()->reseller->associated_groups)) {
        $query = $query->whereIn('id', json_decode(Auth::user()->reseller->associated_groups));
    } else if (Auth::user()->usertype == 'reseller') {
        $query = $query->where('resellerid', Auth::user()->resellerid);
    }
    $customers = $query->orderBy('name')->get();

    return $customers;
}

function getResellerGroupAdminIds($resellerId) {
    $resellergroup = DB::table('resellergroup')
                    ->where('id', $resellerId)
                    ->first();
    $groupAdminIdArray = [];
    if($resellergroup) {
        $groupAdminIdArray = isset($resellergroup->associated_groups) ? json_decode($resellergroup->associated_groups) : [];
    }
    return $groupAdminIdArray;
}
