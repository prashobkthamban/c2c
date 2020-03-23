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

    public static function getReport( ){

        $data = Reminder::select('name','opername','phonenumber','reminders.*')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'reminders.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'reminders.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'reminders.operatorid')
            ->with(['cdrNotes', 'contacts']);
        if( Auth::user()->usertype == 'reseller'){
            $data->where('reminders.resellerid',Auth::user()->resellerid );
        }
        elseif( Auth::user()->usertype == 'operator'){
            $data->where('reminders.operatorid',Auth::user()->resellerid );
        }
        else{
            //$data->where('cdrpbx.groupid',Auth::user()->groupid );
        }
        $result = $data->orderBy('followupdate','DESC')->groupBy('reminders.id')
            ->paginate(30);
        //dd($result);
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
