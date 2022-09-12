<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CdrArchive;


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

    public static function getReport($groupId = '', $department = '', $operator = '', $tag = '', $status = '', $assigned_to = '', $did_no = '', $caller_number = '', $date = '', $start_date = '', $end_date = '', $searchText = '', $sortOrderArray = [], $limit = 0, $skip = 0, $draw = 1) {
        $groupIdArray = [];
        if(!empty($groupId)) {
            $groupIdArray = [$groupId];
        }
        $userType = Auth::user()->usertype;
        $data = CdrReport::with(['cdrNotes', 'reminder', 'operatorAssigned'])
                ->select('cdr.*', 'accountgroup.name as customerName', 'operatoraccount.opername as operatorName')
                ->leftJoin('accountgroup', 'accountgroup.id', '=', 'cdr.groupid')
                ->leftJoin('operatoraccount', 'operatoraccount.id', '=', 'cdr.operatorid');
        if( $userType == 'reseller') {
            if(empty($groupId)) {
                $groupIdArray = getResellerGroupAdminIds(Auth::user()->resellerid);
            }
        } else if( $userType == 'operator' ){
            $data->where('cdr.operatorid',Auth::user()->operator_id );
        } else if($userType == 'admin') {

        } else if($userType == 'groupadmin') {
            if(empty($groupId)) {
                $groupIdArray = [Auth::user()->groupid];
            }
        }
        if(!empty($groupIdArray)) {
            $data->whereIn('cdr.groupid', $groupIdArray);
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
        $recordsTotal = $data->count();
        // if(!empty($searchText)) {
        //     $searchText = strtolower(trim($searchText));
        //     $data->where('cdr.number', 'like', '%' . trim($searchText) . '%')
        //     ->orWhere(DB::raw('lower(contacts.fname)'), 'like', '%' . $searchText . '%')
        //     ->orWhere(DB::raw('lower(contacts.lname)'), 'like', '%' . $searchText . '%')
        //     ->orWhere('cdr.datetime', 'like', '%' . trim($searchText) . '%')
        //     ->orWhere(DB::raw('lower(cdr.status)'), 'like', '%' . $searchText . '%')
        //     ->orWhere(DB::raw('lower(cdr.deptname)'), 'like', '%' . $searchText . '%')
        //     ->orWhere(DB::raw('lower(operatoraccount.opername)'), 'like', '%' . $searchText . '%')
        //     ;
        // }
        $recordsFiltered = $data->count();

        if (count($sortOrderArray) > 0) {
            foreach ($sortOrderArray as $field => $order) {
                $data->orderBy($field, $order);
            }
        }

        if ($limit > 0) {
            $data->skip($skip)
                ->take($limit);
        }
        $results = $data->get();
        // dd($results);

        $data = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $results
        ];
        return $data;
    }

    public static function getReportAjax($groupId = '', $department = '', $operator = '', $tag = '', $status = '', $assigned_to = '', $did_no = '', $caller_number = '', $date = '', $start_date = '', $end_date = '', $fetchArchive = false, $searchText = '', $sortOrderArray = [], $limit = 0, $skip = 0, $draw = 1) {

        if ($fetchArchive) {
            $data = CdrArchive::getReport($groupId, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date, $searchText, $sortOrderArray, $limit, $skip, $draw);
        } else {
            $data = CdrReport::getReport($groupId, $department, $operator, $tag, $status, $assigned_to, $did_no, $caller_number, $date, $start_date, $end_date, $searchText, $sortOrderArray, $limit, $skip, $draw);
        }
        $dataArray = [];
        if(count($data['data']) > 0) {
            foreach($data['data'] as $result) {
                $cdrSubCount = DB::table('cdr_sub')
                                ->leftJoin('operatoraccount', 'cdr_sub.operator', 'operatoraccount.id')
                                ->where('cdr_sub.cdr_id', $result->cdrid)
                                ->where('operatoraccount.groupid', Auth::user()->groupid)
                                ->count();
                $contact = DB::table('contacts')
                                ->where('phone', $result->number)
                                ->where('groupid', $result->groupid)
                                ->first();
                $dataArray[] = [
                    'userType' => Auth::user()->usertype,
                    'cdrId' => $result->cdrid,
                    'uniqueId' => $result->uniqueid,
                    'groupId' => $result->groupid,
                    'customerName' => $result->customerName,
                    'callerId' => $contact ? $contact->fname . ' ' . $contact->lname : $result->number,
                    'number' => $result->number,
                    'dateTime' => $result->datetime,
                    'totalTime' => $result->firstleg,
                    'talkTime' => $result->secondleg,
                    'duration' => $result->firstleg. '(' .$result->secondleg. ')',
                    'creditUsed' => $result->creditused,
                    'status' => $result->status,
                    'cdrSubCount' => $cdrSubCount,
                    'departmentName' => $result->deptname,
                    'operatorName' => $result->operatorName,
                    'tag' => $result->tag,
                    'assignedOperatorName' => $result->operatorAssigned ? $result->operatorAssigned->opername : '',
                    'didNumber' => $result->did_no,
                    'recordedFileName' => $result->recordedfilename,
                    'cdrNotesCount' => count($result->cdrNotes),
                    'isContactSet' => !empty($contact) ? true : false,
                    'contactId' => $contact ? $contact->id : '',
                    'email' => $contact ? $contact->email : '',
                    'firstName' => $contact ? $contact->fname : '',
                    'lastName' => $contact ? $contact->lname : '',
                    'fullName' => $contact ? $contact->fname . ' ' . $contact->lname : '',
                    'isReminderSet' => !empty($result->reminder) ? true : false
                ];
            }
        }
        $data["data"] = $dataArray;
        return $data;
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
