<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\CdrReport;
use Mockery\CountValidator\Exception;


class Contact extends Model
{
    protected $table = 'contacts';
    public $timestamps = false;

    public static function getReport( ){

        $data = Contact::paginate(30);
        return $data;
    }
    public static function InsertContact( $input ){
        $cdr = CdrReport::getContact( $input['rowid'] );

        return Contact::insertGetId(
            ['fname' => $input['fname'], 'lname' => $input['lname'], 'email' => $input['email'], 'phone' => $cdr->number, 'groupid' => Auth::user()->groupid ]
        );
    }

    public static function getContactsByNumber($rowid){
        $cdr = CdrReport::getContact( $rowid );
        return Contact::where("phone",$cdr->number)->first();
    }

    public static function updateContact($input,$id){
        try{
            Contact::where('id', $id)->update(array('fname' => $input['fname'],'email' => $input['email'],'lname' => $input['lname']));
        }
        catch(Exception $e){

        }
        return true;

    }
}
