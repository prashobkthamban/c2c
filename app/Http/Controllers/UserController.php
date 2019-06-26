<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

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
        $user = new Users();
        $lang = $user->get_language();
        //dd($lang);
        return view('user.add_user', compact('lang'));
    }

    public function store(Request $request)
    {
        $user = new Users();
        $lang = $user->get_language();
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'coperate_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'operator_call_count' => 'required|integer|min:0',
            'sms_api_user' => 'required',
            'sms_api_password' => 'required',
            'sms_api_sender' => 'required',
            'cdr_api_key' => 'required',
            'api' => 'required',
            'client_ip' => 'required',
            'conference_members' => 'required|integer|min:0',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            return view('user.add_user', compact('messages', 'lang'));
        } else {
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
        toastr()->success('User added successfully.');
        } 
        return redirect()->route('UserList');
        
    }

    public function edit($id)
    {
        $user = new Users();
        $user_edit = $user->findOrFail($id);        
        $lang = $user->get_language();

        return view('user.edit_user', compact('user_edit','lang'));
    }

    public function update($id, Request $request)
    {

        $user = new Users();
        $lang = $user->get_language();
        $user_edit = $user->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'coperate_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'operator_call_count' => 'required|integer|min:0',
            'sms_api_user' => 'required',
            'sms_api_sender' => 'required',
            'cdr_api_key' => 'required',
            'api' => 'required',
            'client_ip' => 'required',
            'conference_members' => 'required|integer|min:0',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            return view('user.edit_user', compact('messages', 'lang', 'user_edit'));
        } else {
            //dd($user_edit);
            $users = array(
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
                'sms_api_password'=> !empty($request->get('sms_api_password')) ? $request->get('sms_api_password') : $user_edit->sms_api_password,
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
            );
        
            $user_edit->fill($users)->save();
            toastr()->success('User update successfully.');
            return redirect()->route('UserList');
        }
        
    }

    public function destroy($id)
    {
        $user= Users::find($id);
        //dd($user);
        //dd($user123);
        $user->delete();
        toastr()->success('User delete successfully.');
        return redirect()->route('UserList');
    }
}
