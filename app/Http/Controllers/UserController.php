<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // if(!Auth::check()){
        // return redirect('login');
        // }

    }

    public function index() {
        $users = Users::all();
        //dd($users);
        return view('user.user_list', compact('users'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addUser()
    {
        return view('user.add_user');
    }

    public function store(Request $request)
    {

        $request->validate([
            'customer_name'=>'required',
            'coperate_id'=> 'required'
        ]);
  
        $users = new Users([
            'customer_name' => $request->get('customer_name'),
            'coperate_id'=> $request->get('coperate_id'),
            'start_date'=> Carbon::parse($request->get('start_date'))->format('Y-m-d'),
            'end_date'=> Carbon::parse($request->get('end_date'))->format('Y-m-d'),
            'status'=> $request->get('status'),
            'did'=> $request->get('did'),
            'multilanguage'=> $request->get('multilanguage'),
            'language'=> $request->get('language'),
            'record_call'=> $request->get('record_call'),
            'operator_call_count'=> $request->get('operator_call_count'),
            'sms_api_user'=> $request->get('sms_api_user'),
            'sms_api_password'=> $request->get('sms_api_password'),
            'sms_api_sender'=> $request->get('sms_api_sender'),
            'api'=> $request->get('api'),
            'cdr_api_key'=> $request->get('cdr_api_key'),
            'client_ip'=> $request->get('client_ip'),
            'cdr_tag'=> $request->get('cdr_tag'),
            'chanunavil_calls'=> $request->get('chanunavil_calls'),
            'conference_members'=> $request->get('conference_members'),
            'android_app'=> $request->get('android_app'),
            'portal_sms'=> $request->get('portal_sms'),
            'dial_stratergy'=> $request->get('dial_stratergy'),
            'sms_support'=> $request->get('sms_support'),
            'push_api_service'=> $request->get('push_api_service'),
            'pbx_extension'=> $request->get('pbx_extension')
        ]);

        //dd($users);
        $users->save();
        
        return redirect('/users')->with('success', 'User has been added.');
        
    }

    public function edit($id)
    {
        //dd($id);
        $user = Users::findOrFail($id);
        return view('user.edit_user')->withUser($user);
    }

    public function update($id, Request $request)
    {
        $user = Users::findOrFail($id);
        dd('ds');
        // $this->validate($request, [
        //     'title' => 'required',
        //     'description' => 'required'
        // ]);

        // $input = $request->all();

        // $user->fill($input)->save();

        //Session::flash('flash_message', 'Task successfully added!');

        //return redirect()->back();
    }

    public function destroy($id)
    {
        $user= Users::find($id);
        $user->delete();
        return redirect('/adduser')->with('success', 'User has been deleted.');
    }
}
