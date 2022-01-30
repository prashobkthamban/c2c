<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Reminder extends Model
{
    protected $table = 'reminders';

    public function cdrNotes()
    {
        return $this->hasMany('App\Models\CdrNote', 'uniqueid', 'uniqueid');
    }

    public function contacts()
    {
        return $this->hasOne('App\Models\Contact', 'phone', 'number');
    }

    public function operatorAccount() {
        return $this->hasOne('App\Models\OperatorAccount', 'id', 'operatorid');
    }

    public static function getReport($params){
        $data = Reminder::select('accountgroup.name','operatoraccount.opername','operatoraccount.phonenumber','reminders.*')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'reminders.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'reminders.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'reminders.operatorid')
            ->with(['cdrNotes', 'contacts', 'operatorAccount']);
        if(isset($params['caller']) && $params['caller'] != '')
        {
            $data->where('reminders.number','LIKE','%' .$params['caller'].'%'  );
        }
        if(isset($params['department']) && $params['department'] != '')
        {
            $data->where('reminders.deptname','LIKE','%' .$params['department'].'%'  );
        }
        if(isset($params['status']) && $params['status'] != '')
        {
            $data->where('reminders.appoint_status', $params['status']);
        }
        if(isset($params['operator']) && $params['operator'] != '')
        {
            $data->where('operatoraccount.opername','LIKE','%' .$params['operator'].'%'  );
        }
        if(!empty($params['date'])) {
            if($params['date'] == 'today')
            $params['date_from'] = $params['date_to'] = date("Y-m-d");
            elseif ($params['date'] == 'yesterday')
                $params['date_from'] = $params['date_to'] = date("Y-m-d", strtotime("-1 day"));
            elseif ($params['date'] == 'week') {
                $params['date_from'] = date("Y-m-d", strtotime("-7 day"));
                $params['date_to'] = date("Y-m-d");
            }
            elseif($params['date'] == 'month') {
                $params['date_from'] = date("Y-m-d", strtotime("-1 month"));
                $params['date_to'] = date("Y-m-d");
            } 
            elseif($params['date'] == 'custom') {
                if($params['date_from'] != '')
                    $params['date_from'] = date('Y-m-d',strtotime($params['date_from']));
                if($params['date_to'] != '')
                    $params['date_to'] = date('Y-m-d',strtotime($params['date_to']));
            }
            $data->whereBetween('followupdate',[$params['date_from'].'%',$params['date_to'].'%']);
        }
        if( Auth::user()->usertype == 'reseller'){
            $data->where('reminders.resellerid',Auth::user()->resellerid );
        }
        elseif( Auth::user()->usertype == 'operator'){
            $data->where('reminders.operatorid',Auth::user()->id );
        }
        else if( Auth::user()->usertype == 'groupadmin') {
            $data->where('reminders.groupid',Auth::user()->groupid );
        }
        $result = $data->orderBy('followupdate','DESC')->groupBy('reminders.id')
            ->paginate(10);
        // dd($result);
        return $result;
    }

    public static function insertReminder($data,$newdate){

        return Reminder::insertGetId(
            ['number' => $data->number,
                'groupid' => Auth::user()->groupid,
                'operatorid' => Auth::user()->id,
                'followupdate' => $newdate,
                'appoint_status' => "Live",
                'follower' => Auth::user()->username,
                'recordedfilename' => $data->recordedfilename,
                'calldate' =>  $data->datetime,
                'deptname' => $data->deptname,
                'uniqueid' => $data->uniqueid,
                'resellerid' => $data->resellerid,
                'secondleg' => $data->secondleg,
                'assignedto' => $data->assignedto
            ]
        );
    }
}
