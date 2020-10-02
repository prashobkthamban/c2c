<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Converted;
use App\Models\Product;
use App\Models\Invoice_details;
use App\Models\Invoice;
use App\Models\Invoice_Payment;
use PDF;
use Illuminate\Support\Facades\Mail;

date_default_timezone_set('Asia/Kolkata');

class InvoiceController extends Controller
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

        $result = Invoice::getReport();

        if (Auth::user()->usertype == 'admin')
        {
            $list_invoices = DB::table('invoice')
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->leftJoin('account','account.id','=','invoice.user_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','account.username','converted.company_name')
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'groupadmin') {

           $list_invoices = DB::table('invoice')
                    ->where('invoice.user_id','=',Auth::user()->id)
                    ->orWhere('invoice.group_id','=',Auth::user()->groupid)
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->leftJoin('account','account.id','=','invoice.user_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','account.username','converted.company_name')
                    ->orderBy('id', 'desc')
                    ->get();
        }
        elseif (Auth::user()->usertype == 'reseller')
        {
            $groupid = DB::table('resellergroup')->where('id',Auth::user()->resellerid)->first();

            $de = json_decode($groupid->associated_groups);

            $list_invoices = array();
            foreach ($de as $key => $de_gpid) {
                $list_invoices[] = DB::table('invoice')
                    ->where('invoice.group_id','=',$de_gpid)
                    ->Leftjoin('accountgroup','accountgroup.id','invoice.group_id')
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->leftJoin('account','account.id','=','invoice.user_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','account.username','converted.company_name','accountgroup.name as accountgroup_name')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }
        else
        {
             $list_invoices = DB::table('invoice')
                    ->where('invoice.user_id','=',Auth::user()->id)
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name')
                    ->orderBy('id', 'desc')
                    ->get();
        }
        /*echo "<pre>";
        print_r($list_invoices);exit;*/

        $date_from = request()->get('date_from');
        $date_to = request()->get('date_to');
        if($date_from && $date_to) {
            $list_invoices = $this->FilterDataInvoice(request());
        }

        return view('invoice.index',compact('list_invoices','result'));
    }

    public function add()
    {
        $products = Product::select('*')->get();
        $customers = Converted::where('group_id','=',Auth::user()->groupid)->select('*')->get();
        $invoice_number = Invoice::max('id');
        return view('invoice.add',compact('products','customers','invoice_number'));
    }

    public function store(Request $request)
    {
        if($request->get('add_invoice')){
            if($this->createInvoice($request)){
                toastr()->success('Invoice added successfully.');
                return redirect()->route('InvoiceIndex');
            } else {
                toastr()->error('Please check product details.');
                return redirect()->route('InvoiceIndex');
            }
        } else {
            if($this->proposalToInvoice($request)){
                toastr()->success('Invoice added successfully.');
                return redirect()->back();
            } else {
                toastr()->error('Please check product details.');
                return redirect()->back();
            }
        }
    }

    public function destroy($id){

        DB::table('invoice')->where('id',$id)->delete();
        DB::table('invoice_details')->where('invoice_id',$id)->delete();
        toastr()->success('Invoice delete successfully.');
        return redirect()->route('InvoiceIndex');
    }

    public function destroyPayment($id){
        DB::table('invoice_payments')->where('id',$id)->delete();
        toastr()->success('Payment deleted successfully.');
        return redirect()->back();
    }

    public function edit($id){

        $invoice = DB::table('invoice')
                    ->where('invoice.id', $id)
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name','converted.gst_no','converted.email','converted.mobile_no')
                    ->first();
        $invoice_details = DB::table('invoice_details')
                            ->where('invoice_id',$id)
                            ->leftJoin('products','products.id','=','invoice_details.product_id')
                            ->select('invoice_details.*','products.id as p_id','products.name')
                            ->get();

        $products = Product::select('*')->get();
        $customers = Converted::select('*')->get();
        $disc = explode('-',$invoice->discount);
        if(count($disc) == 2){
            $discount = $disc[0] ?? 0;
            $discount_value = $disc[1] ?? 0;
        }else{
            $discount = $discount_value = 0;
        }
        return view('invoice.edit',compact('invoice','invoice_details','products','customers','discount','discount_value'));
    }

    public function update(Request $request,$id){
        $pro = $request->get('products');
        if (!empty($pro) && array_search("Select Products",$pro['name'])) {
            $message = toastr()->error('Please select valid product.');
            return Redirect::back()->with('message');
        }
        $discount = $request->get('discount') ? $request->get('discount').'-'.$request->get('dis_val') : '';
        $edit_invoice = Invoice::find($id);
        $edit_invoice->billing_address = $request->address;
        $edit_invoice->customer_id = $request->customer_id;
        $edit_invoice->date = $request->date;
        $edit_invoice->total_amount = $request->total_amount;
        $edit_invoice->grand_total = $request->grand_total;
        $edit_invoice->discount = $discount;
        $edit_invoice->invoice_number = $request->invoice_number;
        $edit_invoice->total_tax_amount = $request->total_tax;
        if ($edit_invoice->save()) {
            DB::table('invoice_details')->where('invoice_id',$id)->delete();
            $all_products = $request->get('products');
            $products = $all_products['name'];
            foreach($products as $i => $product) {
                $invoice_details = new Invoice_details([
                    'invoice_id' => $id,
                    'product_id' => $product,
                    'qty' => $all_products['quantity'][$i] ?? 0,
                    'rate' => $all_products['rate'][$i] ?? 0,
                    'tax' => $all_products['tax'][$i] ?? 0.00,
                    'amount' => $all_products['amount'][$i] ?? 0,
                ]);
                $invoice_details->save();
            }
            toastr()->success('Invoice Updated successfully.');
            return redirect()->route('InvoiceIndex');
        }
    }

    public function CustomerAddress(Request $request){
        //print_r($request->all());exit;
         $user = DB::table('converted')->where('id', $request->get('customer_id'))->first();
        //print_r($user);
        echo json_encode($user);
    }

    public function Payment(Request $request){
        print_r($request->all());

        $add_payment = new Invoice_Payment([
                'invoice_id' => $request->get('invoice_id'),
                'payment_amount' => $request->get('amount'),
                'transaction_id'=> $request->get('transaction_id') ? $request->get('transaction_id') : '',
                'payment_date'=> $request->get('payment_date'),
                'payment_mode' => $request->get('payment_mode'),
                'status' => $request->get('payment_status'),
                'note' => $request->get('note') ? $request->get('note') : '',
            ]);

            //dd($add_payment);exit;
            $add_payment->save();

            $update_status_invoice = Invoice::find($request->invoice_id);

            $update_status_invoice->payment_status = $request->get('payment_status');

            $update_status_invoice->save();

            toastr()->success('Payment done successfully.');
            return redirect()->route('InvoiceIndex');
    }

    public function FilterDataInvoice(Request $request)
    {
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $company_name = $request->get('company_name');
        $agent_name = $request->get('agent_name');
        $status = $request->get('status');

        if (Auth::user()->usertype == 'operator')
        {
            $filter_data = DB::table('invoice')
                ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                ->leftJoin('account','account.id','=','invoice.user_id')
                ->whereBetween(DB::raw('DATE(invoice.date)'),[$date_from,$date_to])
                ->where('invoice.user_id','=',Auth::user()->id);
            if($company_name){
                $filter_data->where('converted.company_name','like','%'.$company_name.'%');
            }
            if($agent_name){
                $filter_data->where('account.username','like','%'.$agent_name.'%');
            }
            if($status){
                $filter_data->where('invoice.payment_status',$status);
            }
            $filter_data = $filter_data->select('converted.first_name',
                    'converted.last_name','invoice.*','converted.company_name','account.username')->get();
        }
        elseif (Auth::user()->usertype == 'groupadmin') {
            DB::enableQueryLog();
           $filter_data = DB::table('invoice')
                ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                ->leftJoin('account','account.id','=','invoice.user_id')
                ->whereBetween(DB::raw('DATE(invoice.date)'),[$date_from,$date_to])
                ->where('invoice.group_id','=',Auth::user()->groupid);
            if($company_name){
                $filter_data->where('converted.company_name','like','%'.$company_name.'%');
            }
            if($agent_name){
                $filter_data->where('account.username','like','%'.$agent_name.'%');
            }
            if($status){
                $filter_data->where('invoice.payment_status',$status);
            }
            $filter_data = $filter_data->select('converted.first_name',
                    'converted.last_name','invoice.*','converted.company_name','account.username')->get();
                    // dd(DB::getQueryLog());
        }
        else
        {
            $filter_data = DB::table('invoice')
                ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                ->leftJoin('account','account.id','=','invoice.user_id')
                ->whereBetween(DB::raw('DATE(invoice.date)'),[$date_from,$date_to]);
            if($company_name){
                $filter_data->where('converted.company_name','like','%'.$company_name.'%');
            }
            if($agent_name){
                $filter_data->where('account.username','like','%'.$agent_name.'%');
            }
            if($status){
                $filter_data->where('invoice.payment_status',$status);
            }
            $filter_data = $filter_data->select('converted.first_name',
                    'converted.last_name','invoice.*','converted.company_name','account.username')->get();
        }
        return $filter_data;
    }

    public function ViewInvoice($id)
    {
        $invoice = DB::table('invoice')
                    ->where('invoice.id', $id)
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name','converted.gst_no','converted.email','converted.mobile_no')
                    ->first();

        $invoice_details = DB::table('invoice_details')
                    ->where('invoice_id',$id)
                    ->leftJoin('products','products.id','=','invoice_details.product_id')
                    ->select('invoice_details.*','products.id as p_id','products.name')
                    ->get();

        $company_details = DB::table('accountgroup')->where('id',Auth::user()->groupid)->first();

        //print_r($company_details);exit;

        $invoice_payments = DB::table('invoice_payments')->where('invoice_id',$id)->get();

        return view('invoice.view',compact('invoice','invoice_details','invoice_payments','company_details'));
    }

    public function MailInvoice($id)
    {
        $invoice = DB::table('invoice')
                    ->where('invoice.id', $id)
                    ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                    ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name','converted.gst_no','converted.email','converted.mobile_no')
                    ->first();

        $invoice_details = DB::table('invoice_details')
                    ->where('invoice_id',$id)
                    ->leftJoin('products','products.id','=','invoice_details.product_id')
                    ->select('invoice_details.*','products.id as p_id','products.name')
                    ->get();

        //print_r($invoice);exit;

        $data = array(
                'billing_address' => $invoice->billing_address,
                'customer_id' => $invoice->customer_id,
                'date'=> $invoice->date,
                'total_amount'=> $invoice->total_amount,
                'grand_total' => $invoice->grand_total,
                'discount' => $invoice->discount,
                'invoice_number' => $invoice->invoice_number,
                'total_tax_amount' => $invoice->total_tax_amount,
                'invoice_details' => $invoice_details,
            );

        /*$credential = array(
            'from' => 'prachi.itrd@gmail.com',
            'to' => $invoice->email,
            'subject' => 'Your Genrated Invoice',
        );

         Mail::send('invoice.mail_invoice', $data, function ($message) use ($credential){

            $message->from($credential['from']);
            $message->to($credential['to'])->subject($credential['subject']);
        });*/

            //print_r($id);exit;
            toastr()->success('Invoice Mail send successfully.');
            return redirect()->route('InvoiceIndex');
    }

    public function proposalToInvoice($request){
        $pid = $request->get('pid');
        $proposal = DB::table('proposal')->where('proposal.id', $pid)->first();
        $proposal_details = DB::table('proposal_details')->where('proposal_id',$pid)->get();
        if($proposal && count($proposal_details)) {
            $add_invoice = new Invoice([
                    'operator_id' => Auth::user()->operator_id ? Auth::user()->operator_id : '',
                    'user_id' => Auth::user()->id,
                    'group_id' => Auth::user()->groupid,
                    'billing_address' => $request->get('address'),
                    'customer_id' => $request->get('customer_id'),
                    'date'=> $request->get('date'),
                    'total_amount'=> $proposal->total_amount,
                    'grand_total' => $proposal->grand_total,
                    'discount' => $proposal->discount,
                    'invoice_number' => $request->get('invoice_number'),
                    'total_tax_amount' => $proposal->total_tax_amount,
                    'inserted_date' => date("Y-m-d H:i:s"),
                ]);
                $add_invoice->save();
                $id = DB::getPdo()->lastInsertId();
                foreach($proposal_details as $prop) {
                    $invoice_details = new Invoice_details([
                        'invoice_id' => $id,
                        'product_id' =>$prop->product_id,
                        'qty' =>$prop->qty,
                        'rate' =>$prop->rate,
                        'tax' => $prop->tax,
                        'amount' =>$prop->amount,
                    ]);
                    $invoice_details->save();
                }
                return true;
        } else {
            return false;
        }
    }

    public function createInvoice($request) {
        $pro = $request->get('products');
        if (!empty($pro) && array_search("Select Products",$pro['name'])) {
            return false;
        }
        $discount = $request->get('discount') ? $request->get('discount').'-'.$request->get('dis_val') : '';
        $add_invoice = new Invoice([
            'operator_id' => Auth::user()->operator_id ? Auth::user()->operator_id : '',
            'user_id' => Auth::user()->id,
            'group_id' => Auth::user()->groupid,
            'billing_address' => $request->get('address') ?? '',
            'customer_id' => $request->get('customer_id'),
            'date'=> $request->get('date'),
            'total_amount'=> $request->get('total_amount') ?? 0,
            'grand_total' => $request->get('grand_total'),
            'discount' => $discount,
            'invoice_number' => $request->get('invoice_number'),
            'total_tax_amount' => $request->get('total_tax') ?? 0,
            'inserted_date' => date("Y-m-d H:i:s"),
        ]);
        $add_invoice->save();
        $id = DB::getPdo()->lastInsertId();
        $all_products = $request->get('products');
        $products = $all_products['name'];
        foreach($products as $i => $product) {
            $proposal_details = new Invoice_details([
                'invoice_id' => $id,
                'product_id' => $product,
                'qty' => $all_products['quantity'][$i] ?? 0,
                'rate' => $all_products['rate'][$i] ?? 0,
                'tax' => $all_products['tax'][$i] ?? 0.00,
                'amount' => $all_products['amount'][$i] ?? 0,
            ]);
            $proposal_details->save();
        }
        return true;
    }

    public function downloadInvoice($id){
        $invoice = DB::table('invoice')->where('invoice.id', $id)->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
        ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name','converted.gst_no','converted.email','converted.mobile_no')
        ->first();
        $invoice_details = DB::table('invoice_details')->where('invoice_id',$id)->leftJoin('products','products.id','=','invoice_details.product_id')
        ->select('invoice_details.*','products.id as p_id','products.name')->get();
        $company_details = DB::table('accountgroup')->where('id',Auth::user()->groupid)->first();
        $invoice_payments = DB::table('invoice_payments')->where('invoice_id',$id)->get();
        $tnc = DB::table('terms_condition_invoice')->where('user_id',Auth::user()->id)->first();
        $pdf = PDF::loadView('invoice.print', compact('invoice','invoice_details','invoice_payments','company_details','tnc'))->setPaper('a4');
        return $pdf->download("INV-".$invoice->invoice_number.'-'.date('d-m-Y'). '.pdf');
    }

    public function printInvoice($id){
        $invoice = DB::table('invoice')->where('invoice.id', $id)->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
        ->select('invoice.*','converted.id as c_id','converted.first_name','converted.last_name','converted.company_name','converted.gst_no','converted.email','converted.mobile_no')
        ->first();
        $invoice_details = DB::table('invoice_details')->where('invoice_id',$id)->leftJoin('products','products.id','=','invoice_details.product_id')
        ->select('invoice_details.*','products.id as p_id','products.name')->get();
        $company_details = DB::table('accountgroup')->where('id',Auth::user()->groupid)->first();
        $invoice_payments = DB::table('invoice_payments')->where('invoice_id',$id)->get();
        $tnc = DB::table('terms_condition_invoice')->where('user_id',Auth::user()->id)->first();
        $pdf = PDF::loadView('invoice.print', compact('invoice','invoice_details','invoice_payments','company_details','tnc'))->setPaper('a4');
        return $pdf->stream("INV-".$invoice->invoice_number.'-'.date('d-m-Y'). '.pdf');
    }
}



?>
