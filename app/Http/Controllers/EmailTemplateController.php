<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\MailTemplate;

date_default_timezone_set('Asia/Kolkata'); 

class EmailTemplateController extends Controller
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
        $result = MailTemplate::getReport();

        if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') {
            
            $list_emailtemplates = DB::table('email_template')
                    ->where('user_type','=','admin')
                    ->orWhere('user_type','=','groupadmin')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }else{
            $list_emailtemplates = DB::table('email_template')
                    ->orderBy('id', 'desc')
                    ->paginate(10);    
        }
        
        return view('email_template.index',compact('list_emailtemplates','result'));
    }

    public function add()
    {
        return view('email_template.add');
    }

    public function store(Request $request)
    {
        //print_r($request->all());exit;
        $now = date("Y-m-d H:i:s");

        $add_mail = new MailTemplate([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'name' => $request->get('name'),
                'subject' => $request->get('subject'),
                'body' => $request->get('mail_body'),
                'inserted_date' => $now,
            ]);

            //dd($add_mail);exit;
            $add_mail->save();
            toastr()->success('Email Template added successfully.');
            return redirect()->route('EmailTemplateIndex');
    }

    public function edit($id)
    {
        $mail = DB::table('email_template')->where('id', $id)->first();
        return view('email_template.edit',compact('mail'));
    }

    public function update(Request $request,$id)
    {
        //print_r($request->all());exit;

        $edit_template = MailTemplate::find($id);
    
        $edit_template->name = $request->name;
        $edit_template->subject = $request->subject;
        $edit_template->body = $request->mail_body;

        $edit_template->save();
        
        toastr()->success('Email Template Updated successfully.');
        return redirect()->route('EmailTemplateIndex');
    }

    public function destroy($id)
    {
        DB::table('email_template')->where('id',$id)->delete();
        toastr()->success('Email Template Deleted successfully.');
        return redirect()->route('EmailTemplateIndex');
    }

}



?>