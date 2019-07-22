<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountGroupdetails extends Model
{
    //
    protected $table = 'accountgroupdetails';


	public function account()
	{
	    return $this->belongsTo('\App\Models\Accountgroup','groupid');
	}


}
