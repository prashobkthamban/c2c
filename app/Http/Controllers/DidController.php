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
        $dids = DB::table('dids')
            ->join('prigateway', 'dids.gatewayid', '=', 'prigateway.id')
            ->leftJoin('accountgroup', 'dids.assignedto', '=', 'accountgroup.id')
            ->select('dids.*', 'prigateway.Gprovider', 'accountgroup.name')
            ->get();
            //dd($dids);
        $did = new Dids();
        $prigateway = $did->get_prigateway();
        return view('did.did_list', compact('dids', 'prigateway'));
    }

    public function extra_did($id) {
    // echo "sss";die();
        return $extra_dids = DB::table('extra_dids')
            ->where('did_id', $id)
            ->get();

        //dd($dids);
    }

    public function add_extra_did(Request $request) {
        $extra_did_data = [
                'did_id' => $request->get('did_id'),
                'did_no'=> $request->get('did_no'),
                'did_name'=> $request->get('did_name'),
                'set_pri_callerid'=> $request->get('set_pri_callerid'),
                'pri_id'=> $request->get('pri_id'),
            ];

        DB::table('extra_dids')->insert(
            $extra_did_data
        );    

        toastr()->success('Extra did added successfully.');
        return redirect()->route('DidList');
    }

    public function delete_extra_did($id)
    {
        $res = DB::table('extra_dids')->where('id',$id)->delete();
        return response()->json([
            'status' => $res
        ]);
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
