<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CdrArchive extends Model
{
    protected $table = 'cdr_archive';

    public static function getReport($customer = '', $department = '', $operator = '', $tag = '', $status = '', $assigned_to = '', $did_no = '', $caller_number = '', $date = '', $start_date = '', $end_date = '')
    {
        $data = CdrArchive::select('cdr_archive.*', 'name', 'resellername', 'opername as opername', 'opername as assignedto')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr_archive.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr_archive.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr_archive.operatorid');
        if (Auth::user()->usertype == 'reseller') {
            $data->where('cdr_archive.resellerid', Auth::user()->resellerid);
        } elseif (Auth::user()->usertype == 'operator') {
            $data->whereRaw(DB::raw('(cdr_archive.operatorid = "' . Auth::user()->id . '" OR cdr_archive.assignedto = "' . Auth::user()->id . '")'));
        } else {
            $data->where('cdr_archive.groupid', Auth::user()->groupid);
        }
        if(!empty($customer)) {
            $data->where('cdr_archive.groupid',$customer);
        }
        if(!empty($department)) {
            $data->where('cdr_archive.deptname',$department);
        }
        if(!empty($operator)) {
            $data->where('cdr_archive.operatorid',$operator);
        }
        if(!empty($tag)) {
            $data->where('cdr_archive.tag',$tag);
        }
        if(!empty($status)) {
            $data->where('cdr_archive.status',$status);
        }
        if(!empty($assigned_to)) {
            $data->where('cdr_archive.assignedto',$assigned_to);
        }
        if(!empty($did_no)) {
            $data->where('cdr_archive.did_no',$did_no);
        }
        if(!empty($caller_number)) {
            $data->where('cdr_archive.number',$caller_number);
        }
        if(!empty($date)) {
            $fromDate = date('Y-m-d') . ' 00:00:00';
            $toDate = date('Y-m-d') . ' 23:59:59';
            if($date == 'yesterday') {
                $yesterday = date('Y-m-d',strtotime("-1 days"));
                $fromDate = $yesterday . ' 00:00:00';
                $toDate = $yesterday . ' 23:59:59';
            } else if($date == 'week') {
                $fromDate =date('Y-m-d',strtotime("-1 days")) . ' 00:00:00';
            } else if($date == 'week') {
                $fromDate =date('Y-m-d',strtotime("-1 week")) . ' 00:00:00';
            } else if($date == 'month') {
                $fromDate =date('Y-m-d',strtotime("-1 month")) . ' 00:00:00';
            } else if($date == 'custom') {
                $fromDate = date('Y-m-d',strtotime($start_date)) . ' 00:00:00';
                $toDate = date('Y-m-d',strtotime($end_date)) . ' 23:59:59';
            }
            $data->whereBetween('cdr_archive.datetime',[$fromDate, $toDate]);
        }
        $result = $data->orderBy('datetime', 'DESC')
            ->paginate(30);
        return $result;
    }
}
