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
        $result = CdrTag::select( 'cdr_tags.*', 'name' )
            ->leftJoin( 'accountgroup', 'accountgroup.id', '=', 'cdr_tags.groupid' )
            ->where( 'cdr_tags.groupid', Auth::user()->groupid )
            ->orderBy( 'id', 'DESC' )->paginate( 10 );
        return $result;
    }

    public static function getTag(){
        return DB::table('cdr_tags')->where('groupid', Auth::user()->groupid )->pluck('tag', 'tag');
    }

    public static function getTagFromId($id){
        return CdrTag::select('tag')->where('id', $id )->first();
    }


}
