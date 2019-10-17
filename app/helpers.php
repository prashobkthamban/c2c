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

function getAccountgroupdetails($groupid = null) {
    if($groupid != null) {
       $acc_details = DB::table('accountgroupdetails')->where('groupid', $groupid)->pluck('ivr_level_name', 'id'); 
    } else {
        $acc_details = DB::table('accountgroupdetails')->pluck('ivr_level_name', 'id'); 
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
    if(Auth::user()->usertype == 'admin') {
        $not_list = DB::table('notifications')->where('groupid', Auth::user()->groupid)->where('adm_read_status', '0')->get(); 
    } else {
        $not_list = DB::table('notifications')->where('groupid', Auth::user()->groupid)->where('grp_readstatus', '0')->get(); 
    }
    //dd($data['not_list']->count());
    return $not_list;
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