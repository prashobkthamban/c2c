<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//did Model

class CrmLeads extends Model
{
    protected $table = 'crm_leads';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'dob', 'phone_number', 'email','address','lead_status','lead_owner'];
    public $timestamps = false;

}
