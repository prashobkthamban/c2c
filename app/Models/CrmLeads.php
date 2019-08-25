<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//did Model

class CrmLeads extends Model
{
    protected $table = 'crm_leads';
    protected $primaryKey = 'lead_id';
    protected $fillable = ['name', 'DOB', 'phone_number', 'email','address','lead_status','lead_owner','category_id','sub_category_id'];
    public $timestamps = false;

}
