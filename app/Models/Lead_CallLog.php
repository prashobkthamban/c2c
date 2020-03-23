<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_CallLog extends Model
{
    protected $table = 'lead_call_log';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'call_type', 'outcomes','associate_call','call_log_name','notes','inserted_date'];

}
