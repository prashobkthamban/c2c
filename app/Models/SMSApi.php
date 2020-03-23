<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class SMSApi extends Model
{
    protected $table = 'sms_api';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id','link','sender_id','username','password','type','inserted_date'];

    public static function getReport( ){

        $data = SMSApi::paginate(10);
        return $data;
    }

}
