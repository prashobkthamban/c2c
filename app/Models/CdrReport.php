<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;


class CdrReport extends Model
{
    protected $table = 'cdr';

    public static function getReport( ){

       $data = CdrReport::select('cdr.*','name','resellername','opername','phonenumber')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid');
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }
        $result = $data->orderBy('datetime','DESC')
        ->paginate(30);
        return $result;
    }
    public static function getContact( $rowid ){
        return CdrReport::select( 'number' )->where( 'cdr.cdrid', $rowid )
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid')
            ->first( );
    }

    public static function getReport_search($post_data )
    {
         $data = CdrReport::select('cdr.*','name','resellername','opername','phonenumber')
              
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid');
        
        if(isset($post_data['department']) && $post_data['department'] != '')
        {
            $data->where('cdr.deptname','LIKE','%' .$post_data['department'].'%'  );
        }
        if(isset($post_data['operator']) && $post_data['operator'] != '')
        {
             $data->where('cdr.operatorid',$post_data['operator'] );
        }
        if(isset($post_data['assigned_to']) && $post_data['assigned_to'] != '')
        {
             $data->where('cdr.assignedto',$post_data['operator'] );
        }
        if(isset($post_data['caller_number']) && $post_data['caller_number'] != '')
        {
            $data->where('cdr.number','LIKE','%' .$post_data['caller_number'].'%'  );
        }
        if(isset($post_data['status']) && $post_data['status'] != '')
        {
            $data->where('cdr.status','LIKE','%' .$post_data['status'].'%'  );
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
                $data->where('cdr.datetime','>',$startdate.' 00:00:00');   
            }
            if($enddate != '')
            {
                $data->where('cdr.datetime','<',$enddate.' 23:59:59');   
            }
        }
        //
        if(isset($post_data['did_no']) && $post_data['did_no'] != '')
        {
            $data->where('cdr.did_no',$post_data['did_no'] );
        }
        if(isset($post_data['tags']) && $post_data['tags'] != '')
        {
            $data->where('cdr.tag','LIKE','%' .$post_data['tags'].'%'  );
        }
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }
        
        $result = $data->orderBy('datetime','DESC')
        ->paginate(30);
        return $result;
    }

    public static function getstatus( ){
        $data = CdrReport::select( 'status' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }    
        $data->where('cdr.groupid',Auth::user()->groupid);
         return $data->distinct()->get( );
    }
//
    public static function getdids( ){
        $data = CdrReport::select( 'did_no' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }    
        $data->where('cdr.groupid',Auth::user()->groupid);
         return $data->distinct()->get( );
    }

    public static function cdrExport()
    {
        $field = 'cdr.operatorid';
        if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
        {
            $columns = array('uniqueid','did_no','name','number' ,'datetime','firstleg','secondleg','cdr.status as status','creditused','deptname','opername' ,'phonenumber');
        }
        elseif(Auth::user()->usertype ==  'groupadmin')
        {
            $columns = array('uniqueid','did_no','number' ,'datetime','firstleg','secondleg', 'cdr.status as status','creditused','deptname','tag','A.opername as opername','B.opername as assignedto');
        }
        elseif(Auth::user()->usertype ==  'operator')
        {
            $columns = array('uniqueid','did_no','number' ,'datetime','firstleg','secondleg','cdr.status as status','creditused','deptname','tag','opername');
            $field = 'cdr.assignedto';
        }
        $data = CdrReport::select($columns)
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr.resellerid')
            ->leftJoin('operatoraccount AS A', 'A.id', '=', $field);
        if(Auth::user()->usertype ==  'reseller')
        {
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }
        elseif(Auth::user()->usertype ==  'groupadmin')
        {
            
            $data ->leftJoin('operatoraccount AS B', 'B.id', '=', 'cdr.assignedto');
            $data->where('cdr.groupid',Auth::user()->groupid);
        }
        elseif(Auth::user()->usertype ==  'operator')
        {
            $data->where('cdr.operatorid',Auth::user()->id);
        }
        return $data->orderBy('datetime','DESC')->get();
       // return  $data ->get();
    }
}
