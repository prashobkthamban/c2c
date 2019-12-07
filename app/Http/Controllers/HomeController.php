<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\CdrTag;

class HomeController extends Controller
{ 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('auth');
         if(!Auth::check()){
            return redirect('login');
         }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home.dashboard');
    }

    public function callSummary() {
        $date = date("Y-m-d");
        $result = DB::table('cdr')
            ->select('accountgroup.id', 'accountgroup.name', 'cdr.cdrid as calls', 'cdr.firstleg as total', 'cdr.secondleg as outgoing' )
            ->where('cdr.datetime', 'like', $date . '%')
            ->leftJoin('accountgroup', 'cdr.groupid', '=', 'accountgroup.id')
            ->orderBy('id', 'desc')->paginate(10);
            //dd($summary);
        return view('home.call_summary', compact('result'));
    }

    public function dashboardNote() {
        $result = DB::table('dashbord_annuounce')
            ->orderBy('id', 'desc')->paginate(10);
            //dd($result);
        return view('home.dashboard_note', compact('result'));
    }

    public function addAnnouncement(Request $request) 
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'msg' => 'required',
        ], [
            'msg.required' => 'Announcement field is required'
        ]);     

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $msg = ['user' => Auth::user()->username,
                     'msg'=> $request->get('msg'),
                     'date' => NOW()
                    ];

            DB::table('dashbord_annuounce')->insert($msg);
            $data['success'] = 'Announcement added successfully.';
        } 
         return $data;
    }

    public function deleteAnnouncement($id) {
        DB::table('dashbord_annuounce')->where('id',$id)->delete();
        toastr()->success('Announcement delete successfully.');
        return redirect()->route('dashboardNote');
    }

    public function cdrTags() {
        return view('home.cdrtags', ['result' => CdrTag::getReport()]);
    }

    public function deleteRecord($id, $name) {
        DB::table($name)->where('id',$id)->delete();
        toastr()->success('Record delete successfully.');
        return redirect()->route('cdrTags');
    }
}
