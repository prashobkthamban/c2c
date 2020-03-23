<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_Msg extends Model
{
    protected $table = 'lead_msg';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'msg_from', 'msg_to','message','inserted_date'];

}
