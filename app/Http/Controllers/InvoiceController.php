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
        //print_r($request->all());exit;
        $now = date("Y-m-d H:i:s");

        $add_invoice = new Invoice([
                'operator_id' => Auth::user()->operator_id ? Auth::user()->operator_id : '',
                'user_id' => Auth::user()->id,
                'group_id' => Auth::user()->groupid,
                'billing_address' => $request->get('address'),
                'customer_id' => $request->get('customer_id'),
                'date'=> $request->get('date'),
                'total_amount'=> $request->get('total_amount'),
                'grand_total' => $request->get('grand_total'),
                'discount' => $request->get('dis_val'),
                'invoice_number' => $request->get('invoice_number'),
                'total_tax_amount' => $request->get('total_tax'),
                'inserted_date' => $now,
            ]);

            //dd($add_invoice);exit;
            $add_invoice->save();
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
            //print_r($total_tax);exit;
            for ($i=0; $i < $count; $i++) {
                 $invoice_details = new Invoice_details([
                    'invoice_id' => $id,
                    'product_id' => $request->get('products')[$i],
                    'qty' => $request->get('quantity')[$i],
                    'rate' => $request->get('rate')[$i],
                    'tax' => $total_tax,
                    'amount' => $request->get('amount')[$i],
                ]);
             $invoice_details->save();
            }

         /*   $invoice_details = DB::table('invoice_details')
                            ->where('invoice_id',$id)
                            ->leftJoin('products','products.id','=','invoice_details.product_id')
                            ->select('invoice_details.*','products.id as p_id','products.name')
                            ->get();

            $data = array(
                'billing_address' => $request->get('address'),
                'customer_id' => $request->get('customer_id'),
                'date'=> $request->get('date'),
                'total_amount'=> $request->get('total_amount'),
                'grand_total' => $request->get('grand_total'),
                'discount' => $request->get('dis_val'),
                'invoice_number' => $request->get('invoice_number'),
                'total_tax_amount' => $request->get('total_tax'),
                'invoice_details' => $invoice_details,
            );

        $credential = array(
            'from' => 'prachi.itrd@gmail.com',
            'to' => 'prachikkothari@gmail.com',
            'subject' => 'Your Genrated Invoice',
        );

         Mail::send('invoice.mail_invoice', $data, function ($message) use ($credential){

            $message->from($credential['from']);
            $message->to($credential['to'])->subject($credential['subject']);
        });       */

            //print_r($id);exit;
            toastr()->success('Invoice added successfully.');
            return redirect()->route('InvoiceIndex');
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

        return view('invoice.edit',compact('invoice','invoice_details','products','customers'));
    }

    public function update(Request $request,$id){
        //print_r($request->all());exit();

        $edit_invoice = Invoice::find($id);

        $edit_invoice->billing_address = $request->address;
        $edit_invoice->customer_id = $request->customer_id;
        $edit_invoice->date = $request->date;
        $edit_invoice->total_amount = $request->total_amount;
        $edit_invoice->grand_total = $request->grand_total;
        $edit_invoice->discount = $request->dis_val;
        $edit_invoice->invoice_number = $request->invoice_number;
        $edit_invoice->total_tax_amount = $request->total_tax;

        if ($edit_invoice->save()) {

            DB::table('invoice_details')->where('invoice_id',$id)->delete();

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
                 $invoice_details = new Invoice_details([
                    'invoice_id' => $id,
                    'product_id' => $request->get('products')[$i],
                    'qty' => $request->get('quantity')[$i],
                    'rate' => $request->get('rate')[$i],
                    'tax' => $total_tax,
                    'amount' => $request->get('amount')[$i],
                ]);
             $invoice_details->save();
            }

            //print_r($id);exit;
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
                ->whereBetween('invoice.inserted_date',[$date_from,$date_to])
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
           $filter_data = DB::table('invoice')
                ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                ->leftJoin('account','account.id','=','invoice.user_id')
                ->whereBetween('invoice.inserted_date',[$date_from,$date_to])
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
        }
        else
        {
            $filter_data = DB::table('invoice')
                ->leftJoin('converted', 'converted.id', '=', 'invoice.customer_id')
                ->leftJoin('account','account.id','=','invoice.user_id')
                ->whereBetween('invoice.inserted_date',[$date_from,$date_to]);
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
}



?>
