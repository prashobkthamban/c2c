<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VoiceEmail extends Model
{
    protected $table = 'voicemails';

    public static function getReport($post_data=NULL )
    {

        $data = VoiceEmail::select('voicemails.*', 'name', 'resellername')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'voicemails.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'voicemails.resellerid');
        if (Auth::user()->usertype == 'reseller') {
            $data->where('voicemails.resellerid', Auth::user()->resellerid);
        } elseif (Auth::user()->usertype == 'operator') {
            $data->whereRaw(DB::raw('(cdr.operatorid = "' . Auth::user()->id . '")'));
        } else {
            $data->where('voicemails.groupid', Auth::user()->groupid);
        }

        if(isset($post_data['department']) && $post_data['department'] != '')
        {
            $data->where('voicemails.departmentname','LIKE','%' .$post_data['department'].'%'  );
        }
        
        if(isset($post_data['date']) && $post_data['date'] != '')
        {
            $startdate = $enddate = date('Y-m-d');
            if($post_data['date'] == 'yesterday')
            {
                $startdate = $enddate = date("Y-m-d", strtotime("-1 day"));
            }
            elseif($post_data['date'] == 'week')
            {
                $startdate = date("Y-m-d", strtotime("-7 day"));
                $enddate = date("Y-m-d");
            }
            elseif($post_data['date'] == 'month')
            {
                $startdate = date("Y-m-d", strtotime("-1 month"));
                $enddate = date("Y-m-d");
            }
            elseif($post_data['date'] == 'custom')
            {
                if($post_data['startdate'] != '')
                {
                    $startdate = date('Y-m-d',strtotime($post_data['startdate']));
                }
                if($post_data['enddate'] != '')
                {
                    $enddate = date('Y-m-d',strtotime($post_data['enddate']));
                }
                
            }

            if($startdate != '')
            {
                $data->where('voicemails.datetime','>',$startdate.' 00:00:00');   
            }
            if($enddate != '')
            {
                $data->where('voicemails.datetime','<',$enddate.' 23:59:59');   
            }
        }
        //
        if(isset($post_data['did_no']) && $post_data['did_no'] != '')
        {
            $data->where('voicemails.dnid',$post_data['did_no'] );
        }
       
       

        $result = $data->orderBy('datetime', 'DESC')
            ->paginate(30);
        return $result;
    }

    public static function getdids( ){
        $data = VoiceEmail::select( 'dnid' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('voicemails.resellerid',Auth::user()->resellerid );
        }    
        $data->where('voicemails.groupid',Auth::user()->groupid);
         return $data->distinct()->get( );
    }
    public static function get_dept_by_group( ){
        $data = VoiceEmail::select( 'departmentname' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('voicemails.resellerid',Auth::user()->resellerid );
        }    
        $data->where('voicemails.groupid',Auth::user()->groupid);
         return $data->distinct()->get( );
    }

}
