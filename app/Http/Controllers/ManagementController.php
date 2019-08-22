<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect; 
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday;
use App\Models\Contact;

class ManagementController extends Controller
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

    public function holiday() {
        $holiday_list = Holiday::all()->where('groupid', 1);
        return view('management.holiday', compact('holiday_list'));
    }

    public function holidayStore(Request $request)
    {
        //echo "sds";die();
        $holiday = new Holiday();
        $holiday_list = Holiday::all()->where('groupid', 1);
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'reason' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages(); 
            //dd($messages);
            return view('management.holiday', compact('messages', 'holiday_list'));
        } else {
            $holiday = new Holiday([
                'date' => Carbon::parse($request->get('date'))->format('Y-m-d'),
                'reason'=> $request->get('reason'),
                'groupid'=> 1,
                'resellerid'=> 0,
            ]);

        //dd($holiday);
        $holiday->save();
        toastr()->success('Holiday added successfully.');
        } 
        return view('management.holiday', compact('holiday_list'));
        
    }

    public function delete_holiday($id)
    {
        $holiday = Holiday::find($id);
        $holiday->delete();
        toastr()->success('Holiday delete successfully.');
        return redirect()->route('holiday');
    }

    public function voicemail() {
        $voicemails = DB::table('voicemails')
            ->select('voicemails.*', 'accountgroup.name' )
            ->where('voicemails.groupid', 1)
            ->join('accountgroup', 'voicemails.groupid', '=', 'accountgroup.id')
            ->get();
        //dd($voicemails);
        return view('management.voicemail', compact('voicemails'));
    }

    public function contacts() {
        
        $contacts = Contact::all();
        //dd($contacts);
        return view('management.contacts', compact('contacts'));
    }

     public function editContact(Request $request)
    {
        echo 'ds';die();
        //$contacts = Contact::all();
        //dd($contacts);
        return view('management.contacts', compact('contacts'));
    }

    public function delete_contact($id)
    {
        $contact= Contact::find($id);
        $contact->delete();
        toastr()->success('Contact delete successfully.');
        return redirect()->route('Contacts');
    }



}
