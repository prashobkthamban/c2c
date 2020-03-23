<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
	protected $table = 'users';
    protected $fillable = [
        'customer_name', 'coperate_id', 'start_date', 'end_date', 'status', 'did', 'multilanguage', 'language', 'record_call', 'operator_call_count', 'sms_api_user', 'sms_api_password', 'sms_api_sender', 'api', 'cdr_api_key', 'client_ip', 'cdr_tag', 'chanunavil_calls', 'conference_members', 'android_app', 'portal_sms', 'dial_stratergy', 'sms_support', 'push_api_service', 'pbx_extension'
        ];
    protected $primaryKey = 'id';
    //public $timestamps = false;
    protected $dates = ['start_date', 'end_date', 'created_at'];

    public function get_language() {
    	return DB::table('languages')->pluck('Language', 'id');
    }

    public function get_coperate() {
        return DB::table('resellergroup')->pluck('resellername', 'id');
    }
   
}
