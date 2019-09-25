<?php

function getResellers() {
	$resellers = DB::table('resellergroup')->pluck('resellername', 'id');
    return $resellers;
}

function getAccountgroups($usertype=null, $reseller= null) {
	if($usertype != 'reseller' && $reseller != null) {
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