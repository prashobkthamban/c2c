<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Terms_Condition_Invoice extends Model
{
    protected $table = 'terms_condition_invoice';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','name','inserted_date','user_type'];

    public static function getReport( ){

        $data = Terms_Condition_Invoice::paginate(10);
        return $data;
    }

}
