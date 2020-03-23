<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class lead_stages extends Model
{
    protected $table = 'lead_stages';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'cdrreport_lead_id', 'levels', 'status'];

}
