<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['send_from_id', 'send_to_id', 'sendfromusertype', 'title', 'description', 'grp_readstatus', 'sendtousertype', 'fromusername', 'adm_read_status'];
    public $timestamps = false;

    public static function getReport( )
    {
        //dd(Auth::user()->id);
        $data = Notification::select( 'notifications.*', 'account.username' );

        if ( Auth::user()->usertype == 'groupadmin' )
        { 
            $data->where('notifications.send_from_id', Auth::user()->id );
            $data->orWhere('notifications.send_to_id', Auth::user()->id);
            $data->leftJoin('account', 'notifications.send_from_id', '=', 'account.groupid');
        }
        if ( Auth::user()->usertype == 'admin' )
        {
            $data->where('notifications.send_from_id', Auth::user()->id);
            $data->orWhere('notifications.send_to_id', Auth::user()->id);
            $data->leftJoin('account', 'notifications.send_from_id', '=', 'account.groupid');
        }
        if ( Auth::user()->usertype == 'operator' )
        {
            $data->where( 'notifications.send_from_id', Auth::user()->id );
            $data->orWhere('notifications.send_to_id', Auth::user()->id);
            $data->leftJoin('account', 'notifications.send_from_id', '=', 'account.id');
        }
        if ( Auth::user()->usertype == 'reseller' )
        {
            $data->where( 'notifications.send_from_id', Auth::user()->id );
            $data->orWhere('notifications.send_to_id', Auth::user()->id);
            $data->leftJoin('account', 'notifications.send_from_id', '=', 'account.id');
        }
        $result = $data->orderBy( 'datetime', 'DESC' )->paginate(10);
        return $result;
    }
}