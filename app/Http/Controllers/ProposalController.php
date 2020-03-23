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
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }
        elseif (Auth::user()->usertype == 'groupadmin' ) {

            $list_proposals = DB::table('proposal')
                     ->where('proposal.user_id','=',Auth::user()->id)
                    ->orWhere('operator_id','=',Auth::user()->groupid)
                    ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address')
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        }
        else
        {
            $list_proposals = DB::table('proposal')
            ->where('user_id','=', Auth::user()->id)
            ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
            ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.address')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }

        return view('proposal.index',compact('list_proposals','result'));
    }

    public function add()
    {
        $products = Product::select('*')->get();
        $customers = Converted::select('*')->get();

        return view('proposal.add',compact('products','customers'));
    }

    public function store(Request $request)
    {
        //print_r($request->all());exit;

        $add_proposal = new Proposal([
                'operator_id' => Auth::user()->operator_id ? Auth::user()->operator_id : '',
                'user_id' => Auth::user()->id,
                'subject' => $request->get('subject'),
                'cutomer_id' => $request->get('customer_id') ? $request->get('customer_id') : '',
                'date'=> $request->get('date'),
                'total_amount'=> $request->get('total_amount'),
                'grand_total' => $request->get('grand_total'),
                'discount' => $request->get('dis_val'),
                'total_tax_amount' => $request->get('total_tax'),
            ]);

            //dd($add_proposal);exit;
            $add_proposal->save();
            $id = DB::getPdo()->lastInsertId();

            $pro = $request->get('products');

            if (empty($pro)) {
                $count = 0;
            }else{
                 $count = count($request->get('products'));
            }
            //print_r($count);exit();

            $tax = $request->get('tax');
            //print_r($tax);exit;

            for ($i=0; $i < count($tax); $i++) { 
                $total_tax = implode(",", $tax);
            }
            
            for ($i=0; $i < $count; $i++) { 
                 $proposal_details = new Product_details([
                    'proposal_id' => $id,
                    'product_id' => $request->get('products')[$i],
                    'qty' => $request->get('quantity')[$i],
                    'rate' => $request->get('rate')[$i],
                    'tax' => $total_tax,
                    'amount' => $request->get('amount')[$i],
                ]); 
             $proposal_details->save();              
            }
            
            //print_r($id);exit;
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
        $customers = Converted::select('*')->get();

        return view('proposal.edit',compact('proposal','proposal_details','products','customers'));
    }

    public function update(Request $request,$id){
        //print_r($request->all());exit();

        $edit_proposal = Proposal::find($id);
    
        $edit_proposal->subject = $request->subject;
        $edit_proposal->cutomer_id = $request->customer_id;
        $edit_proposal->date = $request->date;
        $edit_proposal->total_amount = $request->total_amount;
        $edit_proposal->grand_total = $request->grand_total;
        $edit_proposal->discount = $request->dis_val;
        $edit_proposal->total_tax_amount = $request->total_tax;

        if ($edit_proposal->save()) {

            DB::table('proposal_details')->where('proposal_id',$id)->delete();

            $pro = $request->get('products');
            
            if (empty($pro)) {
                $count = 0;
            }else{
                 $count = count($request->get('products'));
            }

            $tax = $request->get('tax');
            //print_r($tax);exit;

            for ($i=0; $i < count($tax); $i++) { 
                $total_tax = implode(",", $tax);
            }

            for ($i=0; $i < $count; $i++) { 
                 $proposal_details = new Product_details([
                    'proposal_id' => $id,
                    'product_id' => $request->get('products')[$i],
                    'qty' => $request->get('quantity')[$i],
                    'rate' => $request->get('rate')[$i],
                    'tax' => $total_tax,
                    'amount' => $request->get('amount')[$i],
                ]); 
             $proposal_details->save();              
            }            
            
            //print_r($id);exit;
            toastr()->success('Proposal Updated successfully.');
            return redirect()->route('ProposalIndex');
        }
    }
}



?>