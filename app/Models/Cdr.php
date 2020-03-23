<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Cdr extends Model
{
    protected $table = 'cdr';
    public $timestamps = false;


    public static function updateTag($input,$tagname){
        try{
            Cdr::where('cdrid', $input['cdrid'])->update(array('tag' =>$tagname));
        }
        catch(Exception $e){

        }
        return true;

    }

    public static function getCdrFromId($id){
        return Cdr::where('cdrid', $id )->first();
    }
}
