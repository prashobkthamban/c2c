<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;


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
    public static function getContact( $rowid ){
        return CdrReport::select( 'number' )->where( 'cdr.cdrid', $rowid )
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid')
            ->first( );
    }

}
