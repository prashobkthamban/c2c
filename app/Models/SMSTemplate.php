<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class SMSTemplate extends Model
{
    protected $table = 'sms_template';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','user_type','name','body','inserted_date','group_id'];

    public static function getReport( ){

        $data = SMSTemplate::paginate(10);
        return $data;
    }

}
