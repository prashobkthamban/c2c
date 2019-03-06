<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VoiceEmail extends Model
{
    protected $table = 'voicemails';

    public static function getReport( )
    {

        $data = VoiceEmail::select('voicemails.*', 'name', 'resellername')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'voicemails.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'voicemails.resellerid');
        if (Auth::user()->usertype == 'reseller') {
            $data->where('voicemails.resellerid', Auth::user()->resellerid);
        } elseif (Auth::user()->usertype == 'operator') {
            $data->whereRaw(DB::raw('(cdr.operatorid = "' . Auth::user()->id . '")'));
        } else {
            $data->where('voicemails.groupid', Auth::user()->groupid);
        }
        $result = $data->orderBy('datetime', 'DESC')
            ->paginate(30);
        return $result;
    }
}
