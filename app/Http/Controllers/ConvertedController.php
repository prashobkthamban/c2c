<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Converted;

date_default_timezone_set('Asia/Kolkata'); 

class ConvertedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->leads = new Converted();
    }

    public function index(){

        $result = Converted::getReport();

        if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') 
        {
            $list_converteds = DB::table('converted')
                ->select('*')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        else
        {
            $list_converteds = DB::table('converted')
                ->where('user_id','=',Auth::user()->id)
                ->select('*')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        return view('cdr.list_converted',compact('list_converteds','result'));
    }

    public function store(Request $request)
    {

        //dd($request->all());exit;
        $list_converteds = DB::table('converted')
            ->select('*')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $pro = new Converted();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'gst_no' => 'required',
            'email' => 'required',
            'phone_no' => 'required'
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            return view('cdr.list_converted', compact('messages','list_converteds'));
        } else {
            $add_converted = new Converted([
                'user_id' => Auth::user()->id,
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'gst_no' => $request->get('gst_no'),
                'mobile_no' => $request->get('phone_no'),
                'email' => $request->get('email'),
                'address' => $request->get('address') ? $request->get('address') : '',
                'company_name' => $request->get('company_name_converted'),
            ]);

            //dd($add_converted);exit;
            $add_converted->save();
            toastr()->success('Converted added successfully.');
            return redirect()->route('ListConverted');
        }
    }

    public function edit(Request $request)
    {
        //print_r($request->get('myid'));
        $user = DB::table('converted')->where('converted.id', $request->get('myid'))->Leftjoin('cdrreport_lead','cdrreport_lead.id','=','converted.cdrreport_lead_id')->select('cdrreport_lead.first_name as cdr_firstname','cdrreport_lead.last_name as cdr_lastname','cdrreport_lead.email as cdr_email','cdrreport_lead.company_name as cdr_companyname','cdrreport_lead.phoneno as cdr_phn','converted.*')->first();
        //print_r($user);exit;
        echo json_encode($user);
        //exit;
    }

    public function update(Request $request)
    {
        //print_r($request->all());exit;
        $list_converteds = DB::table('converted')
            ->select('*')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        $id = $request->get('id');
        $pro = new Converted();
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'gst_no' => 'required',
            'email' => 'required',
            'phone_no' => 'required'
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            return view('cdr.list_converted', compact('messages','list_converteds'));
        } else {
                
                $edit_converted = Converted::find($id);
                
                $edit_converted->company_name = $request->company_name_converted;
                $edit_converted->first_name = $request->first_name;
                $edit_converted->last_name = $request->last_name;
                $edit_converted->gst_no = $request->gst_no;
                $edit_converted->mobile_no = $request->phone_no;
                $edit_converted->email = $request->email;
                $edit_converted->address = $request->address;

               /* print_r($edit_converted);exit();*/

            $edit_converted->save();
            toastr()->success('Converted updated successfully.');
            return redirect()->route('ListConverted');
        }
    }

    public function destroy($id)
    {
        DB::table('converted')->where('id',$id)->delete();
        toastr()->success('Converted delete successfully.');
        return redirect()->route('ListConverted');
    }
}




















?>