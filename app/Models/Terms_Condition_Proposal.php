<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Terms_Condition_Proposal extends Model
{
    protected $table = 'terms_condition_proposal';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['id','user_id','name','inserted_date','user_type'];

}
