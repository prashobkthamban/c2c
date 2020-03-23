<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Converted extends Model
{
    protected $table = 'converted';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'user_id', 'first_name',	'last_name', 'gst_no', 'mobile_no', 'email', 'address','company_name'];

    public static function getReport( ){

        $data = Converted::paginate(10);
        return $data;
    }

}
