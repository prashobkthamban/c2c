<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Lead_Reminder;

date_default_timezone_set('Asia/Kolkata'); 

class ReminderShowController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->leads = new Converted();
    }*/

    public function index()
    {

        /*echo "<pre>";
        print_r($list_remainder);*/

        $time = date('H:i', strtotime("+30 minutes"));
        $date = date('Y-m-d');
        //echo $date."::".$time;

        foreach ($list_remainder as $key => $value) {
            
            if ($value->date == $date) {

                //echo "<br>";
                $cleantime=substr($value->time,0,5);
                //echo $cleantime;
                
                if ($cleantime == $time) {

                    //echo "Mail Sent";

                    $data = array(
                        'title' => $value->title,
                        'description' => $value->task,
                    );
                    
                    $credential = array(
                        'from' => 'prachi.itrd@gmail.com',
                        'to' => $value->email,
                        'subject' => 'Remainder',
                    );

                    Mail::send('cdr.reminder_email.blade', $data, function ($message) use ($credential){

                        $message->from($credential['from']);
                        $message->to($credential['to'])->subject($credential['subject']);
                    });   
                }
            }
        }

        //return view('sms_api.index',compact('list_smsapis'));
    }

}



?>