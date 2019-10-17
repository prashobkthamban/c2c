<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['groupid', 'sendfromusertype', 'title', 'description', 'grp_readstatus', 'sendtousertype', 'fromusername', 'adm_read_status'];
    public $timestamps = false;

    public static function getReport( )
    {
        $data = Notification::select( 'notifications.*', 'name' )
            ->leftJoin('accountgroup', 'notifications.groupid', '=', 'accountgroup.id');

        if ( Auth::user()->usertype == 'groupadmin' )
        {
            $data->where( 'notifications.groupid', Auth::user()->groupid );
        }
        $result = $data->orderBy( 'datetime', 'DESC' )->paginate( 30 );
        return $result;
    }
}