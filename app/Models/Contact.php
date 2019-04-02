<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\CdrReport;


class Contact extends Model
{
    protected $table = 'contacts';

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

}
