<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class CdrTag extends Model
{
    protected $table = 'cdr_tags';

    public static function getReport( )
    {
        $data = CdrTag::select( 'cdr_tags.*', 'name' )
            ->leftJoin( 'accountgroup', 'accountgroup.id', '=', 'cdr_tags.groupid' );

        if ( Auth::user()->usertype == 'groupadmin' )
        {
            $data->where( 'cdr_tags.groupid', Auth::user()->groupid );
        }

        $result = $data->orderBy( 'id', 'DESC' )->paginate( 30 );
        return $result;
    }
}
