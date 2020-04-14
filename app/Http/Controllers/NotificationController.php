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
       // dd( Notification::getReport());
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
            'send_to_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ], [
            'send_to_id.required' => 'The Customer field is required.',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $sender = explode(',', $request->send_to_id);
            $notification = new Notification([
             'send_from_id' => Auth::user()->id,
             'send_to_id' => $sender[0],
             'title' => $request->get('title'),
             'extension'=> $request->get('extension'),
             'description'=> $request->get('description'),
             'grp_readstatus'=> '0',
             'adm_read_status'=> 1,
             'sendfromusertype'=> Auth::user()->usertype,
             'sendtousertype'=> $sender[1],
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
            $not = Notification::where('id', $request->get('not_id'))->get();
            if(Auth::user()->usertype == $not[0]->sendfromusertype) {
                Notification::where('id', $request->get('not_id'))->update(array('grp_readstatus' => '0'));
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
        $not = Notification::where('id', $request->get('view_id'))->get();
        if(Auth::user()->id == $not[0]->send_from_id) {
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

}
