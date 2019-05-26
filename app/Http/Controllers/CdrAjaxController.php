<?php
namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CdrReport;
use App\Models\Contact;
use App\Models\CdrTag;
use App\Models\Cdr;
use App\Models\CdrSub;

use App\Models\CdrArchive;
use App\Models\CdrPbx;
use App\Models\OperatorAccount;
use App\Models\VoiceEmail;
use App\Models\Blacklist;
use App\Models\Holiday;
use App\Models\Conference;
use App\Models\CurChannelUsed;
use App\Models\OperatorDepartment;

class CdrAjaxController extends Controller

{
    public function getForm(Request $request)
    {

        if ( $request->ajax() ) {
            $data = $request->all();
            if ( !isset( $data[ 'viewfile' ] ) ) {
                $arr[ 'error' ]    = 1;
                $arr[ 'errormsg' ] = 'Please enter required fields';
                return response()->json( $arr );
                exit( );
            }

            $array[ 'id' ] = $data[ 'id' ];
            if($data[ 'viewfile' ] == 'cdr.tag'){
                $array['tags'] =   CdrTag::getTag();
            }

            if($data[ 'viewfile' ] == 'cdr.callhistory'){
                $array['callhistory'] =   CdrSub::getCdrSub($data[ 'id' ]);
            }
            $html               = view( $data[ 'viewfile' ], $array );
            $arr[ 'view' ]      = $html->__toString();
        }
        $arr[ 'success' ] = 1;
        return response()->json( $arr );
        exit( );

    }
    public function addContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        if($data = Contact::getContactsByNumber($request->Input()['rowid'])){
            Contact::updateContact($request->input(),$data->id);
        }
        else{
            Contact::InsertContact( $request->Input() );
        }


        return response()->json(['success'=>'Record is successfully added']);

    }

    public function addTag(Request $request){
        $validator = Validator::make($request->all(), [
            'tagid' => 'required',
            'cdrid' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        if($data = CdrTag::getTagFromId($request->Input()['tagid'])){
            Cdr::updateTag(    $request->input(),$data->tag);
        }


        return response()->json(['success'=>'Record is successfully added']);
    }

    public function addReminder(Request $request){
        $validator = Validator::make($request->all(), [
            'startdate' => 'required',
            'Timepicker' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        if($data = Cdr::getCdrFromId($request->Input()['cdrid'])){
            $date = date('Y-m-d', strtotime($request->Input()['startdate']));
            $time = date('H:i', strtotime($request->Input()['Timepicker']));
            $newdate = $date.' '.$time;

            Reminder::insertReminder(   $data,$newdate);
        }


        return response()->json(['success'=>'Record is successfully added']);
    }

    public function getreportsearch(Request $request)
    {

        if ( $request->ajax() ) {
            $data = $request->all();
            
            $array = array('result' => CdrReport::getReport_search($data));
            $html               = view( 'home.cdrreport_ajax', $array );
            $arr[ 'view' ]      = $html->__toString();
        }
        $arr[ 'success' ] = 1;
        return response()->json( $arr );
        exit( );

    }

    public function getcdroutsearch(Request $request)
    {
        if ( $request->ajax() ) {
            $data = $request->all();
            
            $array = array('result' => CdrPbx::getReport_search($data));
            $html               = view( 'home.cdrreport_ajax', $array );
            $arr[ 'view' ]      = $html->__toString();
        }
        $arr[ 'success' ] = 1;
        return response()->json( $arr );
        exit( );

    }
     public function getvoicesearch(Request $request)
    {
        if ( $request->ajax() ) {
            $data = $request->all();
            
            $array = array('result' => VoiceEmail::getReport($data));
            $html               = view( 'home.voicemail_ajax', $array );
            $arr[ 'view' ]      = $html->__toString();
        }
        $arr[ 'success' ] = 1;
        return response()->json( $arr );
        exit( );

    }
}