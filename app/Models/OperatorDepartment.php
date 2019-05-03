<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OperatorDepartment extends Model
{
    protected $table = 'operatordepartment';

    public static function getDepartmentbygroup( ){
        
        $data = OperatorDepartment::select('id','dept_name');           
        $result = $data->where('groupid',Auth::user()->groupid )->get();
        return $result;
    }
}
