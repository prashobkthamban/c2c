<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Reminder;
use App\Models\CdrTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
    }

    public function index(Request $request){
        //dd($request->all);
        $data['caller'] = $request->get('caller_number');
        $data['operator'] = $request->get('operator');
        $data['department'] = $request->get('department');
        $data['status'] = $request->get('status');
        $data['date_to'] = $request->get('date_to');
        $data['date_from'] = $request->get('date_from');
        $data['date'] = $request->get('date');
        $dept = Reminder::select('deptname')->where('deptname', '!=', '')->groupBy('deptname')->pluck('deptname', 'deptname');
        return view('home.reminder', ['result' => Reminder::getReport($data),'tags'=>CdrTag::getTag(), 'depts' => $dept, 'params' => $data]);
    }

    public function pbxextension() {
        $pbx_list = DB::table('pbx_chan_sip_extensions')
                    ->select('pbx_chan_sip_extensions.*', 'accountgroup.name' )
                    ->join('accountgroup', 'pbx_chan_sip_extensions.groupid', '=', 'accountgroup.id')
                    ->orderBy('id', 'desc')->paginate(10);
        return view('home.pbx_extension', ['pbx_list' => $pbx_list]);
    }

    public function getExtension($id) {
        return $pbx_list = DB::table('pbx_chan_sip_extensions')
                    ->where('id', $id)->get();
    }

    public function addExtension(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'did_num' => 'required',
            'extension' => 'required',
            'password' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $pbx = ['groupid' => $request->get('groupid'),
                     'did_num' => $request->get('did_num'),
                     'extension'=> $request->get('extension'),
                     'password'=> $request->get('password'),
                     'add'=> 1,
                    ];

            if(empty($request->get('id'))) {
                DB::table('pbx_chan_sip_extensions')->insert($pbx);
                $data['success'] = 'Account added successfully.';
            } else {
                DB::table('pbx_chan_sip_extensions')
                    ->where('id', $request->get('id'))
                    ->update($pbx);
                $data['success'] = 'Account updated successfully.';
            }
        } 
         return $data;
    }

    public function delete_pbxexten($id)
    {
        DB::table('pbx_chan_sip_extensions')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('PbxExtension');
    }

    public function pbxringgroups() {
        $pbx_list = DB::table('pbx_ringgroups')
                    ->select('pbx_ringgroups.*', 'accountgroup.name' )
                    ->join('accountgroup', 'pbx_ringgroups.groupid', '=', 'accountgroup.id')
                    ->orderBy('id', 'desc')->paginate(10);
        return view('home.pbx_ringgroup', ['pbx_list' => $pbx_list]);
    }

    public function addRinggroup(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'ringgroup' => 'required',
            'description' => 'required|regex:/^\S*$/u',
            'grptime' => 'integer|max:300',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $pbx = ['groupid' => $request->get('groupid'),
                     'ringgroup'=> $request->get('ringgroup'),
                     'description'=> $request->get('description'),
                     'grptime'=> $request->get('grptime'),
                     'members'=> $request->get('members'),
                     'strategy'=> $request->get('strategy'),
                     'add'=> 1
                    ];

            if(empty($request->get('id'))) {
                DB::table('pbx_ringgroups')->insert($pbx);
                $data['success'] = 'Account added successfully.';
            } else {
                DB::table('pbx_ringgroups')
                    ->where('id', $request->get('id'))
                    ->update($pbx);
                $data['success'] = 'Account updated successfully.';
            }
        } 
         return $data;
    }

    public function getRinggroup($id) {
        return DB::table('pbx_ringgroups')
                    ->where('id', $id)->get();
    }

    public function deleteRinggroup($id)
    {
        DB::table('pbx_ringgroups')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('PbxRingGroups');
    }

    public function pbxDid() {
        $pbx_did = DB::table('pbx_incoming')
                    ->select('pbx_incoming.*', 'accountgroup.name' )
                    ->join('accountgroup', 'pbx_incoming.groupid', '=', 'accountgroup.id')
                    ->orderBy('id', 'desc')->paginate(10);
        return view('home.pbx_did', ['pbx_did' => $pbx_did]);
    }

    public function getOptions($type, $groupid) {
        $data['extensions'] = getExtensions($type, $groupid);
        $data['ringgroups'] = getRinggroups($type, $groupid);
        return $data;
    }
    
    public function addPbxDid(Request $request) {
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'did' => 'required',
            'outdptname' => 'required',
            'indptname' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $dest_num = $request->get('dest_num');
            if($request->get('destination') == 'ringgroup') {
                $dest_num = $request->get('rrnumber');
                if(empty($request->get('rrnumber'))) {
                    DB::table('pbx_ringgroups')->insert(['ringgroup' => '11'.$request->get('did'), 'description' => 'DID-PRIMARYGROUP', 'members' => '10000000', 'groupid' => $request->get('groupid'), 'strategy' => 'ringall', 'add' => '1', 'grptime' => '60']);
                    $dest_num = '11'.$request->get('did');
                }
            }
            $pbxDid = ['groupid' => $request->get('groupid'),
                     'did' => $request->get('did'),
                     'destination' => $request->get('destination'),
                     'outdptname'=> $request->get('outdptname'),
                     'indptname'=> $request->get('indptname'),
                     'dest_num' => $dest_num,
                     'add' => 1
                    ];

            if(empty($request->get('id'))) {
                DB::table('pbx_incoming')->insert($pbxDid);
                $data['success'] = 'Account added successfully.';
            } else {
                unset($pbxDid['add']);
                $new_field = array('update' => 1);
                $pbxDid_1 = array_merge($pbxDid, $new_field);

                DB::table('pbx_incoming')
                    ->where('id', $request->get('id'))
                    ->update($pbxDid);
                $data['success'] = 'Account updated successfully.';
            }
        } 
         return $data;
    }

    public function deletePbxdid($id)
    {
        DB::table('pbx_incoming')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('PbxDid');
    }

    public function getPbxdid($id) {
        return DB::table('pbx_incoming')->where('id', $id)->get();
    }
    
}
