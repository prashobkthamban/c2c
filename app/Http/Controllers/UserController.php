<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use App\Models\Dids;
use App\Models\Accountgroup;
use App\Models\Account;
use App\Models\OperatorAccount;
use App\Models\OperatorDepartment;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

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
        $users = DB::table('accountgroup')
            ->join('resellergroup', 'accountgroup.resellerid', '=', 'resellergroup.id')
            ->select('accountgroup.*', 'resellergroup.resellername')
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
        //dd($user_edit);   
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
            //dd($messages = $validator->messages());
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
        $user->delete();
        toastr()->success('User delete successfully.');
        return redirect()->route('UserList');
    }

    /* ----------login account----------- */
    public function loginAccounts() {
        $account_group = new Accountgroup();
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');

        $query = DB::table('account')
             ->leftJoin('accountgroup', 'account.groupid', '=', 'accountgroup.id')
             ->leftJoin('resellergroup', 'account.resellerid', '=', 'resellergroup.id');
        if(Auth::user()->usertype == 'admin') {
        } elseif(Auth::user()->usertype == 'reseller') {
           $query->where('resellerid', Auth::user()->usertype);
        } elseif(Auth::user()->usertype == 'groupadmin') {
           $query->where('groupid', Auth::user()->groupid);
           $query->where('usertype', 'groupadmin');
        } else {
            $query->where('groupid', Auth::user()->groupid);
        }
        $query->select('account.*', 'accountgroup.name', 'resellergroup.resellername');
        $accounts = $query->get();
        //dd($accounts);
        return view('user.account_list', compact('accounts', 'coperate'));
    }

    public function editAccount($id = null) {
        $account = new Account();
        return $account->findOrFail($id);     
        
    }

    public function addAccount(Request $request) {
        // $add_account = [
        //         'did_id' => $request->get('did_id'),
        //         'did_no'=> $request->get('did_no'),
        //         'did_name'=> $request->get('did_name'),
        //         'set_pri_callerid'=> $request->get('set_pri_callerid'),
        //         'pri_id'=> $request->get('pri_id'),
        //     ];

        // DB::table('extra_dids')->insert(
        //     $extra_did_data
        // );    

        toastr()->success('Account added successfully.');
        return redirect()->route('loginAccounts');
    }

    /* ----------blacklist----------- */
    public function blacklist() {
        $blacklists = DB::table('blacklist')
            ->leftJoin('accountgroup', 'blacklist.groupid', '=', 'accountgroup.id')
            ->select('blacklist.*', 'accountgroup.name')
            ->get();
        return view('user.black_list', compact('blacklists'));
    }

    public function addBlacklist()
    {
        $customer = DB::table('accountgroup')->pluck('name', 'id');
        $customer = $customer->prepend('Select Customer', '');
        return view('user.add_blacklist', compact('customer'));
    }

    public function storeBlacklist(Request $request)
    {
        $customer = DB::table('accountgroup')->pluck('name', 'id');
        $customer = $customer->prepend('Select Customer', '');
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'phone_number' => 'required',
            'reason' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            return view('user.add_blacklist', compact('messages', 'customer'));
        } else {
            $blacklist_data = [
                'groupid' => $request->get('groupid'),
                'phone_number'=> $request->get('phone_number'),
                'reason'=> $request->get('reason')
            ];

            DB::table('blacklist')->insert(
                $blacklist_data
            );  

            toastr()->success('Blacklist added successfully.');
        } 
        return redirect()->route('BlackList');
        
    }

    public function destroyBlacklist($id)
    {
        //dd($id);
        $res = DB::table('blacklist')->where('id',$id)->delete();
        toastr()->success('Blacklist delete successfully.');
        return redirect()->route('BlackList');
    }

    public function operators() {
        $operators = DB::table('operatoraccount')
            ->select('operatoraccount.*')
            ->get();
        //dd($operators);
        return view('user.operator_list', compact('operators'));
    }

    public function addOperator()
    {
        return view('user.add_operator');
    }

    public function storeOperator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'required',
            'opername' => 'required',
            'username' => 'required',
            'password' => 'required',
            'livetrasferid' => 'required',
            'start_work' => 'required',
            'end_work' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            return view('user.add_operator', compact('messages'));
        } else {
            //dd($request->all());die();
            $operator_data = new OperatorAccount([
                'phonenumber' => $request->get('phonenumber'),
                'opername'=> $request->get('opername'),
                'oper_status'=> $request->get('oper_status'),
                'livetrasferid'=> $request->get('livetrasferid'),
                'start_work'=> $request->get('start_work'),
                'end_work'=> $request->get('end_work'),
                'app_use'=> $request->get('app_use'),
                'edit'=> $request->get('edit'),
                'download'=> $request->get('download'),
                'play'=> $request->get('play'),
            ]);

            // DB::table('operatoraccount')->insert(
            //     $operator_data
            // );  
            $operator_data->save();
            if($operator_data->save()){
                $account_data = [
                    'username'=> $request->get('username'),
                    'password'=> $request->get('password'),
                ];
                $operator_data->accounts()->save($account_data);
            }

            toastr()->success('Operator added successfully.');
        } 
        return redirect()->route('OperatorList');
        
    }

    public function editOperator($id)
    {
        $operator = new OperatorAccount();
        //$operator_edit = $operator->find($id);     
        $operator_edit = OperatorAccount::with(['accounts'])->find($id);     
        //$operator_edit = OperatorAccount::with('accounts')->where('id', $id)->get();  
        //dd($operator_edit);   
        return view('user.edit_operator', compact('operator_edit'));
    }

    public function updateOperator($id, Request $request)
    {
        //dd($id);
        $operator = new OperatorAccount();
        $operator_edit = $operator->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'required',
            'opername' => 'required',
            'username' => 'required',
            'password' => 'required',
            'livetrasferid' => 'required',
            'start_work' => 'required',
            'end_work' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            //dd($messages = $validator->messages());
            return view('user.edit_operator', compact('messages', 'user_edit'));
        } else {
            $operator_data = [
                'phonenumber' => $request->get('phonenumber'),
                'opername'=> $request->get('opername'),
                'oper_status'=> $request->get('oper_status'),
                'livetrasferid'=> $request->get('livetrasferid'),
                'start_work'=> $request->get('start_work'),
                'end_work'=> $request->get('end_work'),
                'app_use'=> $request->get('app_use'),
                'edit'=> $request->get('edit'),
                'download'=> $request->get('download'),
                'play'=> $request->get('play'),
            ];
            //dd($operator_data);
            $operator_edit->fill($operator_data)->save();
            if($operator_edit->fill($operator_data)->save()) {
                $account_data = [
                    'username'=> $request->get('username'),
                    'password'=> $request->get('password'),
                ];
                //dd($account_data);
                $operator_edit->accounts()->update($account_data);
            }
            toastr()->success('Operator update successfully.');
            return redirect()->route('OperatorList');
        }
        
    }

    public function destroyOperator($id)
    {
        $operator = OperatorAccount::find($id);
        //dd($operator);
        $operator->delete();
        toastr()->success('Operator delete successfully.');
        return redirect()->route('OperatorList');
    }

    public function operatorgrp() {
        $operatordept = DB::table('operatordepartment')->where('groupid', 1)->where('C2C', '1')->get();
        return view('user.operatorgrp', compact('operatordept'));
    }

    public function operatorgrp_details($id) {
        return $details = OperatorDepartment::find($id);
    }

    public function resellers() {
        $resellers = DB::table('resellergroup')->get();
        dd($resellers);
        return view('user.reseller_list', compact('resellers'));
    }
}
