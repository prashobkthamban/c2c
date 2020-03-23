<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CdrArchive extends Model
{
    protected $table = 'cdr_archive';

    public static function getReport( )
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
        $result = $data->orderBy('datetime', 'DESC')
            ->paginate(30);
        return $result;
    }
}
