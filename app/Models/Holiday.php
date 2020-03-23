<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Holiday extends Model
{
    protected $table = 'holiday';
    protected $fillable = ['date', 'reason', 'groupid', 'resellerid'];
    public $timestamps = false;

    public static function getReport( )
    {

        $data = Holiday::select('holiday.*', 'name', 'resellername')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'holiday.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'holiday.resellerid');

        if (Auth::user()->usertype == 'reseller') {
            $data->where('holiday.resellerid', Auth::user()->resellerid);
        } elseif (Auth::user()->usertype == 'groupadmin') {
            $data->where('holiday.groupid', Auth::user()->groupid);
        }
        $result = $data->orderBy('id', 'DESC')
            ->paginate(30);
        return $result;
    }
}
