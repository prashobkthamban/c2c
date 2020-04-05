<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OperatorAccount extends Model
{
    protected $table = 'operatoraccount';
    protected $fillable = [
        'phonenumber', 'groupid', 'opername', 'oper_status', 'livetrasferid', 'start_work', 'end_work', 'app_use', 'edit', 'download', 'play', 'shift_id', 'working_days'
        ];
    public $timestamps = false;
    public function accounts()
    {
        return $this->hasOne('\App\Models\Account', 'operator_id');
    }

    public static function getReport( $post_data=NULL){

        $data = OperatorAccount::select('operatoraccount.*','dept_name')
            ->leftJoin('operator_dept_assgin', 'operator_dept_assgin.operatorid', '=', 'operatoraccount.id')
            ->leftJoin('operatordepartment', 'operatordepartment.id', '=', 'operator_dept_assgin.departmentid')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'operatoraccount.groupid');
            if( Auth::user()->usertype != 'admin'){
                if( Auth::user()->usertype == 'reseller'){
                    $data->where('operatoraccount.resellerid',Auth::user()->resellerid );
                }
                elseif( Auth::user()->usertype == 'groupadmin'){
                    $data->where('operatoraccount.groupid',Auth::user()->groupid );
                     $data->where('operatoraccount.operatortype','web');
                }
                else{
                    $data->where('operatoraccount.id',Auth::user()->id );
                }
            }

            if(isset($post_data['priority']) && $post_data['priority'] != '')
            {
                $data->where('operator_dept_assgin.priority ','LIKE','%' .$post_data['priority'].'%'  );
            }
            if(isset($post_data['caller_number']) && $post_data['caller_number'] != '')
            {
                $data->where('operatoraccount.number','LIKE','%' .$post_data['caller_number'].'%'  );
            }
            if(isset($post_data['department']) && $post_data['department'] != '')
            {
                $data->where('operatoraccount.dept_name','LIKE','%' .$post_data['department'].'%'  );
            }
            if(isset($post_data['operator']) && $post_data['operator'] != '')
            {
                $data->where('operatoraccount.id',$post_data['operator'] );
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
