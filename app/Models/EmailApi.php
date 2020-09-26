<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class EmailApi extends Model
{
    protected $table = 'email_api';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','user_type','smtp_host','port','username','password','type','inserted_date'];

    public static function getReport( ){

        $data = EmailApi::paginate(10);
        return $data;
    }

}
