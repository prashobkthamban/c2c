<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body style="border:1px solid;">
	<h1><center>Your Generated Invoice</center></h1>
	<table border="0" cellspacing="0" cellpadding="2" style="width: 90%;margin-left: 30px;">
		<tbody>
			<tr>
				<td>Company Name</td>
				<td>Test</td>
				<td><b>Invoice Number</b></td>
				<td><b>INV-{{$invoice_number}}</b></td>			
			</tr>
			<tr>
				<td>Address</td>
				<td style="width: 60%;">{{$billing_address}}</td>
				<td>Invoice Date</td>
				<td>{{$date}}</td>
			</tr>
		</tbody>
	</table>
	<br><br>
	<table border="0" cellspacing="0" cellpadding="3" style="width: 90%;margin-left: 30px;">
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
				<td>₹{{$invoice_detail->rate}}</td>
				<td>{{$invoice_detail->tax}}%</td>
				<td>₹{{$invoice_detail->amount}}</td>
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
				<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{$total_amount}}</td>
			</tr>
			<tr>
				<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;text-align: right;border-color: transparent;">Total Discount</td>
				<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{$discount}}</td>
			</tr>
			<tr>
				<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;text-align: right;border-color: transparent;">Total Tax</td>
				<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{$total_tax_amount}}</td>
			</tr>
			<tr>
				<td style="border-bottom: 1px solid;border-left: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;text-align: right;border-color: transparent;">Total</td>
				<td style="border-bottom: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{$grand_total}}</td>
			</tr>
		</tfoot>
	</table>
	<br><br>
</body>
</html>