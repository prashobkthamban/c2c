<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Contact extends Model
{
    protected $table = 'contacts';

    public static function getReport( ){

        $data = Contact::paginate(30);
        return $data;
    }
}
