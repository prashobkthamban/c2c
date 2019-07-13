<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use App\Models\Dids;
use App\Models\Accountgroup;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        $users = DB::table('Accountgroup')
            ->join('resellergroup', 'Accountgroup.resellerid', '=', 'resellergroup.id')
            ->select('Accountgroup.*', 'resellergroup.resellername')
            ->get();
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
        $account_group = new Accountgroup();
        $did = new Dids();
        $lang = $account_group->get_language();
        $lang = $lang->prepend('Select language', '0');
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $sms_gateway = $account_group->sms_api_gateway();
        $sms_gateway = $sms_gateway->prepend('Select gateway', '0');
        $did_list = $did->get_did();
        $did_list = $did_list->prepend('Select Did', '0');
        //dd($did_list);
        return view('user.add_user', compact('lang', 'coperate', 'default', 'did_list', 'sms_gateway'));
    }

    public function store(Request $request)
    {
        $account_group = new Accountgroup();
        $did = new Dids();
        $lang = $account_group->get_language();
        $lang = $lang->prepend('Select language', '0');
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $did_list = $did->get_did();
        $did_list = $did_list->prepend('Select Did', '0');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'did' => 'required',
            'resellerid' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'lang_file' => 'required',
            'try_count' => 'required|integer|min:0',
            'dial_time' => 'required|integer|min:0',
            'maxcall_dur' => 'required|integer|min:0',
            'c2c_channels' => 'required',
            'c2cAPI' => 'required',
            'sms_api_gateway_id' => 'required',
            'sms_api_user' => 'required',
            'sms_api_pass' => 'required',
            'sms_api_senderid' => 'required',
            'cdr_apikey' => 'required',
            'API' => 'required',
            'ip' => 'required',
            'max_no_confrence' => 'required|integer|min:0',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            return view('user.add_user', compact('messages', 'lang', 'coperate', 'did_list'));
        } else {
            $account_group = new Accountgroup([
                'name' => $request->get('name'),
                'resellerid'=> $request->get('resellerid'),
                'startdate'=> Carbon::parse($request->get('startdate'))->format('Y-m-d'),
                'enddate'=> Carbon::parse($request->get('enddate'))->format('Y-m-d'),
                'status'=> $request->get('status'),
                'did'=> $request->get('did'),
                'lang_file'=> $request->get('lang_file'),
                'multi_lang'=> $request->get('multi_lang'),
                'record_call'=> $request->get('record_call'),
                'try_count'=> $request->get('try_count'),
                'dial_time'=> $request->get('dial_time'),
                'maxcall_dur'=> $request->get('maxcall_dur'),
                'operator_no_logins'=> $request->get('operator_no_logins'),
                'no_channels'=> $request->get('no_channels'),
                'emailservice_assign_cdr'=> $request->get('emailservice_assign_cdr'),
                'smsservice_assign_cdr'=> $request->get('smsservice_assign_cdr'),
                'c2c_channels'=> $request->get('c2c_channels'),
                'c2cAPI'=> $request->get('c2cAPI'),
                'operator_dpt'=> $request->get('operator_dpt'),
                'sms_api_gateway_id'=> $request->get('sms_api_gateway_id'),
                'sms_api_user'=> $request->get('sms_api_user'),
                'sms_api_pass'=> $request->get('sms_api_pass'),
                'sms_api_senderid'=> $request->get('sms_api_senderid'),
                'API'=> $request->get('API'),
                'cdr_apikey'=> $request->get('cdr_apikey'),
                'ip'=> $request->get('ip'),
                'cdr_tag'=> $request->get('cdr_tag'),
                'cdr_chnunavil_log'=> $request->get('cdr_chnunavil_log'),
                'max_no_confrence'=> $request->get('max_no_confrence'),
                'servicetype'=> $request->get('servicetype'),
                'andriodapp'=> $request->get('andriodapp'),
                'web_sms'=> $request->get('web_sms'),
                'dial_statergy'=> $request->get('dial_statergy'),
                'sms_support'=> $request->get('sms_support'),
                'pushapi'=> $request->get('pushapi'),
                'pbxexten'=> $request->get('pbxexten'),
                'c2c'=> $request->get('c2c')
            ]);

        //dd($users);
        $account_group->save();
        toastr()->success('User added successfully.');
        } 
        return redirect()->route('UserList');
        
    }

    public function edit($id)
    {
        //$user = new Users();
        $account_group = new Accountgroup();
        $did = new Dids();
        $user_edit = $account_group->findOrFail($id);        
        $lang = $account_group->get_language();
        $lang = $lang->prepend('Select language', '0');
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $sms_gateway = $account_group->sms_api_gateway();
        $sms_gateway = $sms_gateway->prepend('Select gateway', '0');
        $did_list = $did->get_did();
        $did_list = $did_list->prepend('Select Did', '0');
        return view('user.edit_user', compact('user_edit','lang', 'coperate', 'did_list', 'sms_gateway'));
    }

    public function update($id, Request $request)
    {
        //dd($id);
        $account_group = new Accountgroup();
        $did = new Dids();
        $lang = $account_group->get_language();
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $sms_gateway = $account_group->sms_api_gateway();
        $sms_gateway = $sms_gateway->prepend('Select gateway', '0');
        $did_list = $did->get_did();
        $did_list = $did_list->prepend('Select Did', '0');
        $user_edit = $account_group->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'did' => 'required',
            'resellerid' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'lang_file' => 'required',
            'try_count' => 'required|integer|min:0',
            'dial_time' => 'required|integer|min:0',
            'maxcall_dur' => 'required|integer|min:0',
            'c2c_channels' => 'required',
            'c2cAPI' => 'required',
            'sms_api_gateway_id' => 'required',
            'sms_api_user' => 'required',
            'sms_api_pass' => 'required',
            'sms_api_senderid' => 'required',
            'cdr_apikey' => 'required',
            'API' => 'required',
            'ip' => 'required',
            'max_no_confrence' => 'required|integer|min:0',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
           // dd($messages = $validator->messages());
            return view('user.edit_user', compact('messages', 'lang', 'user_edit', 'coperate', 'sms_gateway', 'did_list'));
        } else {
            $account_group = [
                'name' => $request->get('name'),
                'resellerid'=> $request->get('resellerid'),
                'startdate'=> Carbon::parse($request->get('startdate'))->format('Y-m-d'),
                'enddate'=> Carbon::parse($request->get('enddate'))->format('Y-m-d'),
                'status'=> $request->get('status'),
                'did'=> $request->get('did'),
                'lang_file'=> $request->get('lang_file'),
                'multi_lang'=> $request->get('multi_lang'),
                'record_call'=> $request->get('record_call'),
                'try_count'=> $request->get('try_count'),
                'dial_time'=> $request->get('dial_time'),
                'maxcall_dur'=> $request->get('maxcall_dur'),
                'operator_no_logins'=> $request->get('operator_no_logins'),
                'no_channels'=> $request->get('no_channels'),
                'emailservice_assign_cdr'=> $request->get('emailservice_assign_cdr'),
                'smsservice_assign_cdr'=> $request->get('smsservice_assign_cdr'),
                'c2c_channels'=> $request->get('c2c_channels'),
                'c2cAPI'=> $request->get('c2cAPI'),
                'operator_dpt'=> $request->get('operator_dpt'),
                'sms_api_gateway_id'=> $request->get('sms_api_gateway_id'),
                'sms_api_user'=> $request->get('sms_api_user'),
                'sms_api_pass'=> $request->get('sms_api_pass'),
                'sms_api_senderid'=> $request->get('sms_api_senderid'),
                'API'=> $request->get('API'),
                'cdr_apikey'=> $request->get('cdr_apikey'),
                'ip'=> $request->get('ip'),
                'cdr_tag'=> $request->get('cdr_tag'),
                'cdr_chnunavil_log'=> $request->get('cdr_chnunavil_log'),
                'max_no_confrence'=> $request->get('max_no_confrence'),
                'servicetype'=> $request->get('servicetype'),
                'andriodapp'=> $request->get('andriodapp'),
                'web_sms'=> $request->get('web_sms'),
                'dial_statergy'=> $request->get('dial_statergy'),
                'sms_support'=> $request->get('sms_support'),
                'pushapi'=> $request->get('pushapi'),
                'pbxexten'=> $request->get('pbxexten'),
                'c2c'=> $request->get('c2c')
            ];
            //dd($account_group);
            $user_edit->fill($account_group)->save();
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
