<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.notification', ['result' => Notification::getReport()]);
    }

    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        toastr()->success('Notification delete successfully.');
        return redirect()->route('notification');
    }

    public function addNotification(Request $request) {
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $notification = new Notification(['groupid' => $request->get('groupid'),
                     'title' => $request->get('title'),
                     'extension'=> $request->get('extension'),
                     'description'=> $request->get('description'),
                     'grp_readstatus'=> 0,
                     'adm_read_status'=> 1,
                     'sendfromusertype'=> (Auth::user()->usertype == 'admin') ? 'admin' : 'groupadmin',
                     'sendtousertype'=> (Auth::user()->usertype == 'admin') ? 'groupadmin' : 'admin',
                     'fromusername'=> Auth::user()->username,
                    ]);

            $notification->save();
            $data['success'] = 'Notification added successfully.';
        } 
         return $data;
    }

    public function addSubNotification(Request $request) {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            if(Auth::user()->usertype == 'admin') {
                Notification::where('id', $request->get('not_id'))->update(array('grp_readstatus' => 0));
            } else {
                Notification::where('id', $request->get('not_id'))->update(array('adm_read_status' => '0'));
            }

            $subnotification = ['not_id' => $request->get('not_id'),
                     'username'=> Auth::user()->username,
                     'description'=> $request->get('description'),
                     'datetime'=> date('Y-m-d H:i:s'),
                    ];

            $sub_notify_id = DB::table('notifications_sub')->insertGetId($subnotification);
            if(!empty($sub_notify_id)) {
                $data['id'] = $request->get('not_id');
            }
            
        } 
         return $data;
    }

    public function updateStatus(Request $request) {
        $data['status'] = false;  

        if(Auth::user()->usertype == 'admin') {
            Notification::where('id', $request->get('view_id'))->update(array('adm_read_status' => $request->get('status')));
            $data['status'] = true;
        } else {
            Notification::where('id', $request->get('view_id'))->update(array('grp_readstatus' => $request->get('status')));
            $data['status'] = true;
        }
        return $data;
    }

    public function getNotification($id) {
        $result = Notification::select( 'notifications.*', 'notifications_sub.description as sub_description', 'notifications_sub.datetime as sub_datetime', 'notifications_sub.username as sub_username' )
            ->leftJoin('notifications_sub', 'notifications.id', '=', 'notifications_sub.not_id')
            ->where( 'notifications.id', $id )->get();
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
