<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use App\Models\Dids;
use App\Models\CrmLeads;
use App\Models\Accountgroup;
use App\Models\Account;
use App\Models\OperatorAccount;
use App\Models\OperatorDepartment;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Hash;

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
        $this->did = new Dids();
        $this->op_dept = new OperatorDepartment();
        $this->ac_group = new Accountgroup();

    }

    public function index() {
        $users = DB::table('accountgroup')
            ->leftJoin('resellergroup', 'accountgroup.resellerid', '=', 'resellergroup.id')
            ->leftJoin('dids', 'accountgroup.did', '=', 'dids.id')
            ->select('accountgroup.*', 'resellergroup.resellername', 'dids.did')
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
        $apikey = \Ramsey\Uuid\Uuid::uuid1()->toString();
        $c2capi = "http://ivrmanager.in/api/webc2c.php?apikey=$apikey&source=&number=";
        $cdr_api_key = \Ramsey\Uuid\Uuid::uuid4()->toString();
        //dd($c2capi);
        $account_group = new Accountgroup();
        $lang = $account_group->get_language();
        $lang = $lang->prepend('Select language', '0');
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $sms_gateway = $account_group->sms_api_gateway();
        $sms_gateway = $sms_gateway->prepend('Select gateway', '0');
        $did_list = $this->did->get_did();
        
        $did_list = $did_list->prepend('Select Did', '');
        
        return view('user.add_user', compact('lang', 'coperate', 'default', 'did_list', 'sms_gateway', 'c2capi', 'cdr_api_key'));
    }

    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'did' => 'required',
            'startdate' => 'required|date|before:enddate',
            'enddate' => 'required|date|after:startdate',
            'try_count' => 'required|integer|min:0',
            'dial_time' => 'required|integer|min:0',
            'maxcall_dur' => 'required|integer|min:0',
            'c2c_channels' => 'required',
            'c2cAPI' => 'required',
            'cdr_apikey' => 'required',
            'max_no_confrence' => 'required|integer|min:0',
        ];

        if(!empty($request->get('sms_api_gateway_id'))) {
            $rules['sms_api_user'] = 'required';
            $rules['sms_api_pass'] = 'required';
            $rules['sms_api_senderid'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $messages = $validator->messages(); 
            return redirect()->back()->withErrors($validator)->withInput();
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
                'crm'=> $request->get('crm'),
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

        $account_group->save();
        //accountgroup.grid.inc
        if(!empty($account_group->id)) {
            $this->did::where('id', $request->get('did'))->update(array('assignedto' => $account_group->id));
            //  ivrlevel_id -> department_id OR DT was the preivios fildname
            $this->op_dept->insert(array('resellerid' => $request->get('resellerid'), 'groupid' => $account_group->id, 'ivrlevel_id' => 1, 'dept_name' => 'DT-DPT', 'opt_calltype' => 'Round_Robin', 'adddate' => NOW(), 'starttime' => '00:00:00', 'endtime' => '23:59:59'));

            $this->op_dept->insert(array('resellerid' => $request->get('resellerid'), 'groupid' => $account_group->id, 'ivrlevel_id' => 1, 'dept_name' => 'C2C-DPT', 'opt_calltype' => 'Round_Robin', 'adddate' => NOW(), 'starttime' => '00:00:00', 'endtime' => '23:59:59'));
        }

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
            'startdate' => 'required|date|before:enddate',
            'enddate' => 'required|date|after:startdate',
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
                'crm'=> $request->get('crm'),
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
            $this->did::where('id', $request->get('did'))->update(array('assignedto' => $id));
            toastr()->success('User update successfully.');
            return redirect()->route('UserList');
        }
        
    }

    public function deleteAccount($id)
    {
        $res = DB::table('accountgroup')->where('id',$id)->delete();
        toastr()->success('User delete successfully.');
        return redirect()->route('UserList');
    }

    public function destroy($id)
    {
        $res = DB::table('account')->where('id',$id)->delete();
        toastr()->success('User delete successfully.');
        return redirect()->route('loginAccounts');
    }

    /* ----------login account----------- */
    public function loginAccounts() {
        $account_group = new Accountgroup();
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '');

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
        $accounts = $query->orderBy('id', 'desc')->paginate(10);
        //dd($accounts);
        return view('user.account_list', compact('accounts', 'coperate'));
    }

    public function editAccount($id = null) {
        $account = new Account();
        return $account->findOrFail($id);     
        
    }

    public function getCustomer($usertype, $resellerid) {
        return getAccountgroups($usertype, $resellerid);
    }

    public function getDid($groupid) {
        return getDidList($groupid);
    }

    public function addAccount(Request $request) 
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'usertype' => 'required',
            // 'resellerid' => 'required',
            // 'groupid' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $account = ['username' => $request->get('username'),
                     'password'=> Hash::make($request->get('password')),
                     'usertype'=> $request->get('usertype'), 
                     'resellerid' => $request->get('resellerid'),
                     'groupid' => $request->get('groupid'),
                     'phone_number' => $request->get('phone_number'),
                     'email' => $request->get('email'),
                     'status' => 'Active'
                    ];
            if(empty($request->get('id'))) {
                DB::table('account')->insert($account);
                $data['success'] = 'Account added successfully.';
            } else {
                DB::table('account')
                    ->where('id', $request->get('id'))
                    ->update($account);
                    $data['success'] = 'Account updated successfully.';
            }

        } 
         return $data;
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

            DB::table('operatoraccount')->insert(
                $operator_data
            );  
            $operator_data->save();
            if($operator_data->save()){
                $account_data = [
                    'username'=> $request->get('username'),
                    'password'=> Hash::make($request->get('password'))
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

    public function leadList() {
        $leads = DB::table('crm_leads')->get();
        //dd($users);
        return view('leads.lead_list', compact('leads'));
    }
    
    public function addLead()
    {
        $category = DB::table('crm_category')->where('crm_category_active',1)->pluck('crm_category_name','id');
        $category = $category->prepend('Select category', '0');
        //$subcategory = $subcategory->prepend('Select category', '0');
        return view('leads.add_lead',compact('category'));
    }
    
    public function storeLead(Request $request)
    {
        $crmleads = new CrmLeads();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'DOB' => 'required',
            'lead_status' => 'required',
            'address' => 'required',
            'lead_owner' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);
        if($validator->fails()) {
            $category = DB::table('crm_category')->where('crm_category_active',1)->pluck('crm_category_name','id');
            $category = $category->prepend('Select category', '0');
            $messages = $validator->messages(); 
            return view('leads.add_lead',compact('messages','category'));
        } else {
            $crmleads = new CrmLeads([
                'campaginid' => 1,
                'listid' => 1,
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'DOB' => Carbon::parse($request->get('DOB'))->format('Y-m-d'),
                'lead_status' => $request->get('lead_status'),
                'phone_number' => $request->get('phone_number'),
                'address' => $request->get('address'),
                'lead_owner' => $request->get('lead_owner'),
                'category_id' => $request->get('category_id'),
                'sub_category_id' => $request->get('sub_category_id'),
                
            ]);
        //dd($users);
        $crmleads->save();
        toastr()->success('Lead added successfully.');
        } 
        return redirect()->route('LeadList');     
    }
    
    public function editLead($id)
    {
        $category = DB::table('crm_category')->where('crm_category_active',1)->pluck('crm_category_name','id');
        $category = $category->prepend('Select category', '0');
        $lead_edit = CrmLeads::where('lead_id', $id)->firstOrFail(); 
        $subcategory = DB::table('crm_sub_category')->where('crm_category_id',$lead_edit->category_id)->pluck('crm_sub_category_name','id');
        $subcategory = $subcategory->prepend('Select sub category', '0');
        return view('leads.edit_lead', compact('lead_edit','category','subcategory'));
    }
    public function updateLead($id, Request $request)
    {
        //dd($id);
       // $crm_leads = new CrmLeads();
        $crm_lead = new CrmLeads();
       
        $lead_edit = $crm_lead->findOrFail($id);
       
        $validator = Validator::make($request->all(), [
           'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'DOB' => 'required',
            'lead_status' => 'required',
            'address' => 'required',
            'lead_owner' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);
        if($validator->fails()) {
            $messages = $validator->messages();
            //dd($messages = $validator->messages());
            return view('leads.edit_lead', compact('messages','lead_edit'));
        } else {
            $crm_lead = [
                'campaginid' => 1,
                'listid' => 1,
                'name' => $request->get('name'),
                'email'=> $request->get('email'),
                'DOB'=> Carbon::parse($request->get('DOB'))->format('Y-m-d'),
                'lead_status'=> $request->get('lead_status'),
                'phone_number'=> $request->get('phone_number'),
                'address'=> $request->get('address'),
                'lead_owner'=> $request->get('lead_owner'),
                'category_id'=> $request->get('category_id'),
                'sub_category_id'=> $request->get('sub_category_id'),
                'lead_id' => $id,
            ];
            //dd($account_g->orderBy('adddate', 'desc'roup);
            $lead_edit->fill($crm_lead)->save();
            toastr()->success('Lead update successfully.');
            return redirect()->route('LeadList');
        }
        
    }
    public function destroyLead($id)
    {
        $res = DB::table('crm_leads')->where('lead_id',$id)->delete();
        toastr()->success('Lead delete successfully.');
        return redirect()->route('LeadList');
    }

    public function coperates() {
        $cdr_api_key = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $resellers = DB::table('resellergroup')->orderBy('id', 'desc')->paginate(10);
        return view('user.reseller_list', compact('resellers', 'cdr_api_key'));
    }

    public function editCoperate($id) {

        return DB::table('resellergroup')
                ->join('account', 'resellergroup.id', '=', 'account.resellerid')
                ->where('resellergroup.id', $id)
                ->select('resellergroup.*', 'account.username', 'account.password')
                ->get();
    }

    public function addCoperate(Request $request) 
    {
        if(!empty($request->get('id'))) {
            $validator = Validator::make($request->all(), [
                'resellername' => 'required',
                'username' => 'required',
                'password' => 'required',
                'cdr_apikey' => 'required',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'resellername' => 'required',
                'username' => 'required|unique:account,username',
                'password' => 'required',
                'cdr_apikey' => 'required',
            ]);
        }        

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $reseller = ['resellername' => $request->get('resellername'),
                     'cdr_apikey'=> $request->get('cdr_apikey')
                    ];
            $account = [ 'status' => 'Active',
                         'usertype' => 'reseller',
                         'username' => $request->get('username'),
                         'password'=> Hash::make($request->get('password')),
                        ]; 

            if(empty($request->get('id'))) {
                $id = DB::table('resellergroup')->insertGetId($reseller);
                $new_field = array('resellerid' => $id);
                $account_1 = array_merge($account, $new_field);
                if(!empty($id)) {
                    DB::table('account')->insert($account_1);
                    $data['success'] = 'Coperate added successfully.';
                }
            } else {
                DB::table('resellergroup')
                    ->where('id', $request->get('id'))
                    ->update($reseller);
                DB::table('account')
                    ->where('resellerid', $request->get('id'))
                    ->update($account);
                $data['success'] = 'Coperate updated successfully.';
            }
        } 
        return $data;
    }

    public function destroyCoperate($id)
    {
        DB::table('resellergroup')->where('id',$id)->delete();
        DB::table('account')->where('resellerid',$id)->delete();
        toastr()->success('Coperate delete successfully.');
        return redirect()->route('CoperateGroup');
    }


}
