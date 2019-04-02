<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CdrReport;
use App\Models\Contact;


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
        Contact::InsertContact( $request->Input( ) );

        return response()->json(['success'=>'Record is successfully added']);

    }

}