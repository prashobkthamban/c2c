<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IUserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ServiceController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct(IUserService $userService)
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->userService = $userService;
    }

    public function test()
    {
        $users = $this->userService->getAllUsers();
        print_r($users);die;
    }

    public function billing() {
    
        $query = DB::table('billing')
            ->leftJoin('accountgroup', 'billing.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'billing.resellerid', '=', 'resellergroup.id')
            ->leftJoin('dids', 'billing.groupid', '=', 'dids.assignedto');

        if(Auth::user()->usertype == 'reseller') {
            $query->where('billing.resellerid', Auth::user()->resellerid)->orWhere('billing.resellerid', '0');
        } elseif(Auth::user()->usertype != 'admin') {
            $query->where('billing.groupid', Auth::user()->groupid);
        }
            
        $query->select('billing.*', 'accountgroup.name', 'resellergroup.resellername', 'dids.rdins', 'dids.rdnid')->orderBy('id', 'desc');
        $result = $query->paginate(10);

        if (Auth::user()->usertype == 'operator') {
            $lead_allowed = DB::table('operatoraccount')->where('opername',Auth::user()->username)->select('lead_access')->first();
            $total_access_leads = $lead_allowed->lead_access;
        }   
        else
        {
            $total_access_leads = (Auth::user()->load('accountdetails')->accountdetails != null) ? Auth::user()->load('accountdetails')->accountdetails->leads_access : '';
        }

        $apiKey = urlencode('624AD-63599');
 
        // Prepare data for POST request
        $data = array('apikey' => $apiKey);
     
        // Send the POST request with cURL
        $ch = curl_init('http://smsdnd.voiceetc.co.in/sms-panel/api/http/index.php?username=demosms&apikey=624AD-63599&apirequest=CreditCheck&route=RouteName&format=JSON');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        return view('service.billing_list', compact('result','total_access_leads','response'));
    }

    public function billDetails($groupid) {
        return DB::table('billing_log')->where('billing_log.groupid', $groupid)->get();
    } 

    public function getBilling($id) {
        return DB::table('billing')
        ->leftJoin('accountgroup', 'billing.groupid', '=', 'accountgroup.id')
        ->where('billing.id', $id)->select('billing.*', 'accountgroup.name')->get();
    }

    public function editBilling(Request $request) 
    {
        $rules = [
            //'main_balance' => 'required',
        ];   
        //dd($request->all());
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $today = date("Y-m-d"); 
            if($request->get('billingmode')=='prepaid')
            {
                if($request->get('creditlimit')!=NULL)
                {
                    if($request->get('main_bal')==NULL)
                    {
                        $prepaidbalance = $request->get('creditlimit');
                        $reset='Reset Done';
                    }
                    else
                    {
                        $prepaidbalance = $request->get('main_bal') + $request->get('creditlimit');
                        $reset='';
                    }
                    $limit = 0;
                    $type = 'Recharge';
                    $comments = 'Previous Balance='.$request->get('main_bal').' - Click to call balance '.$request->get('c2c_balance').' ---- '.$reset;
                }
            } else 
            {
                $limit=$request->get('creditlimit');
                if($request->get('creditlimit')==NULL)
                {
                    $prepaidbalance='';
                    $reset='Reset Done';
                } else
                {
                    $prepaidbalance="";
                    $reset='';
                }
                $type = 'Credit Limit';
                $comments = 'Previous Balance='.$request->get('main_bal').' - Click to call balance '.$request->get('c2c_balance').' -- bill date='.$request->get('billdate').' ---- '.$reset;
            }

            $billing_log = ['groupid' => $request->get('groupid'),
                            'amount' => $request->get('creditlimit'),
                            'bill_cycle' => $today,
                            'datetime' => NOW(),
                            'type' => $type,
                            'username' => Auth::user()->username,
                            'comments' => $comments
                        ];

            $billing = ['main_balance' => $prepaidbalance,
                     'call_pulse_setup'=> $request->get('call_pulse_setup'),
                     'c2c_pulse_setup'=> $request->get('c2c_pulse_setup'), 
                     'c2c_balance' => $request->get('c2c_balance'),
                     'billingmode' => $request->get('billingmode'),
                     'billdate' => $request->get('billdate'),
                     'creditlimit' => $limit,
                    ];

            // if($request->get('billingmode')=='postpaid')
            // {
            //     unset($billing['main_balance']);
            // }
            //dd($request->all());
                DB::table('billing')
                    ->where('id', $request->get('id'))
                    ->update($billing);
                DB::table('billing_log')->insert($billing_log);
                    $data['success'] = 'Billing updated successfully.';
        } 
         return $data;
    }

    public function accessLogs() {
        //echo 'bfvgh';die;
        $query = DB::table('ast_login_log')
            ->leftJoin('accountgroup', 'ast_login_log.groupid', '=', 'accountgroup.id');

        if(Auth::user()->usertype == 'reseller') {
            $query->where('account.resellerid', Auth::user()->resellerid);
        } elseif(Auth::user()->usertype != 'groupadmin') {
            $query->where('ast_login_log.groupid', Auth::user()->groupid);
            $query->where('ast_login_log.usertype', 'groupadmin');
        } elseif(Auth::user()->usertype != 'admin') {

        } else {
           $query->where('ast_login_log.groupid', Auth::user()->groupid); 
        }

            
        $query->select('ast_login_log.*', 'accountgroup.name')->orderBy('id', 'desc');
        $result = $query->paginate(10);
        //dd($result);
        return view('service.access_logs', compact('result'));
    }

    public function liveCalls() {
        //dd(Auth::user());
        $query = DB::table('cur_channel_used')
            ->leftJoin('accountgroup', 'cur_channel_used.groupid', '=', 'accountgroup.id')
            ->leftJoin('operatoraccount', 'cur_channel_used.operatorid', '=', 'operatoraccount.id')
            ->leftJoin('operatordepartment', 'cur_channel_used.departmentid', '=', 'operatordepartment.id');

        if(Auth::user()->usertype == 'reseller') {
            $query->where('cur_channel_used.resellerid', Auth::user()->resellerid);
            $query->where('cur_channel_used.calltype', 'ivr');
        } elseif(Auth::user()->usertype == 'groupadmin') {
            $query->where('cur_channel_used.groupid', Auth::user()->groupid);
            $query->where('cur_channel_used.calltype', 'ivr');
        } elseif(Auth::user()->usertype != 'admin') {

        } else {
           $query->where('cur_channel_used.operatorid', Auth::user()->id);
           $query->where('cur_channel_used.calltype', 'ivr'); 
        }

            
        $query->select('cur_channel_used.*', 'accountgroup.name', 'operatoraccount.opername', 'operatordepartment.dept_name')->orderBy('id', 'desc');
        $result = $query->paginate(10);
        //dd($result);
        return view('service.live_calls', compact('result'));
    }

    public function gateway() {
        $result = DB::table('prigateway')->where('delete_status', '0')->orderBy('id', 'desc')->paginate(10);
        return view('service.gateway', compact('result'));
    }

    public function addGateway(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Gprovider' => 'required',
            'Gchannel' => 'required',
        ]);
        if($validator->fails()) {
            $data['error'] = $validator->messages();
        } else {
            $gateway_data = [
                'Gprovider' => $request->get('Gprovider'),
                'Gchannel' => $request->get('Gchannel'),
                'billingdate'=> $request->get('billingdate'),
                'used_units'=> $request->get('used_units'),
                'pluse_rate' => $request->get('pluse_rate'),
            ];
    
            if(empty($request->get('id'))) {
                DB::table('prigateway')->insert($gateway_data);
                $data['success'] = 'Pri Gateway added successfully.';
            } else {
                DB::table('prigateway')
                    ->where('id', $request->get('id'))
                    ->update($gateway_data);
                $data['success'] = 'Pri Gateway updated successfully.';
            }
        }
        return $data;
    }

    public function getPriGateway($id) {
        $data=  DB::table('prigateway')->where('prigateway.id', $id)->get();
	    return $data;
    }  

    public function prilog($id) {
        return $result = DB::table('pri_gateway_log')
            ->where('pri_id', $id)
            ->get();
    }

}
