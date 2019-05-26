<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CdrPbx extends Model
{
    protected $table = 'cdrpbx';

    public static function getReport( ){

        $data = CdrPbx::select('cdrpbx.*','name','resellername','opername','phonenumber')
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdrpbx.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdrpbx.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdrpbx.operatorid');
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }
        elseif( Auth::user()->usertype == 'operator'){
            $data->where('cdrpbx.operatorid',Auth::user()->resellerid );
        }
        else{
            //$data->where('cdrpbx.groupid',Auth::user()->groupid );
        }
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }
        $result = $data->orderBy('datetime','DESC')
            ->paginate(30);
        return $result;
    }

    public static function get_dept_by_group()
    {
        
        $data = CdrPbx::select( 'deptname' ) ;
       // if( Auth::user()->usertype == 'operator'){
            $data->where('cdrpbx.groupid',Auth::user()->groupid );
       // }  
        return $data->distinct()->get( );
    }


    public static function getReport_search($post_data )
    {
         $data = CdrPbx::select('cdrpbx.*','name','resellername','opername','phonenumber')
              
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdrpbx.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdrpbx.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdrpbx.operatorid');
        
        if(isset($post_data['department']) && $post_data['department'] != '')
        {
            $data->where('cdrpbx.deptname','LIKE','%' .$post_data['department'].'%'  );
        }
        if(isset($post_data['operator']) && $post_data['operator'] != '')
        {
             $data->where('cdrpbx.operatorid',$post_data['operator'] );
        }
        if(isset($post_data['assigned_to']) && $post_data['assigned_to'] != '')
        {
             $data->where('cdrpbx.assignedto',$post_data['operator'] );
        }
        if(isset($post_data['caller_number']) && $post_data['caller_number'] != '')
        {
            $data->where('cdrpbx.number','LIKE','%' .$post_data['caller_number'].'%'  );
        }
        if(isset($post_data['status']) && $post_data['status'] != '')
        {
            $data->where('cdrpbx.status','LIKE','%' .$post_data['status'].'%'  );
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
                $data->where('cdrpbx.datetime','>',$startdate.' 00:00:00');   
            }
            if($enddate != '')
            {
                $data->where('cdrpbx.datetime','<',$enddate.' 23:59:59');   
            }
        }
        //
        if(isset($post_data['did_no']) && $post_data['did_no'] != '')
        {
            $data->where('cdrpbx.status',$post_data['did_no'] );
        }
        if(isset($post_data['tags']) && $post_data['tags'] != '')
        {
            $data->where('cdrpbx.tag','LIKE','%' .$post_data['tags'].'%'  );
        }
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }
        
        $result = $data->orderBy('datetime','DESC')
        ->paginate(30);
        return $result;
    }

    public static function getstatus( ){
        $data = CdrPbx::select( 'status' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }    
        $data->where('cdrpbx.groupid',Auth::user()->groupid);
         return $data->distinct()->get( );
    }
//
    public static function getdids( ){
        $data = CdrPbx::select( 'did_no' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }    
        $data->where('cdrpbx.groupid',Auth::user()->groupid);
         return $data->distinct()->get( );
    }


    public static function cdroutExport()
    {
        $field = 'cdrpbx.operatorid';
        if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
        {
            $columns = array('uniqueid','did_no','name','number' ,'datetime','firstleg','secondleg','cdrpbx.status as status','creditused','deptname','opername' ,'phonenumber');
        }
        elseif(Auth::user()->usertype ==  'groupadmin')
        {
            $columns = array('uniqueid','did_no','number' ,'datetime','firstleg','secondleg', 'cdrpbx.status as status','creditused','deptname','A.opername as opername','B.opername as assignedto');
            
        }
        elseif(Auth::user()->usertype ==  'operator')
        {
            $columns = array('uniqueid','did_no','number' ,'datetime','firstleg','secondleg','cdrpbx.status as status','creditused','deptname','opername');
            $field = 'cdrpbx.assignedto';
        }
        $data = CdrPbx::select($columns)
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdrpbx.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdrpbx.resellerid')
            ->leftJoin('operatoraccount AS A', 'A.id', '=', $field);
        if(Auth::user()->usertype ==  'reseller')
        {
            $data->where('cdrpbx.resellerid',Auth::user()->resellerid );
        }
        elseif(Auth::user()->usertype ==  'groupadmin')
        {
            
            $data ->leftJoin('operatoraccount AS B', 'B.id', '=', 'cdrpbx.assignedto');
            $data->where('cdrpbx.groupid',Auth::user()->groupid);
        }
        elseif(Auth::user()->usertype ==  'operator')
        {
            $data->where('cdrpbx.operatorid',Auth::user()->id);
        }
        return $data->orderBy('datetime','DESC')->get();
       // return  $data ->get();
    }
}
