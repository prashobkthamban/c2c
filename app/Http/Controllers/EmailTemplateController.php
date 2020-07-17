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

        if (Auth::user()->usertype == 'groupadmin') {
            
            $list_emailtemplates = DB::table('email_template')
                    ->where('group_id',Auth::user()->groupid)
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'admin') {
            $list_emailtemplates = DB::table('email_template')                    
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'reseller') 
        {
            $groupid = DB::table('resellergroup')->where('id',Auth::user()->resellerid)->first();

            $de = json_decode($groupid->associated_groups);

            $list_emailtemplates = array();
            foreach ($de as $key => $de_gpid) {
                $list_emailtemplates[] = DB::table('email_template')
                    ->Leftjoin('accountgroup','accountgroup.id','email_template.group_id')
                    ->where('email_template.group_id',$de_gpid)
                    ->select('email_template.*','accountgroup.name as accountgroup_name')
                    ->orderBy('id', 'desc')
                    ->get();
            }
            
        }
        else{
            $list_emailtemplates = DB::table('email_template')
                    ->where('user_type','=','operator')
                    ->where('group_id',Auth::user()->groupid)
                    ->orderBy('id', 'desc')
                    ->get();    
        }
        //print_r(Auth::user());exit;
        
        return view('email_template.index',compact('list_emailtemplates','result'));
    }

    public function add()
    {
        return view('email_template.add');
    }

    public function store(Request $request)
    {
        //print_r($request->all());exit;

        $file = $request->file('attachment');
                
        if ($request->hasFile("attachment")) {
            $file = $request->file("attachment");
            $file->move("email_template/",$file->getClientOriginalName());
            $attachment = $file->getClientOriginalName();
        }
        else {
            $attachment = '';
        }

        $now = date("Y-m-d H:i:s");

        $add_mail = new MailTemplate([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'group_id' => Auth::user()->groupid,
                'name' => $request->get('name'),
                'subject' => $request->get('subject'),
                'body' => $request->get('mail_body'),
                'attachment' => $attachment,
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

        $file = $request->file('attachment');
                
        if ($request->hasFile("attachment")) {
            
            //unlink(public_path('product_images/'.$request->old_image));
            $file = $request->file("attachment");
            $file->move("email_template/",$file->getClientOriginalName());
        }

        if (empty($request->file('attachment'))) {
            
            $attachment = $request->get('old_attachment');
        }else{
            
           $attachment = $file->getClientOriginalName();
        }

        $edit_template = MailTemplate::find($id);
    
        $edit_template->name = $request->name;
        $edit_template->subject = $request->subject;
        $edit_template->body = $request->mail_body;
        $edit_template->attachment = $attachment ? $attachment : '';

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