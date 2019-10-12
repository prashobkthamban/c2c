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

    public function index(){
        return view('home.reminder', ['result' => Reminder::getReport(),'tags'=>CdrTag::getTag()]);
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
        //echo "sd";die;
        $pbx_did = DB::table('pbx_incoming')
                    ->select('pbx_incoming.*', 'accountgroup.name' )
                    ->join('accountgroup', 'pbx_incoming.groupid', '=', 'accountgroup.id')
                    ->orderBy('id', 'desc')->paginate(10);
        return view('home.pbx_did', ['pbx_did' => $pbx_did]);
    }
    
}
