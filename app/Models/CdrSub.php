<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;


class CdrSub extends Model
{
    protected $table = 'cdr_sub';

    public static function getCdrSub($id){

       $data = CdrSub::select('cdr_sub.date_time', 'operatoraccount.opername', 'cdr_sub.status')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr_sub.operator') ;

            $data->where('cdr_sub.cdr_id', $id );

        $result = $data->orderBy('cdr_sub.date_time','DESC')->get();
        return $result;
    }

}
