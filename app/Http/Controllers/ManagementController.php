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
use Illuminate\Http\JsonResponse;

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
            $file = $request->file('holiday_msg_file');
            //Display File Name
            // echo 'File Name: '.$file->getClientOriginalName();
            // echo '<br>';
        
            $date = !empty($request->get('date')) ? Carbon::parse($request->get('date'))->format('Y-m-d') : null;
            $fileName = '';
            if ($file) {
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
                //Move Uploaded File
                $destinationPath = '/var/lib/asterisk/sounds/IVRMANGER';
                $fileName = str_replace('-', '_', $date) . '_holiday_msg_file_' . Auth::user()->groupid . '_' . time() . '.' . $extension;
                $file->move($destinationPath, $fileName);
            }

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
        if (in_array(Auth::user()->usertype, ["admin","reseller"])) {
            $groupId = $request->get('customer');
            $groupIdArray = [$groupId];
            if(Auth::user()->usertype == "reseller" && empty($groupId)) {
                $groupIdArray = getResellerGroupAdminIds(Auth::user()->resellerid);
            }
        } else {
            $groupId = Auth::user()->groupid;
            $groupIdArray = [$groupId];
        }
        $date_to = $request->get('date_to');
        $date_from = $request->get('date_from');
        $date = $request->get('date');
        $call_no = $request->get('caller_number');
        $department = $request->get('department');

        $customers = getCustomers();
        $query = DB::table('voicemails')
            ->select('voicemails.*', 'accountgroup.name' )
            ->join('accountgroup', 'voicemails.groupid', '=', 'accountgroup.id');
            if (count($groupIdArray) > 0) {
                $query->whereIn('voicemails.groupid', $groupIdArray);
            }
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
			

        $voicemails = $query->orderBy('datetime','DESC')->paginate(25);
        return view('management.voicemail', compact('voicemails', 'customers', 'groupId', 'call_no', 'department', 'date'));
    }

    public function contacts() {
        return view('management.contacts');
    }

    public function contactsAjaxLoad(Request $request) {
        $type = $request->get('type');
        $searchText = $request->get('search')['value'];

        $sortOrder = $request->get('order')['0'];
        $columnArray = [
            '0' => ['contacts.fname'],
            '1' => ['contacts.lname'],
            '2' => ['contacts.phone'],
            '3' => ['contacts.email']
        ];
        $sortOrderArray = [];
        foreach ($columnArray[$sortOrder['column']] as $field) {
            $sortOrderArray[$field] = $sortOrder['dir'];
        }

        $limit = $request->get('length');
        $skip = $request->get('start');
        $draw = $request->get('draw');
        $data = DB::table('contacts')
                ->where('groupid', Auth::user()->groupid);
        $recordsTotal = $data->count();
        if(!empty($searchText)) {
            $searchText = strtolower(trim($searchText));
            $data->where(DB::raw('lower(contacts.fname)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(contacts.lname)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(contacts.phone)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(contacts.email)'), 'like', '%' . $searchText . '%')
            ;
        }
        $recordsFiltered = $data->count();

        if (count($sortOrderArray) > 0) {
            foreach ($sortOrderArray as $field => $order) {
                $data->orderBy($field, $order);
            }
        }

        if ($limit > 0) {
            $data->skip($skip)
                ->take($limit);
        }
        $results = $data->get();

        $dataArray = [];
        if(count($results) > 0) {
            foreach($results as $result) {
                $dataArray[] = [
                    'id' => $result->id,
                    'firstName' => $result->fname,
                    'lastName' => $result->lname,
                    'phone' => $result->phone,
                    'email' => $result->email
                ];
            }
        }

        $result = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $dataArray
        ];
        
        return new JsonResponse($result);
    }

    public function editContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

    public function voiceFiles(Request $request) {
        $requests = $request->all();
        $groupId = $request->get('customer');
        $customers = getCustomers();
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
        return view('management.voicefiles', compact('requests', 'customers', 'voicefiles', 'voicefilesnames', 'thank4calling', 'repeatoptions', 'previousmenu', 'voicemailmsg', 'trasfringcall', 'contactusoon', 'talktooperator9', 'noinput', 'wronginput', 'nonworkinghours', 'moh', 'transferingagent', 'holiday', 'aombefore', 'aomafter'));
    }

    public function voiceFilesAjaxLoad(Request $request) {
        $searchText = $request->get('search')['value'];

        $sortOrder = $request->get('order')['0'];
        $columnArray = [
            '0' => ['accountgroup.name'],
            '1' => ['did_voicefilesettings.welcomemsg'],
            '2' => ['did_voicefilesettings.flanguagesection'],
            '3' => ['did_voicefilesettings.did_number'],
            '4' => ['did_voicefilesettings.MOH']
        ];
        $sortOrderArray = [];
        foreach ($columnArray[$sortOrder['column']] as $field) {
            $sortOrderArray[$field] = $sortOrder['dir'];
        }

        $limit = $request->get('length');
        $skip = $request->get('start');
        $draw = $request->get('draw');

        $query = DB::table('did_voicefilesettings')
                    ->leftJoin('accountgroup', 'did_voicefilesettings.groupid', '=', 'accountgroup.id')
                    ->select('did_voicefilesettings.*', 'accountgroup.name');
        $recordsTotal = $query->count();
        if(!empty($searchText)) {
            $searchText = strtolower(trim($searchText));
            $query->where(DB::raw('lower(accountgroup.name)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(did_voicefilesettings.welcomemsg)'), 'like', '%' . $searchText . '%')
            ->orWhere(DB::raw('lower(did_voicefilesettings.did_number)'), 'like', '%' . $searchText . '%')
            ;
        }
        $recordsFiltered = $query->count();

        if (count($sortOrderArray) > 0) {
            foreach ($sortOrderArray as $field => $order) {
                $query->orderBy($field, $order);
            }
        }

        if ($limit > 0) {
            $query->skip($skip)
                ->take($limit);
        }
        $results = $query->get();

        $data = [];
        if ($results) {
            foreach ($results as $result) {
                $data[] = [
                    'id' => $result->id,
                    'name' => $result->name,
                    'welcomemsg' => $result->welcomemsg,
                    'flanguagesection' => $result->flanguagesection,
                    'did_number' => $result->did_number,
                    'MOH' => $result->MOH,
                ];
            }
        }

        return new JsonResponse([
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
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
            $didList = getDidList($request->get('groupid'));
            $didNumber = '';
            if (!empty($didList)) {
                foreach ($didList as $list) {
                    if ($list['id'] == $request->get('did')) {
                        $didNumber = $list['did'];
                        break;
                    }
                }
            }
            $wFile = $request->get('wfile');
            $voicefile = [
                     'groupid' => $request->get('groupid'),
                     'did'=> $request->get('did'),
		             'did_number'=> $didNumber,
                     'wfile'=> $wFile, 
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
                $this->saveVoicefile($welcomefile, $langfile, $old_welcomefile, $old_langfile, $voice_file_id, $groupid, $wFile);
                $data['success'] = 'Voicefile added successfully.';
            } else {
                DB::table('did_voicefilesettings')
                    ->where('id', $request->get('id'))
                    ->update($voicefile);
                $this->saveVoicefile($welcomefile, $langfile, $old_welcomefile, $old_langfile, $request->get('id'), $groupid, $wFile);
                $data['success'] = 'Voicefile updated successfully.';
            }
        } 
         return $data;
    }

    private function saveVoicefile($welcomefile, $langfile, $old_welcomefile, $old_langfile, $voice_file_id, $groupid, $wFile) {
        $fileName1 = $fileName2 = null;
        if($welcomefile != null) {
            $fileName1 = $voice_file_id.'_'.$groupid.'_'.str_replace(" ","_",$welcomefile->getClientOriginalName());
        }
        if($langfile != null) {
            $fileName2 = $voice_file_id.'_'.$groupid.'_language.'.$langfile->getClientOriginalExtension(); 
        }
        $files = [
            'welcomemsg' => ($wFile == 'PLAY') ? (isset($fileName1) ? $fileName1 : $old_welcomefile) : null,
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
        $moh = DB::table('mohclassess')->orderBy('id', 'desc')->paginate(100);
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
        $this->reloadMohConfigFile();
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

            $this->reloadMohConfigFile();
        } 
         return $data;
    }

    public function getMoh($id) {
        return $moh = DB::table('mohclassess')->where('id', $id)->get();
    }

    private function reloadMohConfigFile() {
        $contents = "";
        $entries = DB::table('mohclassess')->get();
        if ($entries) {
            foreach ($entries as $entry) {
                $className = $entry->classname;
                $contents .= "[" . $className . "]\n";
                $contents .= "mode=files\n";
                $contents .= "directory=" . config('constants.moh_file'). "/" . $className . "\n";
            }
        }
        file_put_contents('/etc/asterisk/musiconhold-asterconnect.conf', $contents);
        shell_exec("asterisk -rx 'moh reload'");
    }

}
