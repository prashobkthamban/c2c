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
        $holiday_list = Holiday::all()->where('groupid', Auth::user()->groupid);
        return view('management.holiday', compact('holiday_list'));
    }

    public function holidayStore(Request $request)
    {
        if($request->get('format') == 'day') {
            $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'day' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
            'date' => 'required',
            'reason' => 'required',
            ]);
        }

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $holiday = [
                'date' => !empty($request->get('date')) ? Carbon::parse($request->get('date'))->format('Y-m-d') : null,
                'day' => $request->get('day'),
                'reason'=> $request->get('reason'),
                'groupid'=> Auth::user()->groupid,
                'resellerid'=> 0,
            ];
            DB::table('holiday')->insert($holiday); 
            $data['success'] = 'Holiday added successfully.';
        } 
       return $data; 
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
        $contacts = Contact::paginate(10);
        return view('management.contacts', compact('contacts'));
    }

    public function editContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]); 
        //dd($request->all());  

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $contact = [ 'fname' => $request->get('fname'),
                         'lname' => $request->get('lname'),
                         'email'=> $request->get('email'),
                         'phone'=> $request->get('phone'),
                         'groupid' => Auth::user()->groupid
                       ];
            if(empty($request->get('id'))) {
                DB::table('contacts')->insert($contact);
                $data['success'] = 'Contact added successfully.';
            } else {
                DB::table('contacts')
                    ->where('id', $request->get('id'))
                    ->update($contact);
                $data['success'] = 'Contact updated successfully.';
            } 
        } 
         return $data;
    }

    public function delete_contact($id)
    {
        $contact= Contact::find($id);
        $contact->delete();
        toastr()->success('Contact delete successfully.');
        return redirect()->route('Contacts');
    }

    public function ivrMenu() {
        $customers = DB::table('ivr_menu')
            ->select('ivr_menu.*', 'accountgroup.name', 'resellergroup.resellername' )
            ->where('ivr_menu.delete_status', '0')
            ->join('accountgroup', 'ivr_menu.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'ivr_menu.resellerid', '=', 'resellergroup.id')
            ->orderBy('id', 'desc')->paginate(10);
        $languages = DB::table('languages')->get();
        // dd($customers);
        return view('management.ivr_menu', compact('customers', 'languages'));
    }

    public function getIvrMenu($id) {
        return $ivr_menu = DB::table('ivr_menu')
            ->select('ivr_menu.*', 'ast_ivrmenu_language.*')
            ->where('ivr_menu.id', $id)
            ->join('ast_ivrmenu_language', 'ivr_menu.id', '=', 'ast_ivrmenu_language.ivr_menu_id')
            // ->leftJoin('resellergroup', 'ivr_menu.resellerid', '=', 'resellergroup.id')
            ->get(); 
        //dd($ivr_menu);
    }

    public function deleteIvr($id)
    {
        DB::table('ivr_menu')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('ivrMenu');
    }

    public function addIvrmenu(Request $request) {
        
        $lang = explode (",", $request->get('file_lang'));
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
            $vfilename=$request->get('groupid')."_".$request->get('ivr_level');
            $accGroup = ['resellerid' => (!empty($request->get('resellerid') ? $request->get('resellerid') : NULL)),
                     'groupid' => $request->get('groupid'),
                     'ivr_level_name'=> $request->get('ivr_level_name'),
                     'ivr_level'=> $request->get('ivr_level'), 
                     'ivroption' => $request->get('ivroption'),
                     'operator_dept' => $request->get('operator_dept'),
                     'voicefilename' => $vfilename
                    ];

            $ivr_menu_id = DB::table('ivr_menu')->insertGetId($accGroup);
            if (file_exists(config('constants.ivr_file')) && $lang) { 
                $file = config('constants.ivr_file');
                //dd($files);
                foreach($lang as $listOne) {
                    $list_1 = explode ("_", $listOne);
                    $files = $request->file($list_1[0]);
                    //dd($files);
                    if(!empty($files)) {
                        $ext=substr($files->getClientOriginalName(),-4);
                        $newfilename=$list_1[1]."_".$vfilename."".$ext;
                        $ivrLanguage = ['ivr_menu_id' => $ivr_menu_id,
                             'lang_id' => $listOne,
                             'filename'=> $newfilename,
                             'orginalfilename'=> $files->getClientOriginalName(), 
                            ];

                    $files->move($file, $newfilename);
                    DB::table('ast_ivrmenu_language')->insert($ivrLanguage);
                    }   
                } 
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
                    ->orderBy('id', 'desc')->paginate(10);
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
        $fileName1 = $fileName2 = '';
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'did' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $welcomefile = $request->file('welcomemsg');
            $langfile = $request->file('flanguagesection');
            if($request->file('welcomemsg') != null) {
               $fileName1 = $request->get('groupid').'_'.$welcomefile->getClientOriginalName();
            }
            if($request->file('flanguagesection') != null) {
               $fileName2 = $request->get('groupid').'_'.$langfile->getClientOriginalName(); 
            }
            
            $voicefile = [
                     'groupid' => $request->get('groupid'),
                     'did'=> $request->get('did'),
                     'wfile'=> $request->get('wfile'), 
                     'welcomemsg' => isset($fileName1) ? $fileName1 : $request->get('old_welcomemsg'),
                     'languagesection' => $request->get('languagesection'),
                     'flanguagesection' => isset($fileName2) ? $fileName2 : $request->get('old_flanguagesection'),
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
                $voice_file_id = DB::table('did_voicefilesettings')->insertGetId($voicefile);
                $this->uploadVoicefile($welcomefile, $langfile, $fileName1, $fileName2);
                $data['success'] = 'Voicefile added successfully.';
            } else {
                DB::table('did_voicefilesettings')
                    ->where('id', $request->get('id'))
                    ->update($voicefile);
                $this->uploadVoicefile($welcomefile, $langfile, $fileName1, $fileName2);
                $data['success'] = 'Voicefile updated successfully.';
            }
        } 
         return $data;
    }

    private function uploadVoicefile($welcomeFile, $langFile, $fileName1, $fileName2) {
        if (!empty($fileName1) && file_exists(config('constants.voice_welcome_file'))) {
            $file = config('constants.voice_welcome_file');
            $welcomeFile->move($file, $fileName1);
        }
        if (!empty($fileName2) && file_exists(config('constants.voice_lang_file'))) {
            $file = config('constants.voice_lang_file');
            $langFile->move($file, $fileName2);
        }
        return true;
    }

    public function getVoicefile($id) {
        return $voice_file = DB::table('did_voicefilesettings')->where('id', $id)->get();
    }

    public function generalFiles() { 
        $voicefiles = DB::table('voicefilesnames')->orderBy('id', 'desc')->paginate(10);
        $languages = DB::table('languages')->get();
        return view('management.generalfiles', compact('voicefiles', 'languages'));
    }

    public function deleteFile($id, $filename)
    {
        $file = config('constants.general_file').'/'.$filename;
        if(file_exists($file)) {
            File::deleteDirectory($file);
        }
        
        DB::table('voicefilesnames')->where('id', $id)->delete();
        toastr()->success('Record deleted successfully.');
        return redirect()->route('generalFiles');
    }

    public function addGeneralFile(Request $request) {
        
        $lang = explode (",", $request->get('file_lang'));
        //dd($request->get('file_lang'));
        $validator = Validator::make($request->all(), [
            'file_type' => 'required',
            'filename' => 'required|alpha_dash',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $generalFile = ['file_type' => $request->get('file_type'),
                     'filename' => $request->get('filename')
                    ];

            $general_file_id = DB::table('voicefilesnames')->insertGetId($generalFile);
            if (!empty($request->get('file_lang')) && file_exists(config('constants.general_file'))) {
                $file = config('constants.general_file').'/'.$request->get('filename');

                if(!file_exists($file)) {
                    File::makeDirectory($file);
                }

                foreach($lang as $listOne) {
                    $files = $request->file($listOne);
                    $fileName = date('m-d-Y_hia').$files->getClientOriginalName();
                    $files->move($file, $fileName);
                } 
            }
            $data['success'] = 'Menu added successfully.';
        } 
         return $data;
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
