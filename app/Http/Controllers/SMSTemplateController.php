<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\SMSTemplate;

date_default_timezone_set('Asia/Kolkata'); 

class SMSTemplateController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->leads = new Converted();
    }*/

    public function index()
    {
        $result = SMSTemplate::getReport();

        if (Auth::user()->usertype == 'groupadmin') {

            $list_smstemplates = DB::table('sms_template')
                    ->where('group_id',Auth::user()->groupid)
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'admin') {
            $list_smstemplates = DB::table('sms_template')
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'reseller') 
        {
            $groupid = DB::table('resellergroup')->where('id',Auth::user()->resellerid)->first();

            $de = json_decode($groupid->associated_groups);

            $list_smstemplates = array();
            foreach ($de as $key => $de_gpid) {
                $list_smstemplates[] = DB::table('sms_template')
                    ->Leftjoin('accountgroup','accountgroup.id','sms_template.group_id')
                    ->where('sms_template.group_id',$de_gpid)
                    ->select('sms_template.*','accountgroup.name as accountgroup_name')
                    ->orderBy('id', 'desc')
                    ->get();
            }
            
        }
        else{

            $list_smstemplates = DB::table('sms_template')
                    ->where('user_type','=','operator')
                    ->where('group_id',Auth::user()->groupid)
                    ->orderBy('id', 'desc')
                    ->get();
        }
        

        return view('sms_template.index',compact('list_smstemplates','result'));
    }

    public function add()
    {
        return view('sms_template.add');
    }

    public function store(Request $request)
    {
        //print_r($request->all());exit;
        $now = date("Y-m-d H:i:s");

        $add_sms = new SMSTemplate([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'group_id' => Auth::user()->groupid,
                'name' => $request->get('name'),
                'body' => $request->get('sms_body'),
                'inserted_date' => $now,
            ]);

            //dd($add_sms);exit;
            $add_sms->save();
            toastr()->success('SMS Template added successfully.');
            return redirect()->route('SMSTemplateIndex');
    }

    public function edit($id)
    {
        $sms = DB::table('sms_template')->where('id', $id)->first();
        return view('sms_template.edit',compact('sms'));
    }

    public function update(Request $request,$id)
    {
        //print_r($request->all());exit;

        $edit_template = SMSTemplate::find($id);
    
        $edit_template->name = $request->name;
        $edit_template->body = $request->sms_body;

        $edit_template->save();
        
        toastr()->success('SMS Template Updated successfully.');
        return redirect()->route('SMSTemplateIndex');
    }

    public function destroy($id)
    {
        DB::table('sms_template')->where('id',$id)->delete();
        toastr()->success('SMS Template Deleted successfully.');
        return redirect()->route('SMSTemplateIndex');
    }

}



?>