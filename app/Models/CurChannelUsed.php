<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class CurChannelUsed extends Model
{
    protected $table = 'cur_channel_used';

    public static function getReport( )
    {

        $data = CurChannelUsed::select('cur_channel_used.*', 'accountgroup.name', 'operatoraccount.opername', 'operatordepartment.dept_name')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cur_channel_used.groupid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cur_channel_used.operatorid')
            ->leftJoin('operatordepartment', 'operatordepartment.id', '=', 'cur_channel_used.departmentid');

        if (Auth::user()->usertype == 'reseller') {
            $data->where('accountgroup.resellerid', Auth::user()->resellerid);
        } elseif (Auth::user()->usertype == 'groupadmin') {
            $data->where('accountgroup.id', Auth::user()->groupid);
        }else{
            $data->where('cur_channel_used.operatorid', Auth::user()->id);
        }
        $result = $data->orderBy('id', 'DESC')
            ->paginate(30);
        return $result;
    }
}

