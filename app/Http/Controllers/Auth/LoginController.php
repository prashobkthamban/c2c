<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $guard = 'account';
    protected $username = 'username';
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( )
    {
        $this->middleware( 'guest:account' )->except( 'logout' );
    }
    public function showLoginForm( )
    {
        return view( 'auth.login' );
    }
    public function login( Request $request )
    {
        // $password = Hash::make(123456);
        // dd($password);
        $rules     = array(
            'username' => 'required',
            'password' => 'required'
        );
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            $log = [
                'login_time' => date('Y-m-d H:i:s'),
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'groupid' => null,
                'ipaddress' => $request->ip(),
                'usertype' => null,
                'status' => 'FAILED'
            ];
            DB::table('ast_login_log')->insert($log);
            return Redirect::to( 'login' )->withErrors( $validator )->withInput();
        } else {
            $credentials = $request->only( 'username', 'password' );

            if (  Auth::attempt( $credentials ) ) {
                // Authentication passed...
                $log = [
                    'login_time' => date('Y-m-d H:i:s'),
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                    'groupid' => Auth::user()->groupid,
                    'ipaddress' => $request->ip(),
                    'usertype' => Auth::user()->usertype,
                    'status' => 'SUCCESS'
                ];
                DB::table('ast_login_log')->insert($log);
                return redirect()->intended( '/' );
            } else {
                $log = [
                    'login_time' => date('Y-m-d H:i:s'),
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                    'groupid' => null,
                    'ipaddress' => $request->ip(),
                    'usertype' => null,
                    'status' => 'FAILED'
                ];
                DB::table('ast_login_log')->insert($log);
                return Redirect::to( 'login' )->withErrors( array(
                    'username' => 'Invalid credentials'
                ) )->withInput();
            }
        }
    }
}
