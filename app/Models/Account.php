<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Account extends Authenticatable
{
    protected $table = 'account';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password', 'operator_id'];
    protected $hidden = ['password'];
    public $timestamps = false;

	public function accountdetails()
	{
	    return $this->hasOne('\App\AccountGroupdetails','groupid');
	}

	public function operators()
    {
        return $this->belongsTo('OperatorAccount', 'id');
    }

}
