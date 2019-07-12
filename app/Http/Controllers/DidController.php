<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Dids;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        //$dids = Dids::all();
        $dids = DB::table('dids')
            ->join('prigateway', 'dids.gatewayid', '=', 'prigateway.id')
            ->select('dids.*', 'prigateway.Gprovider')
            ->get();
        //dd($dids);
        return view('did.did_list', compact('dids'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addDid()
    {
        $did = new Dids();
        $prigateway = $did->get_prigateway();
        // $did_list = $did->get_did();
        // dd($did_list);
        return view('did.add_did', compact('prigateway'));
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
                'did' => $request->get('did'),
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
        $did = new Dids();
        $prigateway = $did->get_prigateway();
        $did = $did->findOrFail($id);
        //dd($did);
        return view('did.edit_did', compact('did', 'prigateway'));
    }

    public function update($id, Request $request)
    {
        $did = new Dids();
        $prigateway = $did->get_prigateway();
        $did = $did->findOrFail($id);
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
            return view('did.edit_did', compact('did', 'prigateway', 'messages'));
        } else {
            $dids = [
                        'did' => $request->get('did'),
                        'dnid_name'=> $request->get('dnid_name'),
                        'gatewayid'=> $request->get('gatewayid'),
                        'outgoing_gatewayid'=> $request->get('outgoing_gatewayid'),
                        'c2cpri'=> $request->get('c2cpri'),
                        'c2ccallerid'=> $request->get('c2ccallerid'),
                        'outgoing_callerid'=> $request->get('outgoing_callerid'),
                        'set_did_no'=> $request->get('set_did_no')
                    ];

            $did->fill($dids)->save();
            toastr()->success('Did update successfully.');
            return redirect()->route('DidList');
        }
        
    }

    public function destroy($id)
    {
        $user= Users::find($id);
        $user->delete();
        toastr()->success('User delete successfully.');
        return redirect()->route('UserList');
    }
}
