<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

    public static function getTag(){
        //return CdrTag::select('id','tag')->where('groupid', Auth::user()->groupid )->get();
        return DB::table('cdr_tags')->where('groupid', Auth::user()->groupid )->pluck('tag', 'tag');
    }

    public static function getTagFromId($id){
        return CdrTag::select('tag')->where('id', $id )->first();
    }


}
