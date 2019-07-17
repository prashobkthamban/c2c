<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
        $rules     = array(
            'username' => 'required',
            'password' => 'required'
        );
        $validator = Validator::make( $request->all(), $rules );
        if ( $validator->fails() ) {
            return Redirect::to( 'login' )->withErrors( $validator )->withInput();
        } else {
            $credentials = $request->only( 'username', 'password' );
            $user = Account::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

            //dd($user);

            if ( $user ) {
                Auth::loginUsingId($user->id);
                // Authentication passed...
                return redirect()->intended( '/' );
            } else {
                // dd(Auth::attempt( $credentials ));
                // dd(Auth::check());die;
                return Redirect::to( 'login' )->withErrors( array(
                    'username' => 'Invalid credentials'
                ) )->withInput();
            }
        }
    }
}