<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Accountgroup extends Model
{
    //
    protected $table = 'accountgroup';

    public static function getdetailsbygroup( ){
        
        $data = Accountgroup::select('operator_dpt','c2c');           
        $result = $data->where('id',Auth::user()->groupid )->first();
        return $result;
    }
}
