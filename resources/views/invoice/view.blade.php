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
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                        	<div class="col-md-12" style="text-align: right;">

                        		<a title="Compact Sidebar" id="download" href="javascript:void(0)" class="btn btn-outline-secondary" style="margin-right: 15px;"> Download </a>

                        		<a title="Compact Sidebar" id="print" href="javascript:void(0)" class="btn btn-outline-secondary" style="margin-right: 15px;"> Print </a>

                        		<a title="Compact Sidebar" id="mail" href="{{ route('MailInvoice',$invoice->id) }}" class="btn btn-outline-secondary" style="margin-right: 15px;"> Mail </a>

                        		<a title="Compact Sidebar" id="add_converted" href="{{ route('InvoiceIndex') }}" class="btn btn-outline-secondary" style="margin-right: 15px;"> Back To Invoices </a>

                        		<a title="Compact Sidebar" id="add_converted" href="{{ route('editInvoice', $invoice->id) }}" class="btn btn-outline-secondary"> Edit Invoice </a>

                        		<!-- <button id="pdf" class="btn btn-primary" name="btn">Generate PDF</button> -->
                        		
                        	</div>
                        	<div class="canvas_div_pdf" id="download_data" style="width: 100%;">
                        		<!-- <table border="0" cellspacing="0" cellpadding="2" style="width: 90%;margin-left: 30px;margin-top: 30px;" class="show_print">
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td><b>Invoice Number</b></td>
											<td><b style="font-size: large;">INV-{{$invoice->invoice_number}}</b></td>			
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td><b>Invoice Date</b></td>
											<td>{{$invoice->date}}</td>
										</tr>
										<tr>
											<td><b>Client's Details</b></td>
											<td></td>
											<td><b>Company's Details</b></td>
											<td></td>
										</tr>
										<tr>
											<td><b>Company Name</b></td>
											<td>{{$invoice->company_name}}</td>
											<td><b>Company Name</b></td>
											<td>{{$company_details->companyname}}</td>			
										</tr>
										<tr>
											<td><b>Address</b></td>
											<td style="width: 60%;">{{$invoice->billing_address}}</td>
											<td><b>GST Number</b></td>
											<td>{{$company_details->GST}}</td>
										</tr>
										<tr>
											

									
									<td><b>First Name</b></td>
											<td>{{$invoice->first_name}}</td>
											<td><b>Address</b></td>
											<td>{{$company_details->shipping_address}}</td>	</tr>
										<tr>
											<td><b>Last Name</b></td>
											<td>{{$invoice->last_name}}</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td><b>Email Address</b></td>
											<td>{{$invoice->email}}</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td><b>Phone Number</b></td>
											<td>{{$invoice->mobile_no}}</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td><b>GST Number</b></td>
											<td>{{$invoice->gst_no}}</td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
								<table border="0" cellspacing="0" cellpadding="3" style="width: 90%;margin-left: 30px;margin-top: 20px;" class="show_print">
									<thead style="background-color: #80808045;text-align: center;line-height: 154%;">
										<tr>
											<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;    border-color: transparent;">#</td>
											<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;    border-color: transparent;">Item</td>
											<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;    border-color: transparent;">Qty</td>
											<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;    border-color: transparent;">Rate</td>
											<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;    border-color: transparent;">Tax</td>
											<td style="border-left:1px solid;border-top: 1px solid;border-right: 1px solid;    border-bottom: 1px solid;border-color: transparent;">Amount</td>
										</tr>
									</thead>
									<tbody style="text-align: center;line-height: 154%;">
										<?php 
										$i = 1;
										foreach ($invoice_details as $key => $invoice_detail) { ?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$invoice_detail->name}}</td>
											<td>{{$invoice_detail->qty}}</td>
											<td>₹{{sprintf("%.2f",$invoice_detail->rate)}}</td>
											<td>{{$invoice_detail->tax}}%</td>
											<td>₹{{sprintf("%.2f",$invoice_detail->amount)}}</td>
										</tr>
										<?php $i++; }?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
									<tfoot style="background-color: #80808045;line-height: 154%;">
										<tr>
											<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;text-align: right;border-color: transparent;">Sub Total</td>
											<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{sprintf("%.2f",$invoice->total_amount)}}</td>
										</tr>
										<tr>
											<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;text-align: right;border-color: transparent;">Total Discount</td>
											<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{sprintf("%.2f",$invoice->discount)}}</td>
										</tr>
										<tr>
											<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;border-color: transparent;"></td>
											<td style="border-top: 1px solid;text-align: right;border-color: transparent;">Total Tax</td>
											<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{sprintf("%.2f",$invoice->total_tax_amount)}}</td>
										</tr>
										<tr>
											<td style="border-bottom: 1px solid;border-left: 1px solid;border-color: transparent;"></td>
											<td style="border-bottom: 1px solid;border-color: transparent;"></td>
											<td style="border-bottom: 1px solid;border-color: transparent;"></td>
											<td style="border-bottom: 1px solid;border-color: transparent;"></td>
											<td style="border-bottom: 1px solid;text-align: right;border-color: transparent;font-size: large;"><b>Grand Total</b></td>
											<td style="border-bottom: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;font-size: large;"><b>₹{{sprintf("%.2f",$invoice->grand_total)}}</b></td>
										</tr>
									</tfoot>
								</table> -->
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6"></div>
										<div class="col-md-6">
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
								<p id="sign" style="margin-left: 85%;margin-top: 12%;display: none;">Signature</p>
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
		var top_left_margin = 15;
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

		$('#sign').show();

		var HTML_Width = $(".canvas_div_pdf").width();
		var HTML_Height = $(".canvas_div_pdf").height();
		var top_left_margin = 15;
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

