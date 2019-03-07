<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $table = 'notifications';

    public static function getReport( )
    {
        $data = Notification::select( 'notifications.*', 'name' )
            ->leftJoin( 'accountgroup', 'accountgroup.id', '=', 'notifications.groupid' );

        if ( Auth::user()->usertype == 'groupadmin' )
        {
            $data->where( 'notifications.groupid', Auth::user()->groupid );
        }
        $result = $data->orderBy( 'datetime', 'DESC' )->paginate( 30 );
        return $result;
    }
}