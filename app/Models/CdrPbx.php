<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CdrPbx extends Model
{
    protected $table = 'cdrpbx';

    public static function getReport( ){

        $data = CdrPbx::select('cdrpbx.*','name','resellername','opername','phonenumber')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdrpbx.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdrpbx.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdrpbx.operatorid');
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }
        elseif( Auth::user()->usertype == 'operator'){
            $data->where('cdrpbx.operatorid',Auth::user()->resellerid );
        }
        else{
            //$data->where('cdrpbx.groupid',Auth::user()->groupid );
        }
        $result = $data->orderBy('datetime','DESC')
            ->paginate(30);
        return $result;
    }
}
