<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OperatorAccount extends Model
{
    protected $table = 'operatoraccount';

    public static function getReport( ){

        $data = OperatorAccount::select('operatoraccount.*','dept_name')
            ->leftJoin('operator_dept_assgin', 'operator_dept_assgin.operatorid', '=', 'operatoraccount.id')
            ->leftJoin('operatordepartment', 'operatordepartment.id', '=', 'operator_dept_assgin.departmentid')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'operatoraccount.groupid');
        if( Auth::user()->usertype == 'reseller'){
            $data->where('operatoraccount.resellerid',Auth::user()->resellerid );
        }
        elseif( Auth::user()->usertype == 'groupadmin'){
            //$data->where('operatoraccount.operatorid',Auth::user()->resellerid );
        }
        else{
            //$data->where('cdrpbx.groupid',Auth::user()->groupid );
        }
        $result = $data->orderBy('opername','ASC')
            ->paginate(30);
        return $result;
    }

    public static function getOperatorbygroup( ){
        
        $data = OperatorAccount::select('id','opername');          
         if( Auth::user()->usertype == 'reseller'){
            $data->where('operatoraccount.resellerid',Auth::user()->resellerid );
        } 
        $result = $data->where('groupid',Auth::user()->groupid )->get();
        return $result;
    }
}
