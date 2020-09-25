@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.bubble.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.snow.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
<link href="{{asset('assets/styles/vendor/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Invoice </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>
            <div class="row mb-3">
                <div class="col-md-12" style="text-align: right;">
                    <a title="Compact Sidebar" id="download" href="javascript:void(0)" class="btn btn-outline-secondary" style="margin-right: 15px;"> Download </a>
                    <a title="Compact Sidebar" id="print" href="javascript:void(0)" class="btn btn-outline-secondary" style="margin-right: 15px;"> Print </a>
                    <a title="Compact Sidebar" id="mail" href="{{ route('MailInvoice',$invoice->id) }}" class="btn btn-outline-secondary" style="margin-right: 15px;"> Mail </a>
                    <a title="Compact Sidebar" id="add_converted" href="{{ route('InvoiceIndex') }}" class="btn btn-outline-secondary" style="margin-right: 15px;"> Back To Invoices </a>
                    <a title="Compact Sidebar" id="add_converted" href="{{ route('editInvoice', $invoice->id) }}" class="btn btn-outline-secondary"> Edit Invoice </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        	<div class="canvas_div_pdf" id="download_data" style="width: 100%;padding-top: 60px;">
								<div class="col-md-10  offset-md-1 mt-3">
									<div class="row">
                                        <div class="col-md-6 offset-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Invoice Number</b>
												</div>
												<div class="col-md-8">
													<b style="font-size: large;">INV-{{$invoice->invoice_number}}</b>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<b style="font-size: large;">Client's Details</b>
										</div>
										<div class="col-md-6">
											<b style="font-size: large;">Company's Details</b>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Company Name</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->company_name}}</p>
												</div>
												<div class="col-md-3"></div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Company Name</b>
												</div>
												<div class="col-md-8">
													<p>{{$company_details->companyname}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Address</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->billing_address}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>GST Number</b>
												</div>
												<div class="col-md-8">
													<p>{{$company_details->GST}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>First Name</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->first_name}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Address</b>
												</div>
												<div class="col-md-8">
													<p>{{$company_details->shipping_address}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Last Name</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->last_name}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6"></div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Email Address</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->email}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6"></div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>Phone Number</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->mobile_no}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6"></div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<b>GST Number</b>
												</div>
												<div class="col-md-8">
													<p>{{$invoice->gst_no}}</p>
												</div>
											</div>
										</div>
										<div class="col-md-6"></div>
									</div>
								</div>
								<div class="col-md-12" style="background-color: #80808045;text-align: center;line-height: 154%;">
									<div class="row">
										<div class="col-md-1">
											<p>#</p>
										</div>
										<div class="col-md-4">
											<p>Item</p>
										</div>
										<div class="col-md-1">
											<p>Qty</p>
										</div>
										<div class="col-md-2">
											<p>Rate</p>
										</div>
										<div class="col-md-2">
											<p>Tax</p>
										</div>
										<div class="col-md-2">
											<p>Amount</p>
										</div>
									</div>
								</div>
								<div class="col-md-12" style="text-align: center;line-height: 154%;">
									<div class="row">
										<?php
										$i = 1;
										foreach ($invoice_details as $key => $invoice_detail) { ?>
											<div class="col-md-1">
												<p>{{$i}}</p>
											</div>
											<div class="col-md-4">
												<p>{{$invoice_detail->name}}</p>
											</div>
											<div class="col-md-1">
												<p>{{$invoice_detail->qty}}</p>
											</div>
											<div class="col-md-2">
												<p>₹{{sprintf("%.2f",$invoice_detail->rate)}}</p>
											</div>
											<div class="col-md-2">
												<p>{{$invoice_detail->tax}}%</p>
											</div>
											<div class="col-md-2">
												<p>₹{{sprintf("%.2f",$invoice_detail->amount)}}</p>
											</div>
										<?php $i++; }?>
									</div>
								</div>
								<div class="col-md-12" style="background-color: #80808045;line-height: 154%;">
									<div class="row">
										<div class="col-md-9"></div>
										<div class="col-md-3">
											<div class="row">
												<div class="col-md-6">
													<p>Sub Total</p>
												</div>
												<div class="col-md-6">
													<p>₹{{sprintf("%.2f",$invoice->total_amount)}}</p>
												</div>
												<div class="col-md-6">
													<p>Total Discount</p>
												</div>
												<div class="col-md-6">
													<p>₹{{sprintf("%.2f",$invoice->discount)}}</p>
												</div>
												<div class="col-md-6">
													<p>Total Tax</p>
												</div>
												<div class="col-md-6">
													<p>₹{{sprintf("%.2f",$invoice->total_tax_amount)}}</p>
												</div>
												<div class="col-md-6" style="font-size: large;">
													<b>Grand Total</b>
												</div>
												<div class="col-md-6" style="font-size: large;">
													<b>₹{{sprintf("%.2f",$invoice->grand_total)}}</b>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{-- <p id="sign" style="margin-left: 85%;margin-top: 5%;display: none;">Signature</p> --}}
                        	</div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12 card">
                	<div class="card-body">
                    	<h3><center><b>Payments</b></center></h3>
                    	<?php
                    	if (!empty($invoice_payments))
                    	{ ?>
                    		<table class="table" style="width: 100%;">
                    			<thead>
                    				<tr>
                    					<td>ID</td>
                    					<td>Amount</td>
                    					<td>Trasaction Id</td>
                    					<td>payment date</td>
                    					<td>payment mode</td>
                    					<td>payment status</td>
                    					<td>Action</td>
                    				</tr>
                    			</thead>
                    			<tbody>
                    				<?php
                    				$i = 1;
                    				foreach ($invoice_payments as $key => $payment) { ?>
                    					<tr>
                    						<td>{{$i}}</td>
                    						<td>{{$payment->payment_amount}}</td>
                    						<td>{{$payment->transaction_id}}</td>
                    						<td>{{$payment->payment_date}}</td>
                    						<td>{{$payment->payment_mode}}</td>
                                            <td>{{$payment->status}}</td>
                                            <td><a href="{{ route('deleteInvoicePayment', $payment->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="Payment Delete">
                                                <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                            </a></td>
                    					</tr>
                    				<?php $i++; }
                    				?>
                    			</tbody>
                    		</table>
                    	<?php

                    	}
                    	else
                    	{
                    		echo "<h5><center>No Payments Found!</center></h5>";
                    	}
                    	?>
                    </div>
                </div>
                <br><br><br><br>
            </div>
            <!-- end of row -->



@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>

<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/es5/script.min.js')}}"></script>
<script src="{{asset('assets/js/es5/sidebar.large.script.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/perfect-scrollbar.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#sign').hide();
	$('#download').click(function() {
		$('#sign').show();
		getPDF();
		$('#sign').hide();
	});
	$('#print').click(function() {
		$('#sign').show();
		getPRINT();
		$('#sign').hide();
	});
});
	function getPDF(){
		'<br><br>';
		var HTML_Width = $(".canvas_div_pdf").width();
		var HTML_Height = $(".canvas_div_pdf").height();
		var top_left_margin = 10;
		var PDF_Width = HTML_Width+(top_left_margin*2);
		var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
		var canvas_image_width = HTML_Width;
		var canvas_image_height = HTML_Height;

		var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;


		html2canvas($(".canvas_div_pdf")[0],{allowTaint:true}).then(function(canvas) {
			canvas.getContext('2d');

			//console.log(canvas.height+"  "+canvas.width);


			var imgData = canvas.toDataURL("image/jpeg", 1.0);
			var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
		    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);


			for (var i = 1; i <= totalPDFPages; i++) {
				pdf.addPage(PDF_Width, PDF_Height);
				pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
			}

		    pdf.save("Invoice.pdf");
        });
	};

	function getPRINT(){

		// $('#sign').show();

		var HTML_Width = $(".canvas_div_pdf").width();
		var HTML_Height = $(".canvas_div_pdf").height();
		var top_left_margin = 10;
		var PDF_Width = HTML_Width+(top_left_margin*2);
		var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
		var canvas_image_width = HTML_Width;
		var canvas_image_height = HTML_Height;

		var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;


		html2canvas($(".canvas_div_pdf")[0],{allowTaint:true}).then(function(canvas) {
			canvas.getContext('2d');

			//console.log(PDF_Width);


			var imgData = canvas.toDataURL("image/jpeg", 1.0);
			var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
		    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);


			for (var i = 1; i <= totalPDFPages; i++) {
				pdf.addPage(PDF_Width, PDF_Height);
				pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
			}

		    //pdf.save("Download.pdf");
		    var blob = pdf.output("blob");
            //Getting URL of blob object
            var blobURL = URL.createObjectURL(blob);
            //alert(blobURL);
            window.open(blobURL, '_blank');
        });
	};
</script>
@endsection

