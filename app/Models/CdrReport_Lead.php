<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class CdrReport_Lead extends Model
{
    protected $table = 'cdrreport_lead';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'cdrreport_id', 'first_name', 'last_name','company_name','email','owner_name','lead_stage','total_amount','inserted_date','operatorid','phoneno','alt_phoneno'];

    public static function getReport( ){

        $data = CdrReport_Lead::paginate(10);
        return $data;
    }

}
