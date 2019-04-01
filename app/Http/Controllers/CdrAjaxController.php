<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;



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

            $array[ 'headertitle' ] = 'ss';
            $html               = view( $data[ 'viewfile' ], $array );
            $arr[ 'view' ]      = $html->__toString();
        }
        $arr[ 'success' ] = 1;
        return response()->json( $arr );
        exit( );

    }

}