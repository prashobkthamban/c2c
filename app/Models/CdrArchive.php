<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CdrArchive extends Model
{
    protected $table = 'cdr_archive';

    // public static function getReport($customer = '', $department = '', $operator = '', $tag = '', $status = '', $assigned_to = '', $did_no = '', $caller_number = '', $date = '', $start_date = '', $end_date = '')
    // {
    //     $data = CdrArchive::select('cdr_archive.*', 'name', 'resellername', 'opername as opername', 'opername as assignedto')
    //         ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr_archive.groupid')
    //         ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr_archive.resellerid')
    //         ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr_archive.operatorid');
    //     if (Auth::user()->usertype == 'reseller') {
    //         $data->where('cdr_archive.resellerid', Auth::user()->resellerid);
    //     } elseif (Auth::user()->usertype == 'operator') {
    //         $data->whereRaw(DB::raw('(cdr_archive.operatorid = "' . Auth::user()->id . '" OR cdr_archive.assignedto = "' . Auth::user()->id . '")'));
    //     } else {
    //         $data->where('cdr_archive.groupid', Auth::user()->groupid);
    //     }
    //     if(!empty($customer)) {
    //         $data->where('cdr_archive.groupid',$customer);
    //     }
    //     if(!empty($department)) {
    //         $data->where('cdr_archive.deptname',$department);
    //     }
    //     if(!empty($operator)) {
    //         $data->where('cdr_archive.operatorid',$operator);
    //     }
    //     if(!empty($tag)) {
    //         $data->where('cdr_archive.tag',$tag);
    //     }
    //     if(!empty($status)) {
    //         $data->where('cdr_archive.status',$status);
    //     }
    //     if(!empty($assigned_to)) {
    //         $data->where('cdr_archive.assignedto',$assigned_to);
    //     }
    //     if(!empty($did_no)) {
    //         $data->where('cdr_archive.did_no',$did_no);
    //     }
    //     if(!empty($caller_number)) {
    //         $data->where('cdr_archive.number',$caller_number);
    //     }
    //     if(!empty($date)) {
    //         $fromDate = date('Y-m-d') . ' 00:00:00';
    //         $toDate = date('Y-m-d') . ' 23:59:59';
    //         if($date == 'yesterday') {
    //             $yesterday = date('Y-m-d',strtotime("-1 days"));
    //             $fromDate = $yesterday . ' 00:00:00';
    //             $toDate = $yesterday . ' 23:59:59';
    //         } else if($date == 'week') {
    //             $fromDate =date('Y-m-d',strtotime("-1 days")) . ' 00:00:00';
    //         } else if($date == 'week') {
    //             $fromDate =date('Y-m-d',strtotime("-1 week")) . ' 00:00:00';
    //         } else if($date == 'month') {
    //             $fromDate =date('Y-m-d',strtotime("-1 month")) . ' 00:00:00';
    //         } else if($date == 'custom') {
    //             $fromDate = date('Y-m-d',strtotime($start_date)) . ' 00:00:00';
    //             $toDate = date('Y-m-d',strtotime($end_date)) . ' 23:59:59';
    //         }
    //         $data->whereBetween('cdr_archive.datetime',[$fromDate, $toDate]);
    //     }
    //     $result = $data->orderBy('datetime', 'DESC')
    //         ->paginate(30);
    //     return $result;
    // }

    public function cdrNotes()
    {
        return $this->hasMany('App\Models\CdrNote', 'uniqueid', 'uniqueid');
    }
    
    public function operatorAccount() {
        return $this->hasOne('App\Models\OperatorAccount', 'id', 'operatorid');
    }

    public function accountGroup() {
        return $this->hasOne('App\Models\Accountgroup', 'id', 'groupid');
    }
    
    public function operatorAssigned() {
        return $this->hasOne('App\Models\OperatorAccount', 'id', 'assignedto');
    }

    public function contacts()
    {
        return $this->hasOne('App\Models\Contact', 'phone', 'number');
    }

    public function reminder()
    {
        return $this->hasOne('App\Models\Reminder', 'uniqueid', 'uniqueid');
    }

    public static function getReport($groupId = '', $department = '', $operator = '', $tag = '', $status = '', $assigned_to = '', $did_no = '', $caller_number = '', $date = '', $start_date = '', $end_date = '', $searchText = '', $sortOrderArray = [], $limit = 0, $skip = 0, $draw = 1) {
        $groupIdArray = [];
        if(!empty($groupId)) {
            $groupIdArray = [$groupId];
        }
        $userType = Auth::user()->usertype;
        $data = CdrArchive::with(['cdrNotes', 'reminder', 'operatorAssigned'])
                ->select('cdr_archive.*', 'accountgroup.name as customerName', 'operatoraccount.opername as operatorName')
                ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr_archive.groupid')
                ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr_archive.operatorid');
        if( $userType == 'reseller') {
            if(empty($groupId)) {
                $groupIdArray = getResellerGroupAdminIds(Auth::user()->resellerid);
            }
        } else if( $userType == 'operator' ){
            $data->where('cdr_archive.operatorid',Auth::user()->operator_id );
        } else if($userType == 'admin') {

        } else if($userType == 'groupadmin') {
            if(empty($groupId)) {
                $groupIdArray = [Auth::user()->groupid];
            }
        }
        if(!empty($groupIdArray)) {
            $data->whereIn('cdr_archive.groupid', $groupIdArray);
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
            $data->where('cdr_archive.number', 'like', '%' . trim($caller_number) . '%');
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
        $recordsTotal = $data->count();
        // if(!empty($searchText)) {
        //     $searchText = strtolower(trim($searchText));
        //     $data->where('cdr_archive.number', 'like', '%' . trim($searchText) . '%')
        //     ->orWhere(DB::raw('lower(contacts.fname)'), 'like', '%' . $searchText . '%')
        //     ->orWhere(DB::raw('lower(contacts.lname)'), 'like', '%' . $searchText . '%')
        //     ->orWhere('cdr_archive.datetime', 'like', '%' . trim($searchText) . '%')
        //     ->orWhere(DB::raw('lower(cdr_archive.status)'), 'like', '%' . $searchText . '%')
        //     ->orWhere(DB::raw('lower(cdr_archive.deptname)'), 'like', '%' . $searchText . '%')
        //     ->orWhere(DB::raw('lower(operatoraccount.opername)'), 'like', '%' . $searchText . '%')
        //     ;
        // }
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
        // dd($results);

        $data = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $results
        ];
        return $data;
    }
}
