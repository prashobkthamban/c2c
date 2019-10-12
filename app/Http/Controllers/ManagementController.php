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
use File;

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

    public function ivrMenu() {
        $customers = DB::table('accountgroupdetails')
            ->select('accountgroupdetails.*', 'accountgroup.name', 'resellergroup.resellername' )
            ->where('accountgroupdetails.delete_status', '0')
            ->join('accountgroup', 'accountgroupdetails.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'accountgroupdetails.resellerid', '=', 'resellergroup.id')
            ->orderBy('id', 'desc')->paginate(10);
        $languages = DB::table('languages')->get();
        // dd($customers);
        return view('management.ivr_menu', compact('customers', 'languages'));
    }

    public function getIvrMenu($id) {
        return $ivr_menu = DB::table('accountgroupdetails')
            ->select('accountgroupdetails.*', 'ast_ivrmenu_language.*')
            ->where('accountgroupdetails.id', $id)
            ->join('ast_ivrmenu_language', 'accountgroupdetails.id', '=', 'ast_ivrmenu_language.ivr_menu_id')
            // ->leftJoin('resellergroup', 'accountgroupdetails.resellerid', '=', 'resellergroup.id')
            ->get(); 
        //dd($ivr_menu);
    }

    public function deleteIvr($id)
    {
        DB::table('accountgroupdetails')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('ivrMenu');
    }

    public function addIvrmenu(Request $request) {
        
        $lang = explode (",", $request->get('file_lang'));
        //dd($lang);
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'ivr_level_name' => 'required',
            'ivr_level' => 'required',
            'ivroption' => 'required',
            'operator_dept' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $accGroup = ['resellerid' => $request->get('resellerid'),
                     'groupid' => $request->get('groupid'),
                     'ivr_level_name'=> $request->get('ivr_level_name'),
                     'ivr_level'=> $request->get('ivr_level'), 
                     'ivroption' => $request->get('ivroption'),
                     'operator_dept' => $request->get('operator_dept'),
                    ];

            $ivr_menu_id = DB::table('accountgroupdetails')->insertGetId($accGroup);
            if (file_exists(config('constants.ivr_file'))) {
                $file = config('constants.ivr_file').'/'.$ivr_menu_id;

                if(!file_exists($file)) {
                    File::makeDirectory($file);
                }

                foreach($lang as $listOne) {
                    //echo $listOne;
                    $files = $request->file($listOne);
            
                    $ivrLanguage = ['ivr_menu_id' => $ivr_menu_id,
                             'lang_id' => $listOne,
                             'filename'=> $files->getClientOriginalName(),
                             'orginalfilename'=> $files->getClientOriginalName(), 
                            ];

                    $fileName = date('m-d-Y_hia').$files->getClientOriginalName();
                    $files->move($file, $fileName);
                    DB::table('ast_ivrmenu_language')->insert($ivrLanguage);
                } 
                //die;
                   // else {
                   //      foreach ($files as $key => $value) {
                   //          //echo $value->getClientOriginalExtension();
                   //          $fileName = date('m-d-Y_hia').$value->getClientOriginalName();
                   //          $value->move($file, $fileName);
                   //      }
                   // }
            }
            $data['result'] = $accGroup;
            $data['success'] = 'Menu added successfully.';
        } 
         return $data;
    }

    public function voiceFiles() { 
        $voicefiles = DB::table('did_voicefilesettings')
                    ->leftJoin('accountgroup', 'did_voicefilesettings.groupid', '=', 'accountgroup.id')
                    ->select('did_voicefilesettings.*', 'accountgroup.name')
                    ->paginate(10);
        $voicefilesnames = DB::table('voicefilesnames')->where('file_type', 'mainmenupress0')->pluck('filename', 'filename');
        $thank4calling = DB::table('voicefilesnames')->where('file_type', 'thank4caling')->pluck('filename', 'filename');
        $repeatoptions = DB::table('voicefilesnames')->where('file_type', 'repeatoptions')->pluck('filename', 'filename');
        $previousmenu = DB::table('voicefilesnames')->where('file_type', 'previousmenu')->pluck('filename', 'filename');
        $voicemailmsg = DB::table('voicefilesnames')->where('file_type', 'voicemailmsg')->pluck('filename', 'filename');
        $trasfringcall = DB::table('voicefilesnames')->where('file_type', 'trasfringcall')->pluck('filename', 'filename');
        $contactusoon = DB::table('voicefilesnames')->where('file_type', 'contactusoon')->pluck('filename', 'filename');
        $talktooperator9 = DB::table('voicefilesnames')->where('file_type', 'talktooperator9')->pluck('filename', 'filename');
        $noinput = DB::table('voicefilesnames')->where('file_type', 'noinput')->pluck('filename', 'filename');
        $wronginput = DB::table('voicefilesnames')->where('file_type', 'wronginput')->pluck('filename', 'filename');
        $nonworkinghours = DB::table('voicefilesnames')->where('file_type', 'nonworkinghours')->pluck('filename', 'filename');
        $transferingagent = DB::table('voicefilesnames')->where('file_type', 'transferingtodifferentagent')->pluck('filename', 'filename');
        $holiday = DB::table('voicefilesnames')->where('file_type', 'holiday')->pluck('filename', 'filename');
        $aombefore = DB::table('voicefilesnames')->where('file_type', 'aombefore')->pluck('filename', 'filename');
        $aomafter = DB::table('voicefilesnames')->where('file_type', 'aomafter')->pluck('filename', 'filename');
        $moh = DB::table('mohclassess')->pluck('classname', 'classname');
        //dd($voicefilesnames);
        return view('management.voicefiles', compact('voicefiles', 'voicefilesnames', 'thank4calling', 'repeatoptions', 'previousmenu', 'voicemailmsg', 'trasfringcall', 'contactusoon', 'talktooperator9', 'noinput', 'wronginput', 'nonworkinghours', 'moh', 'transferingagent', 'holiday', 'aombefore', 'aomafter'));
    }

    public function addVoicefile(Request $request) {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'did' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $voicefile = [
                     'groupid' => $request->get('groupid'),
                     'did'=> $request->get('did'),
                     'wfile'=> $request->get('wfile'), 
                     'welcomemsg' => 'welcomemsg',
                     'languagesection' => $request->get('languagesection'),
                     'flanguagesection' => 'flanguagesection',
                     'mainmenupress0' => $request->get('mainmenupress0'),
                     'thank4caling' => $request->get('thank4caling'),
                     'repeatoptions' => $request->get('repeatoptions'),
                     'previousmenu' => $request->get('previousmenu'),
                     'voicemailmsg' => $request->get('voicemailmsg'),
                     'trasfringcall' => $request->get('trasfringcall'),
                     'contactusoon' => $request->get('contactusoon'),
                     'talktooperator9' => $request->get('talktooperator9'),
                     'noinput' => $request->get('noinput'),
                     'wronginput' => $request->get('wronginput'),
                     'nonworkinghours' => $request->get('nonworkinghours'),
                     'moh' => $request->get('moh'),
                     'transferingtodifferentagent' => $request->get('transferingtodifferentagent'),
                     'holiday' => $request->get('holiday'),
                     'aombeforewelcome' => $request->get('aombefore'),
                     'aomafterwelcome' => $request->get('aomafter'),
                    ];

            if(empty($request->get('id'))) {
                DB::table('did_voicefilesettings')->insert($voicefile);
                $data['success'] = 'Voicefile added successfully.';
            } else {
                DB::table('did_voicefilesettings')
                    ->where('id', $request->get('id'))
                    ->update($voicefile);
                $data['success'] = 'Voicefile updated successfully.';
            }
        } 
         return $data;
    }

    public function getVoicefile($id) {
        return $voice_file = DB::table('did_voicefilesettings')->where('id', $id)->get();
    }

    public function generalFiles() { 
        $voicefiles = DB::table('voicefilesnames')->paginate(10);
        //dd($voicefiles);
        return view('management.generalfiles', compact('voicefiles'));
    }

    public function deleteFile($id)
    {
        DB::table('voicefilesnames')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('generalFiles');
    }

    public function mohListings() { 
        $moh = DB::table('MOHclassess')->orderBy('id', 'desc')->paginate(10);
        //dd($moh);
        return view('management.mohlistings', compact('moh'));
    }

    public function deleteMoh($id, $classname)
    {
        $file = config('constants.moh_file').'/'.$classname;
        if(file_exists($file)) {
            File::deleteDirectory($file);
        }
        DB::table('MOHclassess')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('mohListings');
    }

    public function addMoh(Request $request) {
        if(empty($request->get('id'))) {
            $validator = Validator::make($request->all(), [
                'classname' => 'required|alpha_dash|unique:mohclassess,classname',
            ]);
        } else {
             $validator = Validator::make($request->all(), [
                'classname' => 'required|alpha_dash',
            ]);
        }
        
        
        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $files = $request->file('moh_file');
        
            if (file_exists(config('constants.moh_file')) && !empty($files)) {
                $file = config('constants.moh_file').'/'.$request->get('classname');
               if(!file_exists($file)) {
                 File::makeDirectory($file);
                    foreach ($files as $key => $value) {
                        //echo $value->getClientOriginalExtension();
                        $fileName = date('m-d-Y_hia').$value->getClientOriginalName();
                        $value->move($file, $fileName);
                    }
               } else {
                    foreach ($files as $key => $value) {
                        //echo $value->getClientOriginalExtension();
                        $fileName = date('m-d-Y_hia').$value->getClientOriginalName();
                        $value->move($file, $fileName);
                    }
               }
            }
            //die;
            $moh = [
                     'classname' => $request->get('classname'),
                   ];

            if(empty($request->get('id'))) {
                DB::table('mohclassess')->insert($moh);
                $data['success'] = 'Moh added successfully.';
            } else {
                DB::table('mohclassess')
                    ->where('id', $request->get('id'))
                    ->update($moh);
                $data['success'] = 'Moh updated successfully.';
            }
        } 
         return $data;
    }

    public function getMoh($id) {
        return $moh = DB::table('MOHclassess')->where('id', $id)->get();
    }



}
