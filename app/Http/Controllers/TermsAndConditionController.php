<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Terms_Condition_Invoice;
use App\Models\Terms_Condition_Proposal;

date_default_timezone_set('Asia/Kolkata');

class TermsAndConditionController extends Controller
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
        $result = Terms_Condition_Invoice::getReport();

        //print_r($result);exit;

        $list_tc_invoices = DB::table('terms_condition_invoice')
                    ->where('terms_condition_invoice.user_id','=',Auth::user()->id)
                    ->LeftJoin('terms_condition_proposal','terms_condition_proposal.id','=','terms_condition_invoice.id')
                    ->select('terms_condition_proposal.name as tc_pro_name','terms_condition_invoice.name as tc_inv_name','terms_condition_invoice.id')
                    //->orderBy('terms_condition_invoice.id', 'desc')
                    ->paginate(10);

        return view('terms_condition.index',compact('list_tc_invoices','result'));
    }

    public function add()
    {
        $list_tc_invoices = DB::table('terms_condition_invoice')
                    ->LeftJoin('terms_condition_proposal','terms_condition_proposal.id','=','terms_condition_invoice.id')
                    ->where('terms_condition_invoice.user_id','=',Auth::user()->id)
                    ->where('terms_condition_proposal.user_id','=',Auth::user()->id)
                    ->select('terms_condition_proposal.name as tc_pro_name','terms_condition_invoice.name as tc_inv_name','terms_condition_invoice.id')->first();

        /*echo "<pre>";
        print_r($list_tc_invoices);exit;*/
        return view('terms_condition.add',compact('list_tc_invoices'));
    }

    public function store(Request $request)
    {

        //print_r($request->all());exit;

        $now = date("Y-m-d H:i:s");

        if (!empty(DB::table('terms_condition_invoice')->where('id', $request->uid)->first()))
        {
            $edit_tc_invoice = Terms_Condition_Invoice::find($request->uid);

            $edit_tc_invoice->name = $request->mail_body_invoice;


            $edit_tc_invoice->save();

            $edit_tc_proposal = Terms_Condition_Proposal::find($request->uid);

            $edit_tc_proposal->name = $request->mail_body_proposal;

            $edit_tc_proposal->save();

            toastr()->success('T&C Updated successfully.');
        }
        else
        {

            $invoice = new Terms_Condition_Invoice([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'name' => $request->get('mail_body_invoice'),
                'inserted_date' => $now,
            ]);

            //dd($invoice);exit;
            $invoice->save();

            $proposal = new Terms_Condition_Proposal([
                'user_id' => Auth::user()->id,
                'user_type' => Auth::user()->usertype ? Auth::user()->usertype : 'user',
                'name' => $request->get('mail_body_proposal'),
                'inserted_date' => $now,
            ]);

            //dd($proposal);exit;
            $proposal->save();

            toastr()->success('T&C added successfully.');
        }


        return redirect()->route('TermsAndConditionAdd');
    }

    public function edit($id)
    {
        $invoice = DB::table('terms_condition_invoice')->where('id', $id)->first();
        $proposal = DB::table('terms_condition_proposal')->where('id', $id)->first();
        return view('terms_condition.edit',compact('invoice','proposal'));
    }

    public function update(Request $request,$id)
    {
        //print_r($request->all());exit;

        $edit_tc_invoice = Terms_Condition_Invoice::find($id);

        $edit_tc_invoice->name = $request->mail_body_invoice;


        $edit_tc_invoice->save();

        $edit_tc_proposal = Terms_Condition_Proposal::find($id);

        $edit_tc_proposal->name = $request->mail_body_proposal;

        $edit_tc_proposal->save();

        toastr()->success('T&C Updated successfully.');
        return redirect()->route('TermsAndConditionIndex');
    }

    public function destroy($id)
    {
        DB::table('terms_condition_invoice')->where('id',$id)->delete();
        DB::table('terms_condition_proposal')->where('id',$id)->delete();
        toastr()->success('T&C Deleted successfully.');
        return redirect()->route('TermsAndConditionIndex');
    }

}



?>
