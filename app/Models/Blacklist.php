<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Blacklist extends Model
{
    protected $table = 'blacklist';
    public static function getReport( )
    {
        $data = Blacklist::select( 'blacklist.*', 'name', 'resellername' )->leftJoin( 'accountgroup', 'accountgroup.id', '=', 'blacklist.groupid' )->leftJoin( 'resellergroup', 'resellergroup.id', '=', 'blacklist.resellerid' );
        if ( Auth::user()->usertype == 'reseller' )
        {
            $data->where( 'blacklist.resellerid', Auth::user()->resellerid );
        }
        elseif ( Auth::user()->usertype == 'groupadmin' )
        {
            $data->where( 'blacklist.groupid', Auth::user()->groupid );
        }
        $result = $data->orderBy( 'id', 'DESC' )->paginate( 30 );
        return $result;
    }
}