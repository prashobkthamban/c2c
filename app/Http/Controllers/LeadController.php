<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Lead_Products;
use App\Models\CdrReport_Lead;
use App\Models\Product;
use App\Models\lead_stages;
use App\Models\Lead_Mail;
use App\Models\Lead_CallLog;
use App\Models\Lead_Msg;
use App\Models\Lead_Notes;
use App\Models\Converted;
use App\Models\Lead_activity;
use App\Models\Lead_Reminder;
use App\Models\Proposal;
use App\Models\Product_details;

use Illuminate\Support\Facades\Mail;

use File;

/*use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\ToModel;*/

use Excel;

date_default_timezone_set('Asia/Kolkata'); 

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->leads = new CdrReport_Lead();
    }

    public function index(){

        //print_r(Auth::user()->load('accountdetails')->accountdetails->crm);exit;
        $result = CdrReport_Lead::getReport();

        if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') 
        {
            $list_leads = DB::table('cdrreport_lead')
            ->where('user_id','=',Auth::user()->id)
            ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
            ->select('operatoraccount.opername','cdrreport_lead.*')
            ->latest('cdrreport_lead.id')
            ->paginate(10);

            //print_r(Auth::user()->id);exit;

            $level_1 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '1')->get()->count();
            $level_2 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '2')->get()->count();
            $level_3 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '3')->get()->count();
            $level_4 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '4')->get()->count();
            $level_5 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '5')->get()->count();
            $level_6_7 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '>=', '6')->get()->count();

            $products = Product::select('*')->get();

            $users_lists = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid', Auth::user()->groupid)
                        ->get();
        }
        else
        {
            $list_leads = DB::table('cdrreport_lead')
            ->where('user_id','=',Auth::user()->id)
            ->orWhere('operatorid','=',Auth::user()->operator_id)
            ->select('*')
            ->orderBy('id', 'DESC')
            ->paginate(10);

            /*print_r($list_leads);exit;*/

            $level_1 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '1')->get()->count();
            $level_2 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '2')->get()->count();
            $level_3 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '3')->get()->count();
            $level_4 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '4')->get()->count();
            $level_5 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '=', '5')->get()->count();
            $level_6_7 = DB::table('lead_stages')->where('user_id','=',Auth::user()->id)->where('levels', '>=', '6')->get()->count();

            $products = Product::select('*')->get();

            $users_lists = DB::table('operatoraccount')
                        ->select('operatoraccount.*')->where('groupid', Auth::user()->groupid)
                        ->get();
        }

        return view('cdr.all_leads',compact('list_leads','products','level_1','level_2','level_3','level_4','level_5','level_6_7','users_lists','result'));
    }

    public function addLead(Request $request)
    {

     $path = $request->file('csv_file')->getRealPath();

     $data = Excel::load($path)->get();
     //echo '<pre>';
    //print_r($data);

    //print_r($data->count());exit;
     if ($data->count() > 500) {
          toastr()->success('Please Enter data within 500 limit.');
        return redirect()->route('ListLeads');
     }
     else{

             if($data->count() > 0)
             {
              foreach($data->toArray() as $key => $value)
              {
                if (Auth::user()->id == $value['owner_name']) {
                    $operator_id = 0;
                    $owner_name = Auth::user()->usertype;
                }
                else{
                    $operator_id = $value['owner_name'];
                    $owner_name = 'operator';
                }
        
                $insert_data[] = array(
                 'first_name'  => $value['first_name'],
                 'last_name'   => $value['last_name'],
                 'company_name'   => $value['company_name'],
                 'email'    => $value['email'],
                 'phoneno' => $value['phoneno'],
                 'alt_phoneno' => $value['alt_phoneno'],
                 'total_amount'  => $value['total_amount'],
                 'owner_name' => $owner_name,
                 'operatorid' => $operator_id,
                 'lead_stage'   => $value['lead_stage']
                );
                DB::table('cdrreport_lead')->insert($insert_data);
                $id = DB::getPdo()->lastInsertId();
                $pro_data[] = array(
                        'cdrreport_lead_id' => $id,
                        'product_id' => $value['product_name'],
                        'quantity' => $value['product_qty'],
                        'pro_amount' => $value['product_amount'],
                        'subtotal_amount' => $value['subtotal_amount']
                    );
               
              }
              
              DB::table('lead_products')->insert($pro_data);
            }
            //return back()->with('success', 'Excel Data Imported successfully.');
            toastr()->success('Excel Data Imported successfully.');
            return redirect()->route('ListLeads');
     }

    }

    public function editLead(Request $request)
    {
    	$user = DB::table('cdrreport_lead')->where('cdrreport_lead.id', $request->get('myid'))->leftJoin('lead_products', 'lead_products.cdrreport_lead_id', '=', 'cdrreport_lead.id')->first();
        
        echo json_encode($user);
    }

    public function LeadProduct(Request $request)
    {
    	$lead_products = DB::table('lead_products')->where('cdrreport_lead_id', $request->get('myid'))->leftJoin('products', 'products.id', '=', 'lead_products.product_id')->get();
        //print_r($user);
        echo json_encode($lead_products);
    }

    public function update(Request $request)
    {
        if (Auth::user()->id == $request->get('owner_name')) {
            $operator_id = 0;
            $owner_name = Auth::user()->usertype;
        }
        else{
            $operator_id = $request->get('owner_name');
            $owner_name = 'operator';
        }

    	$id = $request->get('id');
    	//print_r($request->all());exit;
    	$edit_lead = CdrReport_Lead::find($id);
    
        $edit_lead->first_name = $request->first_name;
        $edit_lead->last_name = $request->last_name;
        $edit_lead->company_name = $request->company_name;
        $edit_lead->email = $request->email;
        $edit_lead->lead_stage = $request->lead_stage;
        $edit_lead->total_amount = $request->total_amount;
        $edit_lead->phoneno = $request->phoneno;
        $edit_lead->alt_phoneno = $request->alt_phoneno;
        $edit_lead->owner_name = $owner_name;
        $edit_lead->operatorid = $operator_id;

        //print_r($edit_lead);exit;
        $edit_lead->save();
        $pro = $request->get('products');
        if (empty($pro)) {
        	$count = 0;
        }else{
        	$count = count($request->get('products'));
        }
        //print_r($count);exit();
        DB::table('lead_products')->where('cdrreport_lead_id', '=', $id)->delete();    
        for ($i=0; $i < $count; $i++) { 
            $lead_product = new Lead_Products([
                'cdrreport_lead_id' => $id,
                'product_id' => $request->get('products')[$i],
                'quantity' => $request->get('quantity')[$i],
                'pro_amount' => $request->get('pro_amount')[$i],
                'subtotal_amount' => $request->get('sub_amount')[$i],
            ]); 
        $lead_product->save();              
        }   

        $stage = $request->get('lead_stage');

            if ($stage == 'New') {
                $lead_id = 1;
            }
            elseif ($stage == 'Contacted') {
                $lead_id = 2;
            }
            elseif ($stage == 'Interested') {
                $lead_id = 3;
            }
            elseif ($stage == 'Under review') {
                $lead_id = 4;
            }
            elseif ($stage == 'Demo') {
                $lead_id = 5;
            }
            elseif ($stage == 'Unqualified') {
                $lead_id = 6;
            }          
            else{
                $lead_id = 7;
            }

            $lead_stages = lead_stages::where('cdrreport_lead_id','=',$id)->get();
            $lead_stages[0]->levels = $lead_id;
            /*echo "<pre>";
            print_r($lead_stages[0]);exit;*/

            $lead_stages[0]->save();

        toastr()->success('Lead Updated successfully.');
        return redirect()->route('ListLeads');
    }

    public function ProductAmount(Request $request)
    {
    	$pro_amount = DB::table('products')->where('id', $request->get('pro_id'))->get();
        echo json_encode($pro_amount);
    }

    public function deleteLead($id)
    {
    	DB::table('cdrreport_lead')->where('id',$id)->delete();
    	DB::table('lead_products')->where('cdrreport_lead_id',$id)->delete();
        toastr()->success('Lead delete successfully.');
        return redirect()->route('ListLeads');
    }

    public function ViewLeadID($id)
    {
    	$message = '';
    	$lead = DB::table('cdrreport_lead')->where('id', $id)->get();
    	$lead_stages = DB::table('lead_stages')->where('cdrreport_lead_id', $id)->first();
    	$lead_mails = DB::table('lead_mail')->where('cdrreport_lead_id', $id)->get();
    	$call_logs = DB::table('lead_call_log')->where('cdrreport_lead_id', $id)->get();
    	$msgs = DB::table('lead_msg')->where('cdrreport_lead_id', $id)->get();
    	$notes = DB::table('lead_notes')->where('cdrreport_lead_id', $id)->orderBy('inserted_date', 'DESC')->get();
        $recent_activities = DB::table('lead_recent_activities')->where('cdrreport_lead_id', $id)->limit(6)->orderBy('inserted_date', 'DESC')->get();
        $proposal = DB::table('proposal')->where('proposal.cdrreport_lead_id', $id)->leftJoin('converted','converted.id','=','proposal.cutomer_id')->select('converted.first_name','converted.last_name','converted.email','converted.mobile_no','converted.id','proposal.*')->get();
        $products = Product::select('*')->get();
        if (Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'groupadmin') {
            $mail_template = DB::table('email_template')->where('user_type','=','admin')->orWhere('user_type','=','groupadmin')->select('id','name')->get();
            $sms_template = DB::table('sms_template')->where('user_type','=','admin')->orWhere('user_type','=','groupadmin')->select('id','name')->get();
        }else {
            $mail_template = DB::table('email_template')->select('id','name')->get();
            $sms_template = DB::table('sms_template')->select('id','name')->get();
        }
    	return view('cdr.parti_lead',compact('lead','id','lead_stages','message','lead_mails','call_logs','msgs','notes','recent_activities','mail_template','sms_template','products','proposal'));
    }

    public function LeadStages($lead_id,$id)
    {
    	$check_data = DB::table('lead_stages')->where('cdrreport_lead_id',$lead_id)->get();
    	//print_r($check_data);
        $now = date("Y-m-d H:i:s");

        if ($id == 1) {
            $stage = 'New';
        }
        elseif ($id == 2) {
            $stage = 'Contacted';
        }
        elseif ($id == 3) {
            $stage = 'Interested';
        }
        elseif ($id == 4) {
            $stage = 'Under review';
        }
        elseif ($id == 5) {
            $stage = 'Demo';
        }
        elseif ($id == 6) {
            $stage = 'Unqualified';
        }
        elseif ($id == 7) {
            $stage = 'Converted';
        }

        $update_stage_cdrlead = CdrReport_Lead::find($lead_id);

        $update_stage_cdrlead->lead_stage = $stage;

        $update_stage_cdrlead->save();

        //print_r($update_stage_cdrlead);exit;

    	if ($check_data->isempty()) {

    		$lead_stages = new lead_stages([
                'user_id' => Auth::user()->id,
                'cdrreport_lead_id' => $lead_id,
                'levels' => $id,
                'status' => 'active',
            ]); 

	        $lead_stages->save();

            $active = new Lead_activity([
                'activity_name' => 'lead',
                'cdrreport_lead_id' => $lead_id,
                'activity_data' => $id,
                'inserted_date' => $now,
            ]); 

            $active->save();

	        $message = toastr()->success('Lead Updated successfully.');
       	 	/*return redirect()->route('ListLeads');*/
       	 	return Redirect::back()->with('message');

    	}
    	else{
    		$lead_stages = lead_stages::where('cdrreport_lead_id','=',$lead_id)->first();
    		//print_r($lead_stages->status);exit;
    		
    		if ($lead_stages->status) {
    			$status = 'active';
    		}
    		else{
    			$status = 'inactive';
    		}
            
            $lead_stages->levels = $id;
            $lead_stages->status = $status;
            $lead_stages->updated_stages = $now;

            $lead_stages->save();

            $active = new Lead_activity([
                'activity_name' => 'lead',
                'cdrreport_lead_id' => $lead_id,
                'activity_data' => $id,
                'inserted_date' => $now,
            ]); 

            $active->save();
            $message = toastr()->success('Lead Updated successfully.');
       	 	/*return redirect()->route('ListLeads');*/
       	 	return Redirect::back()->with('message');
    	}
    }

    public function update_lead(Request $request, $id)
    {
    	//print_r($request->all());

    	$edit_lead = CdrReport_Lead::find($id);
    
        $edit_lead->first_name = $request->first_name;
        $edit_lead->last_name = $request->last_name;
        $edit_lead->company_name = $request->company_name;
        $edit_lead->email = $request->email;
        $edit_lead->department = $request->department ? $request->department : '';
        $edit_lead->authority = $request->authority ? $request->authority : 'no';
        $edit_lead->address = $request->address ? $request->address : '';
        $edit_lead->work = $request->work ? $request->work : '';
        $edit_lead->dnd = $request->dnd ? $request->dnd : 'no';

        //print_r($edit_lead);exit;
        $edit_lead->save();
        $message = toastr()->success('Lead Updated successfully.');
   	 	/*return redirect()->route('ListLeads');*/
   	 	return Redirect::back()->with('message');
    }

    public function Mail(Request $request)
    {
    	//print_r($request->all());exit;

    	$data = array(
            'body' => $request->get('mail_body', 'text/html'),
        );

    	$credential = array(
    		'from' => $request->get('from'),
    		'to' => $request->get('to'),
    		'subject' => $request->get('subject'),
    		'cc' => $request->get('cc') ? $request->get('cc') : '',
    		'bcc' => $request->get('bcc') ? $request->get('bcc') : '',
    	);

    	/*print_r($credential);exit;*/

       /* print_r($credential);*/

    	 Mail::send('cdr.email', $data, function ($message) use ($credential){

	        if ($credential['cc'] == '' && $credential['bcc'] == '') {
	        	$message->from($credential['from']);
	        	$message->to($credential['to'])->subject($credential['subject']);
	        }
	        elseif ($credential['cc'] == '') {
	        	$message->from($credential['from']);
	        	$message->to($credential['to'])->subject($credential['subject']);
	        	$message->cc($credential['bcc']);
	        }
	        else{
	        	$message->from($credential['from']);
	        	$message->to($credential['to'])->subject($credential['subject']);
	        	$message->cc($credential['cc']);
	        }
	    });

    	$now = date("Y-m-d H:i:s");

    	$add_mail = new Lead_Mail([
                'cdrreport_lead_id' => $request->get('lead_id'),
                'from' => $request->get('from'),
                'to'=> $request->get('to'),
                'cc'=> $request->get('cc') ? $request->get('cc') : '',
                'bcc'=> $request->get('bcc')? $request->get('bcc') : '',
                'subject'=> $request->get('subject') ? $request->get('subject') : '',
                'body'=> $request->get('mail_body'),
                'inserted_date' => $now,
            ]);

            //dd($add_mail);exit;
            $add_mail->save();
            $message = toastr()->success('Lead Updated successfully.');
   	 	/*return redirect()->route('ListLeads');*/
   	 	return Redirect::back()->with('message');
    }

    public function CallLog(Request $request)
    {
    	//print_r($request->all());
    	$now = date("Y-m-d H:i:s");

    	$add_call_log = new Lead_CallLog([
                'cdrreport_lead_id' => $request->get('lead_id'),
                'call_type' => $request->get('call_type'),
                'outcomes'=> $request->get('outcomes'),
                'associate_call'=> $request->get('associate_call'),
                'call_log_name'=> $request->get('call_log_name'),
                'notes'=> $request->get('notes'),
                'inserted_date' => $now,
            ]);

            //dd($add_call_log);exit;
            $add_call_log->save();
            $message = toastr()->success('Lead Updated successfully.');
   	 	/*return redirect()->route('ListLeads');*/
   	 	return Redirect::back()->with('message');
    }

    public function SendMsg(Request $request)
    {
    	//print_r($request->all());exit;
    	$now = date("Y-m-d H:i:s");

        $username = 'demosms';
        $apiKey = '624AD-63599';
        $apiRequest = 'Text';
        // Message details
        $numbers = $request->get('msg_to'); // Multiple numbers separated by comma
        $sender = 'DEMOAC';
        $message = $request->get('msg_text');
        // Route details
        $apiRoute = 'DND';
        // Prepare data for POST request
        $data = 'username='.$username.'&apikey='.$apiKey.'&apirequest='.$apiRequest.'&route='.$apiRoute.'&mobile='.$numbers.'&sender='.$sender."&message=".$message;
        // Send the GET request with cURL
        $url = 'http://smsdnd.voiceetc.co.in/sms-panel/api/http/index.php?'.$data;
        $url = preg_replace("/ /", "%20", $url);
        //print_r($url);
        $response = file_get_contents($url);
        // Process your response here
       // echo $response;exit();

    	$add_msg = new Lead_Msg([
                'cdrreport_lead_id' => $request->get('lead_id'),
                //'msg_from' => $request->get('msg_from'),
                'msg_to'=> $request->get('msg_to'),
                'message'=> $request->get('msg_text'),
                'inserted_date' => $now,
            ]);

            //dd($add_msg);exit;
            $add_msg->save();
            $message = toastr()->success('Lead Updated successfully.');
   	 	/*return redirect()->route('ListLeads');*/
   	 	return Redirect::back()->with('message');
    }

    public function Notes(Request $request)
    {
    	//print_r($request->all());exit;
    	$now = date("Y-m-d H:i:s");

    	$add_note = new Lead_Notes([
                'cdrreport_lead_id' => $request->get('lead_id'),
                'note' => $request->get('note_msg'),
                'inserted_date' => $now,
            ]);

            //dd($add_note);exit;
            $add_note->save();
            $active = new Lead_activity([
                'activity_name' => 'note',
                'cdrreport_lead_id' => $request->get('lead_id'),
                'activity_data' => $request->get('note_msg'),
                'inserted_date' => $now,
            ]); 

            $active->save();
            $message = toastr()->success('Notes Updated successfully.');
   	 	/*return redirect()->route('ListLeads');*/
   	 	return Redirect::back()->with('message');
    }

    public function EditNotes(Request $request)
    {
        //print_r($request->all());exit;

        $now = date("Y-m-d H:i:s");

        $edit_note = Lead_Notes::find($request->get('note_id'));
    
        $edit_note->note = $request->edit_note_msg;
        $edit_note->inserted_date = $now;

        //print_r($edit_note);exit;
        $edit_note->save();

        $active = new Lead_activity([
                'activity_name' => 'note',
                'cdrreport_lead_id' => $request->get('lead_id'),
                'activity_data' => $request->get('edit_note_msg'),
                'inserted_date' => $now,
            ]); 

        $active->save();
        $message = toastr()->success('Note Updated successfully.');
        /*return redirect()->route('ListLeads');*/
        return Redirect::back()->with('message');
    }

    public function NoteDelete($id)
    {
    	DB::table('lead_notes')->where('id',$id)->delete();
    	$message = toastr()->success('Notes Deleted successfully.');
		return Redirect::back()->with('message');
    }

    public function Unqui_reason(Request $request)
    {
    	//print_r($request->all());exit();
        $lead_id = $request->lead_id;
        
        $now = date("Y-m-d H:i:s");

    	$edit_lead = lead_stages::find($lead_id);
    
        $edit_lead->levels = $request->uni_val;
        $edit_lead->uniq_reason = $request->unq_reason;
        $edit_lead->updated_stages = $now;
        //print_r($edit_lead);exit;
        $edit_lead->save();
        $message = toastr()->success('Lead Updated successfully.');
   	 	/*return redirect()->route('ListLeads');*/
   	 	return Redirect::back()->with('message');
    }

    public function Converted(Request $request)
    {
        //print_r($request->all());exit();
        
        $lead_id = $request->lead_id;
        $now = date("Y-m-d H:i:s");

        $edit_lead = lead_stages::where('cdrreport_lead_id',$lead_id)->get();
        $update_edit = $edit_lead[0];
        $update_edit['levels'] = $request->con_val;
        $update_edit['updated_stages'] = $now;
        //print_r($edit_lead);exit;
        if ($update_edit->save()) {
            
            $add_converted = new Converted([
                'user_id' => Auth::user()->id,
                'cdrreport_lead_id' => $request->get('lead_id'),
                'first_name' => $request->get('first_name_c'),
                'last_name' => $request->get('last_name_c'),
                'gst_no' => $request->get('gst_no'),
                'mobile_no' => $request->get('mobile_no'),
                'email' => $request->get('email_c'),
                'address' => $request->get('address_c'),
                'company_name' => $request->get('company_name_converted'),
            ]);

            //dd($add_converted);exit;
            $add_converted->save();
            $message = toastr()->success('Stage Updated successfully.');
            return Redirect::back()->with('message');
        }

    }

    public function Reminder(Request $request)
    {
        //print_r($request->all());exit;
        $now = date("Y-m-d H:i:s");

        $add_reminder = new Lead_Reminder([
                'user_id' => Auth::user()->id,
                'cdrreport_lead_id' => $request->get('lead_id'),
                'date' => $request->get('startdate'),
                'time' => $request->get('starttime'),
                'title' => $request->get('title'),
                'task' => $request->get('task'),
                'inserted_date' => $now,
            ]);

            //dd($add_reminder);exit;
            $add_reminder->save();
            $active = new Lead_activity([
                'activity_name' => 'Reminder',
                'cdrreport_lead_id' => $request->get('lead_id'),
                'activity_data' => $request->get('task'),
                'inserted_date' => $now,
            ]); 

            $active->save();
            $message = toastr()->success('Notes Updated successfully.');
        return Redirect::back()->with('message');
    }

    public function selectmailtemplate(Request $request)
    {
        $id = $request->get('myid');
        $mail_template = DB::table('email_template')->where('id', $id)->get();
        echo json_encode($mail_template);
    }

    public function selectsmstemplate(Request $request)
    {
        $id = $request->get('myid');
        $sms_template = DB::table('sms_template')->where('id', $id)->get();
        echo json_encode($sms_template);
    }

    public function Assigned_Lead(Request $request)
    {
        //print_r($request->all());
        //exit();
        $id = $request->get('lead_id');
        $count_owner_name = count($request->get('owner_name'));
        //echo $count_owner_name;exit;

        for ($i=0; $i < $count_owner_name ; $i++) {

            $lead_details = CdrReport_Lead::find($id);
            $ass_to = $lead_details->replicate(); 
            $ass_to['operatorid'] = $request->get('owner_name')[$i];
            $ass_to->save();

            $last_inserted_id = DB::getPdo()->lastInsertId();
            
            $lead_details_products = Lead_Products::where('cdrreport_lead_id',$id)->get();
            $ass_to_products = $lead_details_products[0]->replicate();
            $ass_to_products['cdrreport_lead_id'] = $last_inserted_id; 
            $ass_to_products->save();

            $lead_stage = lead_stages::where('cdrreport_lead_id',$id)->get();
            $ass_lead = $lead_stage[0]->replicate();
            $ass_lead['cdrreport_lead_id'] = $last_inserted_id; 
            $ass_lead->save();
        }
        $message = toastr()->success('Lead Assigned successfully.');
        return Redirect::back()->with('message');
        
    }


    public function AddProposal(Request $request)
    {

        //print_r($request->all());exit;

        $add_proposal = new Proposal([
                'operator_id' => Auth::user()->operator_id ? Auth::user()->operator_id : '',
                'user_id' => Auth::user()->id,
                'subject' => $request->get('subject'),
                'cdrreport_lead_id' => $request->get('lead_id') ? $request->get('lead_id') : '',
                'cutomer_id' => $request->get('customer_id') ? $request->get('customer_id') : '',
                'date'=> $request->get('date'),
                'total_amount'=> $request->get('total_amount'),
                'grand_total' => $request->get('grand_total'),
                'discount' => $request->get('dis_val'),
                'total_tax_amount' => $request->get('total_tax'),
            ]);

            //dd($add_proposal);exit;
            $add_proposal->save();
            $id = DB::getPdo()->lastInsertId();

            $pro = $request->get('products');

            if (empty($pro)) {
                $count = 0;
            }else{
                 $count = count($request->get('products'));
            }
            //print_r($count);exit();

            $tax = $request->get('tax');
            //print_r($tax);exit;

            for ($i=0; $i < count($tax); $i++) { 
                $total_tax = implode(",", $tax);
            }
            
            for ($i=0; $i < $count; $i++) { 
                 $proposal_details = new Product_details([
                    'proposal_id' => $id,
                    'product_id' => $request->get('products')[$i],
                    'qty' => $request->get('quantity')[$i],
                    'rate' => $request->get('rate')[$i],
                    'tax' => $total_tax,
                    'amount' => $request->get('amount')[$i],
                ]); 
             $proposal_details->save();              
            } 

            $now = date("Y-m-d H:i:s");

            $active = new Lead_activity([
                'activity_name' => 'Proposal',
                'cdrreport_lead_id' => $request->get('lead_id'),
                'activity_data' => $request->get('subject'),
                'inserted_date' => $now,
            ]); 

            $active->save();           
            
            //print_r($id);exit;
            toastr()->success('Proposal added successfully.');
            return Redirect::back();
    }

    public function FilterData(Request $request)
    {
        //print_r($request->get('date_from'));exit();
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $lead = $request->get('lead');

        if ($date_from != '' && $date_to != '' && $lead != '') {

            $filter_data = DB::table('cdrreport_lead')
                ->where('inserted_date','>=', $date_from)
                ->where('inserted_date','<=', $date_to)
                ->where('lead_stage',$lead)
                ->leftJoin('operatoraccount','operatoraccount.id','=','cdrreport_lead.operatorid')
                ->select('operatoraccount.opername','cdrreport_lead.*')
                ->get();
        }
        echo json_encode($filter_data);
    }
}




















?>