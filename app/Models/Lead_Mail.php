<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Lead_Mail extends Model
{
    protected $table = 'lead_mail';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'cdrreport_lead_id', 'from', 'to','cc','bcc','subject','body','inserted_date'];

}
