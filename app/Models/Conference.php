<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conference extends Model
{
    protected $table = 'dialout_confrence';

    public static function getReport( )
    {

        $data = Conference::select('dialout_confrence.*', 'name', 'resellername')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'dialout_confrence.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'dialout_confrence.resellerid');

        if (Auth::user()->usertype == 'reseller') {
            $data->where('dialout_confrence.resellerid', Auth::user()->resellerid);
        } elseif (Auth::user()->usertype == 'groupadmin') {
            $data->where('dialout_confrence.groupid', Auth::user()->groupid);
        }
        $result = $data->orderBy('id', 'DESC')
            ->paginate(30);
        return $result;
    }
}
