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

        if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') {

            $list_smstemplates = DB::table('sms_template')
                    ->where('user_type','=','admin')
                    ->orWhere('user_type','=','groupadmin')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }else{

            $list_smstemplates = DB::table('sms_template')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
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