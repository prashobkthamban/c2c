<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class CdrReport extends Model
{
    protected $table = 'cdr';

    public static function getReport( ){

       $data = CdrReport::select('cdr.*','name','resellername','opername','phonenumber')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid');
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }
        $result = $data->orderBy('datetime','DESC')
        ->paginate(30);
        return $result;
    }
}
