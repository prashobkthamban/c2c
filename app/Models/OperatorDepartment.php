<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperatorDepartment extends Model
{
    protected $table = 'operatordepartment';
    public $timestamps = false;
    public static function getDepartmentbygroup( ){
        
        $data = OperatorDepartment::select('id','dept_name');           
        $result = $data->where('groupid',Auth::user()->groupid )->get();
        return $result;
    }

     public static function getReport($dt=0,$c2c=0, $post_data=NULL){

     	//DB::connection()->enableQueryLog();
        $data = OperatorDepartment::select('*');
       
        if($c2c == 1)
        {
        	$data->where(function ($query) use ($dt) {
                $query->where('c2c','=',  1)
                      ->orWhere(function ($quer) use ($dt){
                      	$quer->where('c2c', '=', 0)->where('DT','=',$dt);
                      });
            });
        }
        else
        {
        	$data->where('c2c',0);
        	$data->where('DT',$dt);
        }
        $result = $data->where('groupid',Auth::user()->groupid)->get(); 
        //dd(DB::getQueryLog());
        return $result;
        
    }

   
}
