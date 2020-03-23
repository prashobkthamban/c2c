<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_Reminder extends Model
{
    protected $table = 'lead_reminders';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'cdrreport_lead_id', 'date', 'time','title','task','inserted_date'];

}
