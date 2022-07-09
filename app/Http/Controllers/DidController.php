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

    public function index(Request $request) {
        $requests = $request->all();
        $groupId = $request->get('customer');
        $customers = getCustomers();
        $query = DB::table('dids')
            ->join('prigateway', 'dids.gatewayid', '=', 'prigateway.id')
            ->leftJoin('accountgroup', 'dids.assignedto', '=', 'accountgroup.id')
            ->select('dids.*', 'prigateway.Gprovider', 'accountgroup.name');
        if ($groupId) {
            $query->where('accountgroup.id', $groupId);
        }
        $dids = $query->orderBy('id', 'desc')
            ->paginate(10);
            //dd($dids);
        $did = new Dids();
        $prigateway = $did->get_prigateway();
        return view('did.did_list', compact('dids', 'prigateway', 'requests', 'customers'));
    }

    public function extra_did($id) {
        return $extra_dids = DB::table('extra_dids')
            ->where('did_id', $id)
            ->get();
    }

    public function add_extra_did(Request $request) {
        $validator = Validator::make($request->all(), [
            'did_no' => 'required',
            'did_name' => 'required',
        ]);  

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $extra_did_data = [
                'did_id' => $request->get('did_id'),
                'groupid' => $request->get('groupid'),
                'did_no'=> $request->get('did_no'),
                'did_name'=> $request->get('did_name'),
                'set_pri_callerid'=> $request->get('set_pri_callerid'),
                'pri_id'=> $request->get('pri_id'),
            ];

            DB::table('extra_dids')->insert(
                $extra_did_data
            );

            $data['success'] = 'Operator department added successfully.';
        }
        return $data;
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
            'rdins' => 'required',
        ]);
        $attributeNames = array(
           'rdins' => 'Mobile Number',     
        );
        $validator->setAttributeNames($attributeNames);

        if($validator->fails()) {
            $messages = $validator->messages();
            $prigateway = $did->get_prigateway(); 
            return view('did.add_did', compact('messages', 'prigateway'));
        } else {
  
            $did = new Dids([
                'did' => $request->get('did'),
                'rdins' => $request->get('rdins'),
                'dnid_name'=> $request->get('dnid_name'),
                'gatewayid'=> $request->get('gatewayid'),
                'outgoing_gatewayid'=> $request->get('outgoing_gatewayid'),
                'c2cpri'=> $request->get('c2cpri'),
                'c2ccallerid'=> $request->get('c2ccallerid'),
                'outgoing_callerid'=> $request->get('outgoing_callerid'),
                'set_did_no'=> $request->get('set_did_no'),
                'enable_failover_gateway'=> $request->get('enable_failover_gateway'),
                'failover_outgoing_gatewayid'=> $request->get('failover_outgoing_gatewayid'),
                'failover_outgoing_callerid'=> $request->get('failover_outgoing_callerid'),
            ]);

            //dd($did_data);
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
            'rdins' => 'required',
        ]);
        $attributeNames = array(
           'rdins' => 'Mobile Number',     
        );
        $validator->setAttributeNames($attributeNames);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            return view('did.edit_did', compact('did', 'prigateway', 'messages'));
        } else {
            $dids = [
                        'did' => $request->get('did'),
                        'rdins' => $request->get('rdins'),
                        'dnid_name'=> $request->get('dnid_name'),
                        'gatewayid'=> $request->get('gatewayid'),
                        'outgoing_gatewayid'=> $request->get('outgoing_gatewayid'),
                        'c2cpri'=> $request->get('c2cpri'),
                        'c2ccallerid'=> $request->get('c2ccallerid'),
                        'outgoing_callerid'=> $request->get('outgoing_callerid'),
                        'set_did_no'=> $request->get('set_did_no'),
                        'enable_failover_gateway'=> $request->get('enable_failover_gateway'),
                        'failover_outgoing_gatewayid'=> $request->get('failover_outgoing_gatewayid'),
                        'failover_outgoing_callerid'=> $request->get('failover_outgoing_callerid'),
                    ];

            $did->fill($dids)->save();
            toastr()->success('Did update successfully.');
            return redirect()->route('DidList');
        }
        
    }

    public function destroy($id)
    {
        DB::table('dids')->where('id',$id)->delete();
        DB::table('extra_dids')->where('did_id',$id)->delete();
        toastr()->success('Did delete successfully.');
        return redirect()->route('DidList');
    }
}
