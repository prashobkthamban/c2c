<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\OperatorDepartment;

class OperatorController extends Controller
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
        $this->op_dept = new OperatorDepartment();

    }

    public function index(Request $request) {
        $requests = $request->all();
        $groupId = $request->get('customer');
        $query = DB::table('operatordepartment')->where('operatordepartment.delete_status', '0')
         ->leftJoin('ivr_menu', 'operatordepartment.ivrlevel_id', '=', 'ivr_menu.id')
         ->leftJoin('accountgroup', 'operatordepartment.groupid', '=', 'accountgroup.id')
         ->leftJoin('resellergroup', 'operatordepartment.resellerid', '=', 'resellergroup.id');
          if(Auth::user()->usertype == 'admin') {
        } elseif(Auth::user()->usertype == 'reseller') {
           $query->where('operatordepartment.resellerid', Auth::user()->resellerid);
        } else {
            $query->where('operatordepartment.groupid', Auth::user()->groupid);
        }
        
        if (isset($groupId)) {
            $query->where('operatordepartment.groupid', $groupId);
        }

        $query->select('operatordepartment.*', 'resellergroup.resellername', 'ivr_menu.ivr_level_name', 'accountgroup.name')->orderBy('id', 'desc');
        $operatordept = $query->get();
        return view('operator.operatordept_list', compact('operatordept', 'requests'));
    }

    public function addOperator(Request $request) { 
        $rules = [
            'groupid' => 'required',
            'dept_name' => 'required',
            'opt_calltype' => 'required',
            'starttime' => 'required',
            'endtime' => 'required',
            'call_transfer' => 'required'
        ];
        if($request->get('DT') !== '1' && $request->get('C2C') !== '1') {
            $rules['ivrlevel_id'] = 'required';
        }

        $messages = [
            'groupid.required' => 'Customer is required',
            'dept_name.required' => 'Department Name is required',
            'ivrlevel_id.required' => 'Ivr Level is required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $opeDept = ['resellerid' => $request->get('resellerid'),
                     'groupid' => $request->get('groupid'),
                     'ivrlevel_id'=> $request->get('ivrlevel_id'),
                     'dept_name'=> $request->get('dept_name'), 
                     'ivr_option' => $request->get('ivr_option'),
                     'misscallalert' => $request->get('misscallalert'),
                     'generateticket' => $request->get('generateticket'),
                     'opt_calltype' => $request->get('opt_calltype'),
                     'defaultaction' => $request->get('defaultaction'),
                     'default_sms' => $request->get('default_sms'),
                     'default_sms_no' => $request->get('default_sms_no'),
                     'sticky_agent' => $request->get('sticky_agent'),
                     'complaint' => $request->get('complaint'),
                     'sms_to_caller' => $request->get('sms_to_caller'),
                     'sms_to_operator' => $request->get('sms_to_operator'),
                     'starttime' => $request->get('starttime'),
                     'endtime' => $request->get('endtime'),
                     'call_transfer' => $request->get('call_transfer'),
                     'email_id' => $request->get('email_id'),
                     'phone_no' => $request->get('phone_no'),
                     'DT' => $request->get('DT'),
                     'C2C' => $request->get('C2C')
                    ];

            if(empty($request->get('id'))) {
                DB::table('operatordepartment')->insert($opeDept);
                $data['success'] = 'Operator department added successfully.';
            } else {
                DB::table('operatordepartment')
                    ->where('id', $request->get('id'))
                    ->update($opeDept);
                $data['success'] = 'Operator department updated successfully.';
            }
        } 
         return $data;
    }

    public function getOperator($id) {
        return $ope_dept = OperatorDepartment::find($id);
    }

    public function nonOperatorList(Request $request) {
    
        $requests = $request->all();
        $groupId = $request->get('customer');
        $query = DB::table('nonoperatordepartment')
         ->leftJoin('operatordepartment', 'nonoperatordepartment.departmentid', '=', 'operatordepartment.id')
         ->leftJoin('accountgroup', 'nonoperatordepartment.groupid', '=', 'accountgroup.id')
         ->leftJoin('resellergroup', 'nonoperatordepartment.resellerid', '=', 'resellergroup.id');
          if(Auth::user()->usertype == 'admin') {
        } elseif(Auth::user()->usertype == 'reseller') {
           $query->where('nonoperatordepartment.resellerid', Auth::user()->resellerid);
        } else {
            $query->where('nonoperatordepartment.groupid', Auth::user()->groupid);
        }
        
        if (isset($groupId)) {
            $query->where('nonoperatordepartment.groupid', $groupId);
        }

        $query->select('nonoperatordepartment.*', 'resellergroup.resellername', 'operatordepartment.dept_name', 'accountgroup.name');
        $nonoperatordept = $query->orderBy('id', 'desc')->get();
        $languages = DB::table('languages')->get();
        //dd($nonoperatordept);
        return view('operator.nonoperatordept_list', compact('nonoperatordept', 'languages', 'requests'));
    }

    public function getNonOperator($id) {
        return $non_opt = DB::table('nonoperatordepartment')
                ->leftJoin('operatordepartment', 'nonoperatordepartment.departmentid', '=', 'operatordepartment.id')
                ->where('nonoperatordepartment.id', $id)->select('nonoperatordepartment.*', 'operatordepartment.dept_name')->get();
    }

    public function addNonOperator(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'departmentid' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $nonDept = ['resellerid' => $request->get('resellerid'),
                     'groupid' => $request->get('groupid'),
                     'departmentid'=> $request->get('departmentid'),
                     'sms_to_caller'=> $request->get('sms_to_caller'), 
                     'sms_to_operator' => $request->get('sms_to_operator'),
                     'operator_no' => $request->get('operator_no'),
                     'generateticket' => $request->get('generateticket'),
                     'record_com' => $request->get('record_com'),
                     'operator_email' => $request->get('operator_email'),
                     'sms_template_caller' => $request->get('sms_template_caller'),
                     'sms_template_operator' => $request->get('sms_template_operator'),
                     'email_to_operator' => $request->get('email_to_operator'),
                     'caller_sms_template_id' => $request->get('caller_sms_template_id'),
                     'operator_sms_template_id' => $request->get('operator_sms_template_id'),
                    ];

            if(empty($request->get('id'))) {
                DB::table('nonoperatordepartment')->insert($nonDept);
                $data['success'] = 'Non Operator department added successfully.';
            } else {
                DB::table('nonoperatordepartment')
                    ->where('id', $request->get('id'))
                    ->update($nonDept);
                $data['success'] = 'Non Operator department updated successfully.';
            }
        } 
         return $data;
    }

    public function deleteNonOperator($id)
    {
        $res = DB::table('nonoperatordepartment')->where('id',$id)->delete();
        toastr()->success('Non Operator delete successfully.');
        return redirect()->route('NonOperatorList');
    }

    public function getDepartment($groupid) {
        return getDepartmentList($groupid);
    }

    public function addFiles(Request $request) {
         $lang = explode (",", $request->get('file_lang'));
         //dd($lang);
        // $validator = Validator::make($request->all(), [
        //     'groupid' => 'required',
        //     'ivr_level_name' => 'required',
        //     'ivr_level' => 'required',
        //     'ivroption' => 'required',
        //     'operator_dept' => 'required',
        // ]);    

        // if($validator->fails()) {
        //     $data['error'] = $validator->messages(); 
        // } else { 
            $vfilename=$request->get('nonopt_id');
            if (file_exists(config('constants.ivr_file')) && $lang) { 
                $file = config('constants.ivr_file');
                foreach($lang as $listOne) {
                    $list_1 = explode ("_", $listOne);
                    $files = $request->file($list_1[0]);
                    if(!empty($files)) {
                        $ext=substr($files->getClientOriginalName(),-4);
                        $newfilename=$list_1[1]."_".$vfilename."".$ext;
                        $ivrLanguage = [
                             'lang_id' => $list_1[0],
                             'nonopt_id' => $request->get('nonopt_id'),
                             'filename'=> $newfilename,
                             'orginalfilename'=> $files->getClientOriginalName(), 
                            ];

                    $files->move($file, $newfilename);
                    DB::table('ast_nooptfile_language')->insert($ivrLanguage);
                    }   
                } 
            }
            //$data['result'] = $accGroup;
            $data['success'] = 'Upload files successfully.';
        //} 
         return $data;
    }

    public function sms(Request $request) {
    
        $requests = $request->all();
        $groupId = $request->get('customer');
        $query = DB::table('sms_content')
         ->leftJoin('operatordepartment', 'sms_content.departmentid', '=', 'operatordepartment.id')
         ->leftJoin('accountgroup', 'sms_content.groupid', '=', 'accountgroup.id')
         ->leftJoin('resellergroup', 'sms_content.resellerid', '=', 'resellergroup.id');
        if(Auth::user()->usertype == 'admin') {
        } elseif(Auth::user()->usertype == 'reseller') {
           $query->where('sms_content.resellerid', Auth::user()->resellerid);
        } elseif(Auth::user()->usertype == 'groupadmin') {
            $query->where('sms_content.groupid', Auth::user()->groupid);
        } else {
            $query->where('sms_content.id', Auth::user()->id);
        }
        
        if (isset($groupId)) {
            $query->where('sms_content.groupid', $groupId);
        }

        $query->select('sms_content.*', 'resellergroup.resellername', 'operatordepartment.dept_name', 'accountgroup.name')->orderBy('id', 'desc');
        $sms = $query->get();
        return view('operator.sms_list', compact('sms', 'requests'));
    }

    public function addSms(Request $request) {
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'type' => 'required',
            'content' => 'required'
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $smsData = ['groupid' => $request->get('groupid'),
                     'type'=> $request->get('type'),
                     'sms_to'=> $request->get('sms_to'), 
                     'addtional_alert' => $request->get('addtional_alert'),
                     'content' => $request->get('content'),
                     'smstmpid' => $request->get('smstmpid'),
                    ];
            if(empty($request->get('id'))) {
                DB::table('sms_content')->insert($smsData);
                $data['success'] = 'Sms added successfully.';
            } else {
                DB::table('sms_content')
                    ->where('id', $request->get('id'))
                    ->update($smsData);
                $data['success'] = 'Sms updated successfully.';
            }
        } 
         return $data;
    }

    public function getSms($id) {
       return $sms = DB::table('sms_content')->where('id', $id)->get(); 
    }

    public function getIvr($groupid) {
        return getAccountgroupdetails($groupid);
    }

    
}
