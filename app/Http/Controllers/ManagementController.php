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
        $holiday_list = Holiday::where('groupid', Auth::user()->groupid)->orderBy('id','DESC')->paginate(10);
        return view('management.holiday', compact('holiday_list'));
    }

    public function holidayStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'reason' => 'required',
        ]);

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $file=$request->file('holiday_msg_file');
            //Display File Name
            // echo 'File Name: '.$file->getClientOriginalName();
            // echo '<br>';
        
            //Display File Extension
            $extension = $file->getClientOriginalExtension();
            if(!in_array($extension, ['gsm', 'wav'])) {
                return ['error' => ['error' => ['Only .gsm or .wav files allowed']]];
            }
        
            //Display File Real Path
            // echo 'File Real Path: '.$file->getRealPath();
            // echo '<br>';
        
            //Display File Size
            $fileSize = $file->getSize();
            if($fileSize) {
                // return ['error' => ['error' => ['Only .gsm or .wav files allowed']]];
            }
        
            //Display File Mime Type
            // echo 'File Mime Type: '.$file->getMimeType();
            $date = !empty($request->get('date')) ? Carbon::parse($request->get('date'))->format('Y-m-d') : null;
            //Move Uploaded File
            $destinationPath = '/var/lib/asterisk/sounds/IVRMANGER';
            $fileName = str_replace('-', '_', $date) . '_holiday_msg_file_' . Auth::user()->groupid . '_' . time() . '.' . $extension;
            $file->move($destinationPath, $fileName);

            $holiday = [
                'date' => $date,
                'holidaymsg' => $fileName,
                'calltransferto' => $request->get('call_transfer_to'),
                'reason'=> $request->get('reason'),
                'groupid'=> Auth::user()->groupid,
                'resellerid'=> 0,
            ];
            DB::table('holiday')->insert($holiday); 
            $data['success'] = 'Holiday added successfully.';
        } 
       return $data; 
    }

    public function voicemail(Request $request) {
        $date_to = $request->get('date_to');
        $date_from = $request->get('date_from');
        $date = $request->get('date');
        $call_no = $request->get('caller_number');
        $department = $request->get('department');

        $query = DB::table('voicemails')
            ->select('voicemails.*', 'accountgroup.name' )
            ->where('voicemails.groupid', Auth::user()->groupid)
            ->join('accountgroup', 'voicemails.groupid', '=', 'accountgroup.id');
            if(!empty($call_no)) {
                $query->where('voicemails.callerid', 'LIKE', '%' . $call_no . '%');
            }
            if(!empty($department)) {
                $query->where('voicemails.departmentname','like','%'.$department.'%');
            }
            if(!empty($date)) {
                if($date == 'today')
                    $date_from = $date_to = date("Y-m-d");
                elseif ($date == 'yesterday')
                    $date_from = $date_to = date("Y-m-d", strtotime("-1 day"));
                elseif ($date == 'week') {
                    $date_from = date("Y-m-d", strtotime("-7 day"));
                    $date_to = date("Y-m-d");
                }
                elseif($date == 'month') {
                    $date_from = date("Y-m-d", strtotime("-1 month"));
                    $date_to = date("Y-m-d");
                } 
                elseif($date == 'custom') {
                    if($date_from != '')
                        $date_from = date('Y-m-d',strtotime($date_from));
                    if($date_to != '')
                        $date_to = date('Y-m-d',strtotime($date_to));
                }
                $query->whereBetween('datetime',[$date_from.' 00:00:00',$date_to.' 23:59:59']);
            }
			

        $voicemails = $query->orderBy('datetime','DESC')->get();
        return view('management.voicemail', compact('voicemails', 'call_no', 'department', 'date'));
    }

    public function contacts() {
        $contacts = Contact::getContacts(Auth::user()->groupid);
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
        //dd($data);
        return $data;
    }

    public function ivrMenu(Request $request) {
        $requests = $request->all();
        $groupId = $request->get('customer');
        $query = DB::table('ivr_menu')
            ->select('ivr_menu.*', 'accountgroup.name', 'resellergroup.resellername', 'operatoraccount.opername as failOverOperatorName')
            ->where('ivr_menu.delete_status', '0')
            ->join('accountgroup', 'ivr_menu.groupid', '=', 'accountgroup.id')
            ->leftJoin('resellergroup', 'ivr_menu.resellerid', '=', 'resellergroup.id')
            ->leftJoin('operatoraccount', 'operatoraccount.id', 'ivr_menu.failover_dest');
        if (isset($groupId)) {
            $query->where('ivr_menu.groupid', $groupId);
        }
        $customers = $query->orderBy('id', 'desc')->get();
        $languages = DB::table('languages')->get();
        //dd($customers);
        return view('management.ivr_menu', compact('customers', 'languages', 'requests'));
    }

    public function getIvrMenu($id) {
        return $ivr_menu = DB::table('ivr_menu')
            ->select('ivr_menu.*', 'resellergroup.resellername','ast_ivrmenu_language.ivr_menu_id', 'ast_ivrmenu_language.lang_id', 'ast_ivrmenu_language.filename', 'ast_ivrmenu_language.orginalfilename')
            ->where('ivr_menu.id', $id)
            ->leftJoin('ast_ivrmenu_language', 'ivr_menu.id', '=', 'ast_ivrmenu_language.ivr_menu_id')
            ->leftJoin('resellergroup', 'ivr_menu.resellerid', '=', 'resellergroup.id')
            ->get(); 
    }

    public function addIvrmenu(Request $request) {
        
        $lang = explode (",", $request->get('file_lang'));
        $validator = Validator::make($request->all(), [
            'groupid' => 'required',
            'ivr_level_name' => 'required',
            'ivr_level' => 'required',
            'ivroption' => 'required',
            'failover_operator_id' => 'required',
            'operator_dept' => 'required',
        ]);    

        if($validator->fails()) {
            $data['error'] = $validator->messages(); 
        } else {
            $vfilename=$request->get('groupid')."_".$request->get('ivr_level');
            $accGroup = ['resellerid' => $request->get('resellerid'),
                'groupid' => $request->get('groupid'),
                'ivr_level_name'=> $request->get('ivr_level_name'),
                'ivr_level'=> $request->get('ivr_level'), 
                'ivroption' => $request->get('ivroption'),
                'failover_dest' => $request->get('failover_operator_id'),
                'operator_dept' => $request->get('operator_dept'),
                'voicefilename' => $vfilename
            ];

            if(empty($request->get('id'))) { 
                $ivr_menu_id = DB::table('ivr_menu')->insertGetId($accGroup);
            } else {
                DB::table('ivr_menu')->where('id', $request->get('id'))->update($accGroup);
                $ivr_menu_id = $request->get('id');
            }
           
            if (file_exists(config('constants.ivr_file')) && $lang) { 
                $file = config('constants.ivr_file');
                foreach($lang as $listOne) {
                    $list_1 = explode ("_", $listOne);
                    $files = $request->file($list_1[0]);
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
            $voicefile = [
                     'groupid' => $request->get('groupid'),
                     'did'=> $request->get('did'),
		     'did_number'=> $request->get('did_number'),
                     'wfile'=> $request->get('wfile'), 
                     'languagesection' => $request->get('languagesection'),
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
                    $welcomefile = $request->file('welcomemsg');
                    $langfile = $request->file('flanguagesection');
                    $old_welcomefile = $request->get('old_welcomemsg');
                    $old_langfile = $request->get('old_flanguagesection');
                    $groupid = $request->get('groupid');
              //dd($voicefile);    
            if(empty($request->get('id'))) {
                $voice_file_id = DB::table('did_voicefilesettings')->insertGetId($voicefile);
                $this->saveVoicefile($welcomefile, $langfile, $old_welcomefile, $old_langfile, $voice_file_id, $groupid);
                $data['success'] = 'Voicefile added successfully.';
            } else {
                DB::table('did_voicefilesettings')
                    ->where('id', $request->get('id'))
                    ->update($voicefile);
                $this->saveVoicefile($welcomefile, $langfile, $old_welcomefile, $old_langfile, $request->get('id'), $groupid);
                $data['success'] = 'Voicefile updated successfully.';
            }
        } 
         return $data;
    }

    private function saveVoicefile($welcomefile, $langfile, $old_welcomefile, $old_langfile, $voice_file_id, $groupid) {
        $fileName1 = $fileName2 = null;
        if($welcomefile != null) {
            $fileName1 = $voice_file_id.'_'.$groupid.'_'.$welcomefile->getClientOriginalName();
        }
        if($langfile != null) {
            $fileName2 = $voice_file_id.'_'.$groupid.'_'.$langfile->getClientOriginalName(); 
        }
        $files = [
            'welcomemsg' => isset($fileName1) ? $fileName1 : $old_welcomefile,
            'flanguagesection' => isset($fileName2) ? $fileName2 : $old_langfile
        ];
        DB::table('did_voicefilesettings')
            ->where('id', $voice_file_id)
            ->update($files);
        $this->uploadVoicefile($welcomefile, $langfile, $fileName1, $fileName2);
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
                $filepath = config('constants.general_file');

               /* if(!file_exists($file)) {
                    File::makeDirectory($file);
                }*/
                foreach($lang as $listOne) {
			//dd($listOne);die;
                    $files = $request->file($listOne);
                    $fileName = $listOne.'_'.$request->get('filename').'.'.$request->file($listOne)->getClientOriginalExtension();
                    $files->move($filepath, $fileName);
                } 
            }
            $data['success'] = 'Menu added successfully.';
        } 
         return $data;
    }

    public function mohListings() { 
        $moh = DB::table('mohclassess')->orderBy('id', 'desc')->paginate(10);
        //dd($moh);
        return view('management.mohlistings', compact('moh'));
    }

    public function deleteMoh($id, $classname)
    {
        $file = config('constants.moh_file').'/'.$classname;
        if(file_exists($file)) {
            File::deleteDirectory($file);
        }
        DB::table('mohclassess')->where('id', $id)->delete();
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
        return $moh = DB::table('mohclassess')->where('id', $id)->get();
    }



}
