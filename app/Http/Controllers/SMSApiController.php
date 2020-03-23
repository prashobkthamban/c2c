<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\SMSApi;
use App\Models\EmailApi;

date_default_timezone_set('Asia/Kolkata'); 

class SMSApiController extends Controller
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
        $result = SMSApi::getReport();

        $list_smsapis = DB::table('sms_api')
                    ->where('user_id','=',Auth::user()->id)
                    ->orderBy('id', 'desc')
                    ->paginate(10);

        return view('sms_api.index',compact('list_smsapis','result'));
    }

    public function add()
    {
        return view('sms_api.add');
    }

    public function store(Request $request)
    {
        //print_r($request->all());exit;
        $now = date("Y-m-d H:i:s");

        $validator = Validator::make($request->all(), [
            'link' => 'required',
            'sender_id' => 'required',
            'username' => 'required',
            'password' => 'required',
            'username_email' => 'required',
            'password_email' => 'required',
            'smtp_host' => 'required',
            'port' => 'required',
        ]);    

        if ($validator->fails()) {
            $messages = $validator->messages();
            return view('sms_api.add', compact('messages'));
        } else {

            $add_smsapi = new SMSApi([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'link' => $request->get('link'),
                'sender_id' => $request->get('sender_id'),
                'username' => $request->get('username'),
                'password' => $request->get('password'),
                'type' => $request->get('type'),
                'inserted_date' => $now,
            ]);

            //dd($add_smsapi);exit;
            $add_smsapi->save();

            $add_emailapi = new EmailApi([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'smtp_host' => $request->get('smtp_host'),
                'port' => $request->get('port'),
                'username' => $request->get('username_email'),
                'password' => $request->get('password_email'),
                'type' => $request->get('type_email'),
                'inserted_date' => $now,
            ]);

            //dd($add_emailapi);exit;
            $add_emailapi->save();


            toastr()->success('SMS and Email Api added successfully.');
            return redirect()->route('SMSApiIndex');
        }
    }

    public function edit($id)
    {
        $sms = DB::table('sms_api')->where('id', $id)->first();
        $email = DB::table('email_api')->where('id', $id)->first();
        return view('sms_api.edit',compact('sms','email'));
    }

    public function update(Request $request,$id)
    {
        //print_r($request->all());exit;

        $edit_api = SMSApi::find($id);
    
        $edit_api->link = $request->link;
        $edit_api->sender_id = $request->sender_id;
        $edit_api->username = $request->username;
        $edit_api->password = $request->password;
        $edit_api->type = $request->type;

        $edit_api->save();

        $edit_emailapi = EmailApi::find($id);

        $edit_emailapi->smtp_host = $request->smtp_host;
        $edit_emailapi->port = $request->port;
        $edit_emailapi->username = $request->username_email;
        $edit_emailapi->password = $request->password_email;
        $edit_emailapi->type = $request->type_email;

        $edit_emailapi->save();
        
        toastr()->success('SMS Api Updated successfully.');
        return redirect()->route('SMSApiIndex');
    }

    public function destroy($id)
    {
        DB::table('sms_api')->where('id',$id)->delete();
        toastr()->success('SMS Api Deleted successfully.');
        return redirect()->route('SMSApiIndex');
    }

}



?>