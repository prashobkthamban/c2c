<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Account extends Authenticatable
{
    use Notifiable;
    protected $table = 'account';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password', 'operator_id'];
    //protected $hidden = ['password'];
    public $timestamps = false;

	public function accountdetails()
	{
	    return $this->belongsTo('App\Models\Accountgroup', 'groupid');
    }
    
    public function opeartor()
	{
	    return $this->belongsTo('App\Models\OperatorAccount', 'operator_id');
    }
    
    public function reseller()
	{
	    return $this->belongsTo('App\Models\Resellergroup', 'resellerid');
	}
 
    public function did()
    {
        return $this->belongsTo('App\Models\Dids', 'groupid', 'assignedto');
    }

    public function extradid()
    {
        return $this->hasMany('App\Models\Extra_dids', 'groupid', 'groupid');
    }

	public function operators()
    {
        return $this->belongsTo('App\Models\OperatorAccount', 'operator_id');
    }

}
