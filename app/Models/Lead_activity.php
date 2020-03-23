<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_activity extends Model
{
    protected $table = 'lead_recent_activities';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'activity_name', 'activity_data', 'inserted_date'];

}
