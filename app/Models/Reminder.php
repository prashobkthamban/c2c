<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Reminder extends Model
{
    protected $table = 'reminders';

    public static function getReport( ){

        $data = Reminder::select('reminders.*','name','opername','phonenumber')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'reminders.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'reminders.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'reminders.operatorid');
        if( Auth::user()->usertype == 'reseller'){
            $data->where('reminders.resellerid',Auth::user()->resellerid );
        }
        elseif( Auth::user()->usertype == 'operator'){
            $data->where('reminders.operatorid',Auth::user()->resellerid );
        }
        else{
            //$data->where('cdrpbx.groupid',Auth::user()->groupid );
        }
        $result = $data->orderBy('followupdate','DESC')
            ->paginate(30);
        return $result;
    }
}
