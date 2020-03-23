<?php
namespace App\Exports;
use App\Models\CdrReport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
class PostExport implements FromCollection
{
  public function collection()
  {
     $data = CdrReport::getReport();
        if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
        {
            $columns = 'DiD_num,Customer, Caller, Date,Totaltime,Talktime, Status, Credit, Department, Operator,OperatorNumber';
        }
        elseif(Auth::user()->usertype ==  'groupadmin')
        {
            $columns = 'DID_no,Caller , Date ,Totaltime ,Talktime , Status , Credit, Department,Call_tag, Operator,Assignedto';
        }
        elseif(Auth::user()->usertype ==  'operator')
        {
            $columns = 'DID_no,Caller , Date, Totaltime ,Talktime, Status ,Credit, Department,Call_tag,Assignedto ';
        }

        $result_array = array( explode(',',$columns));
        if(!empty($data))
        {
            foreach($data as $k=>$cdrr) {
                $array = array();
                if(Auth::user()->usertype ==  'admin' || Auth::user()->usertype == 'reseller')
                {
                    $array = array($cdrr->did_no,$cdrr->name,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg,$cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->opername ,$cdrr->phonenumber);
                }
                elseif(Auth::user()->usertype ==  'groupadmin')
                {
                    $array = array($cdrr->did_no,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg, $cdrr->status,($cdrr->creditused != '') ? $cdrr->creditused :"a" ,($cdrr->deptname != '') ? $cdrr->deptname : "s",($cdrr->tag != '' ) ? $cdrr->tag : "d",($cdrr->opername != '' ) ? $cdrr->opername : "f",($cdrr->assignedto != '') ? $cdrr->assignedto : "g");
                }
                elseif(Auth::user()->usertype ==  'operator')
                {
                    $array = array($cdrr->did_no,$cdrr->number ,$cdrr->datetime,$cdrr->firstleg,$cdrr->secondleg,$cdrr->status,$cdrr->creditused,$cdrr->deptname,$cdrr->tag,$cdrr->opername);
                    
                }
                //dd($array);
                $result_array[] = $array;
            }
        }
        return collect($result_array);
  }
}