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
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use View;

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
        if(count($disc) == 2){
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
                    // dd($request->all(),$request->customer_id,$edit_proposal,$proposal_details);
                }
            toastr()->success('Proposal Updated successfully.');
            return redirect()->route('ProposalIndex');
        }
    }

    public function mailProposal($id){
        $proposal = DB::table('proposal')
                    ->where('proposal.id', $id)
                    ->leftJoin('converted', 'converted.id', '=', 'proposal.cutomer_id')
                    ->select('proposal.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name','converted.gst_no','converted.email','converted.mobile_no')
                    ->first();

        $proposal_details = DB::table('proposal_details')
                    ->where('proposal_id',$id)
                    ->leftJoin('products','products.id','=','proposal_details.product_id')
                    ->select('proposal_details.*','products.id as p_id','products.name')
                    ->get();
        $disc = $proposal->discount;
        $dis = (explode('-',$disc));
        if(count($dis) == 2){
            $dvalue = $dis[1];
        }else{
            $dvalue = 0;
        }
        $tnc = DB::table('terms_condition_proposal')->where('user_id',Auth::user()->id)->first();
        $data = array(
                'customer' => ucwords($proposal->first_name.' '.$proposal->last_name),
                'total_amount'=> $proposal->total_amount,
                'grand_total' => $proposal->grand_total,
                'discount' => $dvalue,
                'total_tax_amount' => $proposal->total_tax_amount,
                'proposal_details' => $proposal_details,
                'tnc'=> $tnc->name ?? ''
            );
        $emailApi = 0;
        if (Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'groupadmin') {
            $emailApi = DB::table('email_api')->where('user_id', Auth::user()->id)->first();
        }else{
            $ga = DB::table('account')->where('groupid', Auth::user()->groupid)->where('usertype','groupadmin')->first();
            if($ga){
                $emailApi = DB::table('email_api')->where('user_id',$ga->id)->first();
            }
        }
        // dd($emailApi,$id);
        if($emailApi){
            try {
            $view = View::make('proposal.mail_proposal', $data);
            $html = (string) $view;
            $transport = new Swift_SmtpTransport($emailApi->smtp_host, $emailApi->port,$emailApi->type);
            $transport->setUsername($emailApi->username);
            $transport->setPassword($emailApi->password);
            $swift_mailer = new Swift_Mailer($transport);
            $message = (new Swift_Message($proposal->subject))
            ->setFrom([$emailApi->username])
            ->setTo([$proposal->email])
            ->addPart($html, 'text/html');
            $swift_mailer->send($message);
            } catch (\Exception $e) {
                $message = toastr()->error('Please check your Email Api.');
                return Redirect::back()->with('message');
            }
        }
        toastr()->success('Proposal mail sent successfully.');
        return Redirect::back();
    }
}
?>
