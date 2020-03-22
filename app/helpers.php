<?php

function getResellers() {
	$resellers = DB::table('resellergroup')->pluck('resellername', 'id');
    return $resellers;
}

function getAccountgroups($usertype=null, $reseller= null) {
    //dd($reseller);
	if($usertype != 'reseller' && $reseller != null && $reseller != 0) {
        $cust =  DB::table('accountgroup')->where('resellerid', $reseller)->pluck('name', 'id');
    } else {
        $cust =  DB::table('accountgroup')->pluck('name', 'id');
    }
    return $cust;
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

function getGroupList() {
    $gpAcc =  DB::table('accountgroup')->select('accountgroup.name', 'account.id')->where('accountgroup.id', Auth::user()->groupid)->where('account.operator_id', NULL)->leftJoin('account', 'accountgroup.id', '=', 'account.groupid')->get();
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
    if($groupid != null) {
        $did_list = DB::table('dids')->where('assignedto', $groupid)->pluck('did', 'id'); 
    } else {
        $did_list = DB::table('dids')->pluck('did', 'id'); 
    }
    return $did_list;
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
