<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CdrReport extends Model
{
    protected $table = 'cdr';

    // public static function getReport( ){

    //    $data = CdrReport::select('cdr.*','fname','lname','contacts.email','name','resellername','opername','phonenumber','cdr_notes.operator', 'cdr_notes.note', 'cdr_notes.datetime as note_time')
    //         ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
    //         ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr.resellerid')
    //         ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid')
    //         ->leftJoin('contacts', 'contacts.phone', '=', 'cdr.number')
    //         ->leftJoin('cdr_notes', 'cdr_notes.uniqueid', '=', 'cdr.uniqueid');
    //     if( Auth::user()->usertype == 'reseller'){
    //         $data->where('cdr.resellerid',Auth::user()->resellerid );
    //     }
    //     $result = $data->orderBy('datetime','DESC')
    //     ->paginate(30);
    //     return $result;
    // }

    public function cdrNotes()
    {
        return $this->hasMany('App\Models\CdrNote', 'uniqueid', 'uniqueid');
    }
    
    public function operatorAccount() {
        return $this->hasOne('App\Models\OperatorAccount', 'id', 'operatorid');
    }

    public function accountGroup() {
        return $this->hasOne('App\Models\Accountgroup', 'id', 'groupid');
    }
    
    public function operatorAssigned() {
        return $this->hasOne('App\Models\OperatorAccount', 'id', 'assignedto');
    }

    public function contacts()
    {
        return $this->hasOne('App\Models\Contact', 'phone', 'number');
    }

    public function reminder()
    {
        return $this->hasOne('App\Models\Reminder', 'uniqueid', 'uniqueid');
    }

    public static function getReport($groupId = '', $department = '', $operator = '', $tag = '', $status = '', $assigned_to = '', $did_no = '', $caller_number = '', $date = '', $start_date = '', $end_date = ''){
       $data = CdrReport::with(['cdrNotes', 'contacts', 'reminder', 'operatorAccount', 'operatorAssigned', 'accountGroup']);
        if( Auth::user()->usertype == 'reseller' && !empty(Auth::user()->reseller->associated_groups) ){
            $data->whereIn('cdr.groupid', json_decode(Auth::user()->reseller->associated_groups));
        } 
        else if( Auth::user()->usertype == 'reseller' ) {
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }
        else if( Auth::user()->usertype == 'operator' ){
            $data->where('cdr.operatorid',Auth::user()->operator_id );
        } else if(Auth::user()->usertype == 'admin') {
            $data->select('cdr.*', 'accountgroup.name')->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid');
        } else if(Auth::user()->usertype == 'groupadmin') {
            //dd(Auth::user()->usertype);
            $data->where('cdr.groupid',Auth::user()->groupid );
        }
        if(!empty($groupId)) {
            $data->where('cdr.groupid',$groupId);
        }
        if(!empty($department)) {
            $data->where('cdr.deptname',$department);
        }
        if(!empty($operator)) {
            $data->where('cdr.operatorid',$operator);
        }
        if(!empty($tag)) {
            $data->where('cdr.tag',$tag);
        }
        if(!empty($status)) {
            $data->where('cdr.status',$status);
        }
        if(!empty($assigned_to)) {
            $data->where('cdr.assignedto',$assigned_to);
        }
        if(!empty($did_no)) {
            $data->where('cdr.did_no',$did_no);
        }
        if(!empty($caller_number)) {
            $data->where('cdr.number', 'like', '%' . trim($caller_number) . '%');
        }
        if(!empty($date)) {
            $fromDate = date('Y-m-d') . ' 00:00:00';
            $toDate = date('Y-m-d') . ' 23:59:59';
            if($date == 'yesterday') {
                $yesterday = date('Y-m-d',strtotime("-1 days"));
                $fromDate = $yesterday . ' 00:00:00';
                $toDate = $yesterday . ' 23:59:59';
            } else if($date == 'week') {
                $fromDate =date('Y-m-d',strtotime("-1 days")) . ' 00:00:00';
            } else if($date == 'week') {
                $fromDate =date('Y-m-d',strtotime("-1 week")) . ' 00:00:00';
            } else if($date == 'month') {
                $fromDate =date('Y-m-d',strtotime("-1 month")) . ' 00:00:00';
            } else if($date == 'custom') {
                $fromDate = date('Y-m-d',strtotime($start_date)) . ' 00:00:00';
                $toDate = date('Y-m-d',strtotime($end_date)) . ' 23:59:59';
            }
            $data->whereBetween('cdr.datetime',[$fromDate, $toDate]);
        }
        
        $result = $data->orderBy('cdr.datetime','DESC')->get();
       //dd($result);
       return $result;
    }

    public static function getGraphReport($params){
        $data = CdrReport::select('cdr.status', DB::raw('DATE(cdr.datetime) as newdate'), DB::raw('count(cdr.cdrid) as Count'));
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }
        else if( Auth::user()->usertype == 'operator' ){
            $data->where('cdr.operatorid',Auth::user()->operator_id );
        } else if(Auth::user()->usertype == 'groupadmin') {
            $data->where('cdr.groupid',Auth::user()->groupid );
        }
        
        if(!empty($params['startdate']) && !empty($params['enddate'])) {
            $data->whereBetween('datetime', [Carbon::parse($params['startdate'])->format('Y-m-d'), Carbon::parse($params['enddate'])->format('Y-m-d')]);
        }

        if(!empty($params['status'])){
            $data->where('cdr.status', $params['status'] );
        }

        if(!empty($params['department'])){
            $data->where('cdr.deptname', $params['department'] );
        }

        $result = $data->groupBy('newdate')->groupBy('status')->get();
        return $result;
    }

    public static function getContact( $rowid ){
        return CdrReport::select( 'number' )->where( 'cdr.cdrid', $rowid )
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid')
            ->first( );
    }

    public static function getReport_search($post_data )
    {
        //dd($post_data);
        $data = CdrReport::select('cdr.*','name','resellername','opername','phonenumber')  
            ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
            ->leftJoin('resellergroup', 'resellergroup.id', '=', 'cdr.resellerid')
            ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid');
        
        if(isset($post_data['department']) && $post_data['department'] != '')
        {
            $data->where('cdr.deptname', $post_data['department']);
        }
        if(isset($post_data['operator']) && $post_data['operator'] != '')
        {
             $data->where('cdr.operatorid',$post_data['operator'] );
        }
        if(isset($post_data['assigned_to']) && $post_data['assigned_to'] != '')
        {
             $data->where('cdr.assignedto', $post_data['assigned_to'] );
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
      
        if(isset($post_data['did_no']) && $post_data['did_no'] != '')
        {
            $data->where('cdr.did_no',$post_data['did_no'] );
        }
        if(isset($post_data['tags']) && $post_data['tags'] != '')
        {
            $data->where('cdr.tag','LIKE','%' .$post_data['tags'].'%'  );
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
        //dd($result);
        return $result;
    }

    public static function getstatus($groupId) {
        $data = CdrReport::select( 'status' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }    
        $data->where('cdr.groupid', $groupId);
        return $data->distinct()->get( );
    }
//
    public static function getdids($groupId) {
        $data = CdrReport::select( 'did_no' ) ;
        if( Auth::user()->usertype == 'reseller'){
            $data->where('cdr.resellerid',Auth::user()->resellerid );
        }    
        $data->where('cdr.groupid', $groupId);
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
