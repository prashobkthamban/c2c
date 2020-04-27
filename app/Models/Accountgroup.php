<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Accountgroup extends Model
{
    
    protected $table = 'accountgroup';
    protected $fillable = [
        'name', 'resellerid', 'startdate', 'enddate', 'status', 'did', 'multi_lang', 'lang_file', 'try_count', 'record_call', 'dial_time', 'maxcall_dur', 'sms_support', 'no_channels', 'sms_api_gateway_id', 'sms_api_user', 'sms_api_pass', 'sms_api_senderid', 'operator_dpt', 'API', 'c2c', 'c2cAPI', 'servicetype', 'ip', 'cdr_apikey', 'andriodapp', 'max_no_confrence', 'web_sms', 'dial_statergy', 'c2c_channels', 'operator_no_logins', 'emailservice_assign_cdr', 'smsservice_assign_cdr', 'pushapi', 'pbxexten', 'cdr_tag', 'cdr_chnunavil_log', 'working_days','crm','crm_users','leads_access'
        ];
        
    public function account()
    {
        return $this->hasOne('App\Models\Account','groupid');
    }

    public static function getdetailsbygroup( ){
        
        $data = Accountgroup::select('operator_dpt','c2c');           
        $result = $data->where('id',Auth::user()->groupid )->first();
        return $result;
    }
    
    public static function getservicebygroup( ){  
        $data = Accountgroup::select('emailservice_assign_cdr','smsservice_assign_cdr');           
        $result = $data->where('id',Auth::user()->groupid )->first();
        return $result;
    }

    public function get_language() {
    	return DB::table('languages')->pluck('Language', 'id');
    }

    public function get_coperate() {
        return DB::table('resellergroup')->pluck('resellername', 'id');
    }

    public function sms_api_gateway() {
        return DB::table('sms_api_gateways')->pluck('name', 'id');
    }
}
