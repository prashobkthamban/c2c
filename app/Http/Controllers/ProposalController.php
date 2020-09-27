<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Proposal;
use App\Models\Converted;
use App\Models\Product;
use App\Models\Product_details;
use App\Models\Lead_activity;
use App\Models\Invoice;

date_default_timezone_set('Asia/Kolkata');

class ProposalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->leads = new Converted();
    }

    public function index(){

        $result = Proposal::getReport();

        if (Auth::user()->usertype == 'admin')
        {
            $list_proposals = DB::table('proposal')
                    ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
                    ->leftJoin('account','account.id','=','proposal.user_id')
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address','account.username','converted.company_name')
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'groupadmin' ) {

            $list_proposals = DB::table('proposal')
                     ->where('proposal.user_id','=',Auth::user()->id)
                    ->orWhere('proposal.group_id','=',Auth::user()->groupid)
                    ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
                    ->leftJoin('account','account.id','=','proposal.user_id')
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address','account.username','converted.company_name')
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'reseller' )
        {
            $groupid = DB::table('resellergroup')->where('id',Auth::user()->resellerid)->first();

            $de = json_decode($groupid->associated_groups);

            $list_proposals = array();
            foreach ($de as $key => $de_gpid) {
                $list_proposals[] = DB::table('proposal')
                    ->where('proposal.group_id','=',$de_gpid)
                    ->Leftjoin('accountgroup','accountgroup.id','proposal.group_id')
                    ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
                    ->leftJoin('account','account.id','=','proposal.user_id')
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address','account.username','converted.company_name','accountgroup.name as accountgroup_name')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }
        else
        {
            $list_proposals = DB::table('proposal')
            ->where('proposal.user_id','=', Auth::user()->id)
            ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
            ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address','converted.company_name')
            ->orderBy('id', 'desc')
            ->get();
        }

        /*echo "<pre>";
        print_r($list_proposals);exit;*/
        return view('proposal.index',compact('list_proposals','result'));
    }

    public function add()
    {
        $products = Product::select('*')->get();
        $customers = Converted::select('*')->where('group_id',Auth::user()->groupid)->get();

        return view('proposal.add',compact('products','customers'));
    }

    public function store(Request $request)
    {
        $pro = $request->get('products');
        if (!empty($pro) && array_search("Select Products",$pro['name'])) {
            $message = toastr()->error('Please select valid product.');
            return Redirect::back()->with('message');
        }
        $discount = $request->get('discount') ? $request->get('discount').'-'.$request->get('dis_val') : '';
        $add_proposal = new Proposal([
            'operator_id' => Auth::user()->operator_id ? Auth::user()->operator_id : '',
            'user_id' => Auth::user()->id,
            'group_id' => Auth::user()->groupid,
            'subject' => $request->get('subject'),
            'cutomer_id' => $request->get('customer_id') ?? '',
            'date'=> $request->get('date'),
            'total_amount'=> $request->get('total_amount') ?? 0,
            'grand_total' => $request->get('grand_total') ?? 0,
            'discount' => $discount,
            'total_tax_amount' => $request->get('total_tax') ?? 0,
            'inserted_date' => date('Y-m-d H:i:s'),
        ]);
        $add_proposal->save();
        $id = DB::getPdo()->lastInsertId();
        $all_products = $request->get('products');
        $products = $all_products['name'];
        foreach($products as $i => $product) {
            $proposal_details = new Product_details([
                'proposal_id' => $id,
                'product_id' => $product,
                'qty' => $all_products['quantity'][$i] ?? 0,
                'rate' => $all_products['rate'][$i] ?? 0,
                'tax' => $all_products['tax'][$i] ?? 0.00,
                'amount' => $all_products['amount'][$i] ?? 0,
            ]);
            $proposal_details->save();
        }
        toastr()->success('Proposal added successfully.');
        return redirect()->route('ProposalIndex');
    }

    public function destroy($id){

        DB::table('proposal')->where('id',$id)->delete();
        DB::table('proposal_details')->where('proposal_id',$id)->delete();
        toastr()->success('Proposal delete successfully.');
        return redirect()->route('ProposalIndex');
    }

    public function edit($id){

        $proposal = DB::table('proposal')
                    ->where('proposal.id', $id)
                    ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address')
                    ->first();
        $proposal_details = DB::table('proposal_details')
                            ->where('proposal_id',$id)
                            ->leftJoin('products','products.id','=','proposal_details.product_id')
                            ->select('proposal_details.*','products.id as p_id','products.name')
                            ->get();

        $products = Product::select('*')->get();
        $customers = Converted::select('*')->where('group_id',Auth::user()->groupid)->get();

        $invoice_number = Invoice::max('id');
        $disc = explode('-',$proposal->discount);
        if($disc){
            $discount = $disc[0] ?? 0;
            $discount_value = $disc[1] ?? 0;
        }else{
            $discount = $discount_value = 0;
        }
        //print_r($invoice_number);exit;
        // dd($proposal,$proposal_details,$products,$customers,$invoice_number);
        return view('proposal.edit',compact('proposal','proposal_details','products','customers','invoice_number','discount','discount_value'));
    }

    public function update(Request $request,$id){
        $pro = $request->get('products');
        if (!empty($pro) && array_search("Select Products",$pro['name'])) {
            $message = toastr()->error('Please select valid product.');
            return Redirect::back()->with('message');
        }
        $discount = $request->get('discount') ? $request->get('discount').'-'.$request->get('dis_val') : '';
        $edit_proposal = Proposal::find($id);
        $edit_proposal->subject = $request->subject;
        $edit_proposal->cutomer_id = $request->customer_id;
        $edit_proposal->date = $request->date;
        $edit_proposal->total_amount = $request->total_amount;
        $edit_proposal->grand_total = $request->grand_total;
        $edit_proposal->discount = $discount;
        $edit_proposal->total_tax_amount = $request->total_tax;
        if ($edit_proposal->save()) {
            DB::table('proposal_details')->where('proposal_id',$id)->delete();
            $all_products = $request->get('products');
            $products = $all_products['name'];
            foreach($products as $i => $product) {
                $proposal_details = new Product_details([
                    'proposal_id' => $id,
                    'product_id' => $product,
                    'qty' => $all_products['quantity'][$i] ?? 0,
                    'rate' => $all_products['rate'][$i] ?? 0,
                    'tax' => $all_products['tax'][$i] ?? 0.00,
                    'amount' => $all_products['amount'][$i] ?? 0,
                ]);
                $proposal_details->save();
            }
            toastr()->success('Proposal Updated successfully.');
            return redirect()->route('ProposalIndex');
        }
    }
}
?>
