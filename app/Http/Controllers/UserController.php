<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use App\Models\Dids;
use App\Models\Extra_dids;
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
$string = 'c4ca4238a0b923820dcc';
$encrypted = \Illuminate\Support\Facades\Crypt::encrypt($string);
$decrypted_string = \Illuminate\Support\Facades\Crypt::decrypt($encrypted);

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
	    $this->extra_dids = new Extra_dids();
        $this->op_dept = new OperatorDepartment();
        $this->ac_group = new Accountgroup();

    }

    public function index() {
        $users = DB::table('accountgroup')
            ->leftJoin('resellergroup', 'accountgroup.resellerid', '=', 'resellergroup.id')
            ->leftJoin('dids', 'accountgroup.did', '=', 'dids.id')
            ->select('accountgroup.*', 'resellergroup.resellername', 'dids.did')
            ->get();
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
        $did_list = $did_list->prepend('Select Dids', '');

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
                'c2c'=> $request->get('c2c'),
                'crm_users'=>$request->get('crm_users'),
                'leads_access'=>$request->get('leads_access'),
            ]);

        $account_group->save();
        //accountgroup.grid.inc
        if(!empty($account_group->id)) {
            $this->did::where('id', $request->get('did'))->update(array('assignedto' => $account_group->id));
            //  ivrlevel_id -> department_id OR DT was the preivios fildname
            $this->op_dept->insert(array('resellerid' => $request->get('resellerid'), 'groupid' => $account_group->id, 'ivrlevel_id' => 1, 'dept_name' => 'DT-DPT', 'opt_calltype' => 'Round_Robin', 'adddate' => NOW(), 'starttime' => '00:00:00', 'endtime' => '23:59:59'));
            $this->op_dept->insert(array('resellerid' => $request->get('resellerid'), 'groupid' => $account_group->id, 'ivrlevel_id' => 1, 'dept_name' => 'C2C-DPT', 'opt_calltype' => 'Round_Robin', 'adddate' => NOW(), 'starttime' => '00:00:00', 'endtime' => '23:59:59'));
            $billData = ['resellerid' => $request->get('resellerid'), 'groupid' => $account_group->id];
            DB::table('billing')->insert($billData);
            $shiftData = ['shift_name' => 'Full Day', 'start_time' => '00:00:00', 'end_time'=> '23:59:59', 'groupid' => $account_group->id];
            DB::table('operator_shifts')->insert($shiftData);
        }
	//@savitha we need to insert this entry in to billing
	//$querys = "INSERT INTO billing(resellerid,groupid)VALUES ('" .$request->get('resellerid') . "', '" . $account_group->id . "');";
        toastr()->success('User added successfully.');
        }
        return redirect()->route('UserList');

    }

    public function edit($id)
    {
        $account_group = new Accountgroup();
        $did = new Dids();
        $user_edit = $account_group->findOrFail($id);
        $lang = $account_group->get_language();
        $lang = $lang->prepend('Select language', '0');
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $sms_gateway = $account_group->sms_api_gateway();
        $sms_gateway = $sms_gateway->prepend('Select gateway', '0');
        $did_list = $did->get_did($id);
        return view('user.edit_user', compact('user_edit','lang', 'coperate', 'did_list', 'sms_gateway'));
    }

 public function editSettings($id)
    {
        $account_group = new Accountgroup();
        $user_edit = $account_group->findOrFail($id);
        return view('user.edit_user_settings',compact('user_edit'));
    }

 public function updatesettings($id, Request $request) {
        $account_group = new Accountgroup();
       	$user_edit = $account_group->findOrFail($id);
        $rules = [
            'c2c_channels' => 'required',
            'c2cAPI' => 'required',
            'cdr_apikey' => 'required',
            'max_no_confrence' => 'required|integer|min:0',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $messages = $validator->messages();
            return view('user.edit_user_settings', compact('messages','user_edit'));
        } else {
            $account_group = [
                'emailservice_assign_cdr'=> $request->get('emailservice_assign_cdr'),
                'smsservice_assign_cdr'=> $request->get('smsservice_assign_cdr'),
                'c2c_channels'=> $request->get('c2c_channels'),
                'c2cAPI'=> $request->get('c2cAPI'),
                'operator_dpt'=> $request->get('operator_dpt'),
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
                'pushapi'=> $request->get('pushapi'),
                'pbxexten'=> $request->get('pbxexten'),
                'c2c'=> $request->get('c2c')
            ];
            $user_edit->fill($account_group)->save();
            toastr()->success('User update successfully.');
            return redirect()->route('UserList');
        }
    }


    public function update($id, Request $request) {
        $account_group = new Accountgroup();
        $did = new Dids();
        $lang = $account_group->get_language();
        $coperate = $account_group->get_coperate();
        $coperate = $coperate->prepend('Select coperate', '0');
        $sms_gateway = $account_group->sms_api_gateway();
        $sms_gateway = $sms_gateway->prepend('Select gateway', '0');
        $did_list = $did->get_did($id);
        $did_list = $did_list->prepend('Select Did', '0');
        $user_edit = $account_group->findOrFail($id);
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
                'c2c'=> $request->get('c2c'),
                'crm_users'=>$request->get('crm_users'),
                'leads_access'=>$request->get('leads_access'),
            ];
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
        $coperate = $coperate->prepend('Select coperate', '0');
        $customer = getAccountgroups();
        $customer = $customer->prepend('Select customer', '');

        $query = DB::table('account')
             // ->leftJoin('accountgroup', 'account.groupid', '=', 'accountgroup.id')
             ->leftJoin('resellergroup', 'account.resellerid', '=', 'resellergroup.id');
        if(Auth::user()->usertype == 'admin') {
		// dont want list operator
		$query->where('usertype','<>', 'operator');
        } elseif(Auth::user()->usertype == 'reseller') {
           $query->where('resellerid', Auth::user()->usertype);
        } elseif(Auth::user()->usertype == 'groupadmin') {
           $query->where('groupid', Auth::user()->groupid);
           $query->where('usertype', 'groupadmin');
        } else {
            $query->where('groupid', Auth::user()->groupid);
        }
        $query->select('account.*', 'resellergroup.resellername');
        $accounts = $query->orderBy('id', 'desc')->paginate(10);
        return view('user.account_list', compact('accounts', 'coperate', 'customer'));
    }

    public function editAccount($id = null) {
        $account = new Account();
        return $account->findOrFail($id);
    }

    public function getCustomer($usertype, $resellerid) {
        return getAccountgroups($usertype, $resellerid);
    }

    public function getCustomerResellerId($groupid) {
        return getCustomerResellerId($groupid);
    }

    public function getDid($groupid) {
        return getDidList($groupid);
    }

    public function addAccount(Request $request)
    {
        $rules = [
            'username' => 'required',
            'usertype' => 'required',
        ];

        if(empty($request->get('id'))) {
            $rules['password'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $data = DB::table('account')
                ->where('username', $request->get('username'));
            if(!empty($request->get('id'))) {
                $data->where('id', '!=', $request->get('id'));
            }
            $data = $data->first();
            if (!empty($data)) {
                return ['error' => ['error' => ['Username already in use. Please choose a different Username']]];
            }
            $account = ['username' => $request->get('username'),
                     'password'=> Hash::make($request->get('password')),
                     'user_pwd'=> $request->get('password'),
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
                if(empty($request->get('password'))) {
                    unset($account['password']);
                }

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
            //->leftJoin('accountgroup', 'blacklist.groupid', '=', 'accountgroup.id')
            ->where('groupid', Auth::user()->groupid)
            ->get();
        return view('user.black_list', compact('blacklists'));
    }

    public function addBlacklist(Request $request)
    {
        $customer = DB::table('accountgroup')->pluck('name', 'id');
        $customer = $customer->prepend('Select Customer', '');
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'reason' => 'required',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $blacklist_data = [
                'groupid' => Auth::user()->groupid,
                'phone_number'=> $request->get('phone_number'),
                'reason'=> $request->get('reason')
            ];

            DB::table('blacklist')->insert(
                $blacklist_data
            );
            $data['success'] = 'Blacklist added successfully.';
        }
        return $data;

    }

    public function operators() {
        $operators = OperatorAccount::with(['accounts'])
                ->where('groupid', Auth::user()->groupid)
                ->orderBy('adddate','DESC')
                ->paginate(10);
        $data = DB::table('operatoraccount')
                ->where('groupid', Auth::user()->groupid)
                ->orderByRaw('CONVERT(livetrasferid, SIGNED) desc')
                ->first();
        $nextLiveTransferId = !empty($data) ? $data->livetrasferid+1 : 1;
        return view('user.operator_list', compact('operators', 'nextLiveTransferId'));
    }

    public function operatorCount() {
        return OperatorAccount::where('groupid', Auth::user()->groupid)->count();
    }

    public function stickey_list($id) {
        return $stickey = DB::table('ast_sticky_agents')
            ->select('ast_sticky_agents.id','ast_sticky_agents.caller','accountgroup.name','operatordepartment.dept_name','operatoraccount.opername')->leftJoin('accountgroup', 'ast_sticky_agents.groupid', '=', 'accountgroup.id')->leftJoin('operatordepartment', 'ast_sticky_agents.departmentid', '=', 'operatordepartment.id')->leftJoin('operatoraccount', 'ast_sticky_agents.operatorid', '=', 'operatoraccount.id')->where('operatorid', $id)->get();
            //dd($stickey);die;
    }

    public function delete_stickey($id)
    {
        $res = DB::table('ast_sticky_agents')->where('id',$id)->delete();
        return response()->json([
            'status' => true
        ]);
    }

    public function addOprAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'required',
            'opername' => 'required',
            'username' => 'required',
            'password' => 'required',
            'livetrasferid' => 'required',
            'shift_id' => 'required',
            'working_days' => 'required'
        ]);
        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $data = DB::table('operatoraccount')
                ->where('groupid', Auth::user()->groupid)
                ->where('livetrasferid', $request->get('livetrasferid'));
            if(!empty($request->get('id'))) {
                $data->where('id', '!=', $request->get('id'));
            }
            $data = $data->first();
            if (!empty($data)) {
                return ['error' => ['error' => ['Live Transfer ID already in use. Please choose a different id.']]];
            }
            $data = DB::table('account')
                ->where('username', $request->get('username'));
            if(!empty($request->get('id'))) {
                $data->where('operator_id', '!=', $request->get('id'));
            }
            $data = $data->first();
            if (!empty($data)) {
                return ['error' => ['error' => ['Username already in use. Please choose a different Username']]];
            }
            $workingDays = explode(',', $request->working_days);
            $operator_data = [
                'phonenumber' => $request->get('phonenumber'),
                'groupid' => Auth::user()->groupid,
                'opername'=> $request->get('opername'),
                'oper_status'=> $request->get('oper_status'),
                'livetrasferid'=> $request->get('livetrasferid'),
                'shift_id'=> $request->get('shift_id'),
                'app_use'=> $request->get('app_use'),
                'edit'=> $request->get('edit'),
                'download'=> $request->get('download'),
                'play'=> $request->get('play'),
                'working_days' => json_encode($workingDays),
            ];

            $account_data = [
                'username'=> $request->get('username'),
                'password'=> Hash::make($request->get('password')),
                'user_pwd'=> $request->get('password'),
                'usertype' => 'operator',
                'groupid' => Auth::user()->groupid
            ];
            if(!empty($request->get('id'))) {
                OperatorAccount::where('id', $request->get('id'))->update($operator_data);
                DB::table('account')
                ->where('operator_id', $request->get('id'))
                ->update($account_data);
                $data['success'] = 'Operator updated successfully.';
            } else {
                $operator_data = new OperatorAccount($operator_data);
                $operator_data->save();
                if(!empty($operator_data->id)){
                    $account_data = array_merge($account_data, ['operator_id'=> $operator_data->id]);
                    DB::table('account')->insert(
                        $account_data
                    );
                }
                $data['success'] = 'Operator added successfully.';
                // $data['crm_access_error'] = '1';
            }
        }
        return $data;
    }

    public function getOprAccount($id) {
        $data=  DB::table('operatoraccount')->select('operatoraccount.id', 'phonenumber', 'opername', 'oper_status', 'livetrasferid', 'shift_id', 'app_use', 'edit', 'download', 'play','working_days', 'account.username', 'account.password', 'account.user_pwd', 'operator_shifts.shift_name')->leftJoin('account', 'operatoraccount.id', '=', 'account.operator_id')->leftJoin('operator_shifts', 'operatoraccount.shift_id', '=', 'operator_shifts.id')->where('operatoraccount.id', $id)->get();
	    return $data;
    }

    public function destroyOperator($id)
    {
        $operator = OperatorAccount::find($id);
        $operator->delete();
        $operator->accounts()->delete();
        if($id) {
            DB::table('operator_dept_assgin')->where('operatorid',$id)->delete();
            DB::table('account')->where('operator_id',$id)->delete();
        }
        toastr()->success('Operator delete successfully.');
        return redirect()->route('OperatorList');
    }

    public function operatorShifts() {
        $results = DB::table('operator_shifts')->where('groupid', Auth::user()->groupid)->paginate(10);
        return view('user.operator_shifts', compact('results'));
    }

    public function addShift(Request $request) {
        $validator = Validator::make($request->all(), [
            'shift_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'end_time' => 'after:start_time'
        ], [
            'end_time.after' => 'End Time greater than Start Time.',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $shift = ['shift_name' => $request->get('shift_name'),
                     'start_time' => $request->get('start_time'),
                     'end_time'=> $request->get('end_time'),
                     'groupid' => Auth::user()->groupid
                    ];
            if(empty($request->get('id'))) {
                DB::table('operator_shifts')->insert($shift);
                $data['success'] = 'Operator Shift added successfully.';
            } else {
                DB::table('operator_shifts')
                    ->where('id', $request->get('id'))
                    ->update($shift);
                $data['success'] = 'Operator Shift updated successfully.';
            }

        }
         return $data;
    }

    public function deleteShift($id) {
        $res = DB::table('operator_shifts')->where('id',$id)->delete();
        toastr()->success('Operator Shift delete successfully.');
        return redirect()->route('OperatorShifts');
    }

    public function getShift($id) {
        return DB::table('operator_shifts')
                    ->where('id', $id)->get();
    }

    public function operatorgrp() {
        $acc_grp = DB::table('accountgroup')->where('id', Auth::user()->groupid)->select('operator_dpt', 'c2c')->get();
        $opCount =  DB::table('operatoraccount')->where('groupid', Auth::user()->groupid)->count();
        if($acc_grp[0]->operator_dpt == 'Yes')
        {
           $operatordept = DB::table('operatordepartment')->where('groupid', Auth::user()->groupid)->where('DT', '1')->where('C2C', '0')->get();
        } else {
           $operatordept = DB::table('operatordepartment')->where('groupid', Auth::user()->groupid)->where('DT', '0')->where('C2C', '0')->get();
        }
        return view('user.operatorgrp', compact('operatordept', 'opCount'));
    }

    public function editOprDept(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opt_calltype' => 'required',
            'sticky_agent' => 'required',
            'recordcall' => 'required',
            'dialtime' => 'required',
            'starttime' => 'required',
            'endtime' => 'required',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $operator_data = [
                'opt_calltype' => $request->get('opt_calltype'),
                'sticky_agent' => $request->get('sticky_agent'),
                'recordcall'=> $request->get('recordcall'),
                'dialtime'=> $request->get('dialtime'),
                'starttime'=> $request->get('starttime'),
                'endtime'=> $request->get('endtime'),
                'id' => $request->get('oprid'),
            ];

            if(!empty($request->get('oprid'))) {
                OperatorDepartment::where('id', $request->get('oprid'))->update($operator_data);
                $data['success'] = 'Operator Department update successfully.';
            } else {
                $data['error'] = 'Some error occured.';
            }

        }
        return $data;
    }

    public function operatorgrp_details($id) {
        $data['details'] = OperatorDepartment::find($id);
        $sql = "SELECT operatoraccount.id,operatoraccount.opername,phonenumber,priority,operatortype,operator_dept_assgin.id as oprId FROM  `operatoraccount`
LEFT JOIN accountgroup ON accountgroup.id = operatoraccount.groupid LEFT JOIN operator_dept_assgin ON operator_dept_assgin.operatorid = operatoraccount.id LEFT JOIN operatordepartment ON operator_dept_assgin.departmentid = operatordepartment.id";
        if(Auth::user()->usertype == 'groupadmin') {
            $sql .= " WHERE departmentid=" .$id ." and accountgroup.id = ".Auth::user()->groupid." ";
        }
        $query = DB::select($sql);
        $data['operators'] =  DB::table('operatoraccount')->where('groupid', Auth::user()->groupid)->select('id', 'opername')->get();
        $data['account_det'] = $query;
        $res = DB::table('operator_dept_assgin')
                ->where('departmentid', $id)
                ->orderByRaw('CONVERT(priority, SIGNED) desc')
                ->first();
        $data['nextPriority'] = !empty($res) ? $res->priority+1 : 1;
        return $data;
    }

    public function addOptassign(Request $request) {
        $validator = Validator::make($request->all(), [
            'operatorid' => 'required',
            'priority' => 'required',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $data = DB::table('operator_dept_assgin')
                ->where('departmentid', $request->get('departmentid'))
                ->where('priority', $request->get('priority'));
            $data = $data->first();
            if (!empty($data)) {
                return ['error' => ['error' => ['Priority Number already in use. Please choose a different Number']]];
            }
            $dept = ['operatorid' => $request->get('operatorid'),
                     'departmentid' => $request->get('departmentid'),
                     'priority'=> $request->get('priority')
                    ];

            DB::table('operator_dept_assgin')->insert($dept);
            $data['success'] = 'Operator added successfully.';
        }
         return $data;
    }

    public function addNumassign(Request $request) {
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'required',
            'opname' => 'required',
            'priority' => 'required',
            'livetransfer' => 'required',
            'shift_id' => 'required',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $data = DB::table('operator_dept_assgin')
                ->where('departmentid', $request->get('departmentid'))
                ->where('priority', $request->get('priority'));
            $data = $data->first();
            if (!empty($data)) {
                return ['error' => ['error' => ['Priority Number already in use. Please choose a different Number']]];
            }
            $dept = ['groupid' => Auth::user()->groupid,
                     'oper_status' => 'online',
                     'phonenumber'=> $request->get('phonenumber'),
                     'livetrasferid'=> $request->get('livetransfer'),
                     'opername'=> $request->get('opname'),
                     'start_work'=> '00:00:00',
                     'end_work'=> '23:59:59',
                     'operatortype'=> 'mob',
                     'shift_id'=> $request->get('shift_id'),
                     'adddate'=> now(),
                    ];

            $optid = DB::table('operatoraccount')->insertGetId($dept);
            if(!empty($optid)) {
                $op_assign = ['operatorid' => $optid,
                     'departmentid' => $request->get('departmentid'),
                     'priority'=> $request->get('priority'),
                    ];
                DB::table('operator_dept_assgin')->insert($op_assign);
                $data['success'] = 'Number added successfully.';
            } else {
                $data['error'] = 'Some errors are occured.';
            }
        }
         return $data;
    }

    public function deleteOpgroup($opid, $opacc)
    {
        //$s="Select * from operatoraccount where id='$opid'";
        $operator = OperatorAccount::find($opacc);
        if($operator->operatortype == 'web') {
            DB::table('operator_dept_assgin')->where('id',$opid)->delete();

        }
        if($operator->operatortype == 'mob') {
            DB::table('operatoraccount')->where('id',$opacc)->delete();
            DB::table('operator_dept_assgin')->where('id', $opid)->delete();
        }
        return response()->json([
            'status' => true,
        ]);
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
                ->select('resellergroup.*', 'account.username', 'account.user_pwd')
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
                     'cdr_apikey'=> $request->get('cdr_apikey'),
                     'associated_groups' => json_encode($request->get('associated_groups'))
                    ];
            $account = [ 'status' => 'Active',
                         'usertype' => 'reseller',
                         'username' => $request->get('username'),
                         'password'=> Hash::make($request->get('password')),
                         'user_pwd' => $request->get('password'),
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

    public function myProfile() {
        $acGrp = $this->ac_group->where('id', Auth::user()->groupid)->get();
        // my profile is mainly for groupadmin  and operator
        if(Auth::user()->usertype == 'groupadmin' ||  Auth::user()->usertype == 'operator'){
            $days = json_decode(Auth::user()->accountdetails->working_days);
            $did = Auth::user()->load('extradid')->extradid;
            //dd($did);

        } else {
            $days = [];
            $did = [];
        }
        //dd($acGrp);
        return view('user.my_profile',compact('acGrp','did', 'days'));
    }

    public function editProfile(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
            'phone_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $profile = ['email' => $request->get('email'),
                     'password'=> Hash::make($request->get('password')),
                     'user_pwd'=> $request->get('password'),
                     'phone_number'=> $request->get('phone_number'),
                    ];
            $workingDays = explode(',', $request->working_days);
            $accGroup = ['office_start' => $request->get('office_start'),
                     'office_end'=> $request->get('office_end'),
                     'aocalltransfer'=> $request->get('aocalltransfer'),
                     'playaom'=> $request->get('playaom'),
                     'working_days'=> json_encode($workingDays)
                    ];

                DB::table('account')
                    ->where('id', $request->get('id'))
                    ->update($profile);
                DB::table('accountgroup')
                    ->where('id', Auth::user()->groupid)
                    ->update($accGroup);


                    $data['success'] = 'Profile updated successfully.';
                    $data['data'] = $profile;
                    $data['data'] = array_merge($data['data'], $accGroup);

        }
         return $data;
    }

    public function crmSettings(Request $request)
    {
       if(!empty($request->all())) {
            $crm = ['companyname' => $request->get('companyname'),
                     'GST'=> $request->get('GST'),
                     'billing_address'=> $request->get('billing_address'),
                     'shipping_address'=> $request->get('shipping_address'),
                     'PAN'=> $request->get('PAN'),
                    ];

            DB::table('accountgroup')
                ->where('id', Auth::user()->groupid)
                ->update($crm);

            $data['success'] = 'Crm settings updated successfully.';
        } else {
            $data['error'] = 'Some errors are occured.';
        }
         return $data;
    }

    public function resetPassword() {
        return view('auth.passwords.reset');
    }

    public function associatedGroups() {
        $result = DB::table('accountgroup')
        ->whereIn('accountgroup.id', json_decode(Auth::user()->reseller->associated_groups))
        ->leftJoin('dids', 'accountgroup.did', '=', 'dids.id')
        ->select('accountgroup.id', 'accountgroup.name', 'accountgroup.startdate', 'accountgroup.enddate', 'accountgroup.status', 'accountgroup.did', 'dids.did')
        ->paginate(10);
        //dd($result);
        return view('user.account_group', compact('result'));

    }


}
