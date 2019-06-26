<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Dids;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;

class DidController extends Controller
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

    }

    public function index() {
        $dids = Dids::all();
        return view('did.did_list', compact('dids'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addDid()
    {
        return view('did.add_did');
    }

    public function store(Request $request)
    {
        $did = new Dids();
        $validator = Validator::make($request->all(), [
            'did' => 'required',
            'dnid_name' => 'required',
            'gatewayid' => 'required',
            'outgoing_gatewayid' => 'required',
            'c2cpri' => 'required',
            'c2ccallerid' => 'required',
            'outgoing_callerid' => 'required',
            'set_did_no' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            return view('did.add_did', compact('messages'));
        } else {
  
            $did_data = [
                'did' => $request->get('customer_name'),
                'dnid_name'=> $request->get('dnid_name'),
                'gatewayid'=> $request->get('gatewayid'),
                'outgoing_gatewayid'=> $request->get('outgoing_gatewayid'),
                'c2cpri'=> $request->get('c2cpri'),
                'c2ccallerid'=> $request->get('c2ccallerid'),
                'outgoing_callerid'=> $request->get('outgoing_callerid'),
                'set_did_no'=> $request->get('set_did_no')
            ];

            //dd($users);
            $did->save();
            toastr()->success('Did added successfully.');
            return redirect()->route('DidList');
        }
        
    }

    public function edit($id)
    {
        //dd($id);
        $did = Dids::findOrFail($id);
        //dd($did);
        return view('did.edit_did', compact('did'));
    }

    public function update($id, Request $request)
    {
        $user = Users::findOrFail($id);
        $users = array(
            'customer_name' => $request->get('customer_name'),
            'coperate_id'=> $request->get('coperate_id'),
            'start_date'=> Carbon::parse($request->get('start_date'))->format('Y-m-d'),
            'end_date'=> Carbon::parse($request->get('end_date'))->format('Y-m-d'),
            'status'=> $request->get('status'),
            'did'=> $request->get('did'),
            'multilanguage'=> $request->get('multilanguage'),
            'language'=> $request->get('language'),
            'record_call'=> $request->get('record_call'),
            'operator_call_count'=> $request->get('operator_call_count'),
            'sms_api_user'=> $request->get('sms_api_user'),
            'sms_api_password'=> $request->get('sms_api_password'),
            'sms_api_sender'=> $request->get('sms_api_sender'),
            'api'=> $request->get('api'),
            'cdr_api_key'=> $request->get('cdr_api_key'),
            'client_ip'=> $request->get('client_ip'),
            'cdr_tag'=> $request->get('cdr_tag'),
            'chanunavil_calls'=> $request->get('chanunavil_calls'),
            'conference_members'=> $request->get('conference_members'),
            'android_app'=> $request->get('android_app'),
            'portal_sms'=> $request->get('portal_sms'),
            'dial_stratergy'=> $request->get('dial_stratergy'),
            'sms_support'=> $request->get('sms_support'),
            'push_api_service'=> $request->get('push_api_service'),
            'pbx_extension'=> $request->get('pbx_extension')
        );
        //dd($users);
        $user->fill($users)->save();
        toastr()->success('User update successfully.');
        return redirect()->route('UserList');
    }

    public function destroy($id)
    {
        $user= Users::find($id);
        $user->delete();
        toastr()->success('User delete successfully.');
        return redirect()->route('UserList');
    }
}
