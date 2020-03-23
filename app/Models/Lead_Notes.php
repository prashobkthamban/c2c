<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_Notes extends Model
{
    protected $table = 'lead_notes';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'user_name', 'note','inserted_date'];

}
