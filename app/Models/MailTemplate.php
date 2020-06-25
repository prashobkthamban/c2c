<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class MailTemplate extends Model
{
    protected $table = 'email_template';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','user_type','name', 'subject', 'body','attachment','inserted_date','group_id'];

    public static function getReport( ){

        $data = MailTemplate::paginate(10);
        return $data;
    }

}
