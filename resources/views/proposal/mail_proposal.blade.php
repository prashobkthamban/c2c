<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body style="border:1px solid;">
	<h1><center>Your Generated Proposal</center></h1>
	<table border="0" cellspacing="0" cellpadding="2" style="width: 90%;margin-left: 30px;">
		<tbody>
			<tr>
				<td style="float: left;">Customer Name</td>
                <td style="float: left;">{{$customer}}</td>
                <td></td>
                <td></td>
			</tr>
		</tbody>
	</table>
	<br><br>
	<table border="0" cellspacing="0" cellpadding="3" style="width: 90%;margin-left: 30px;">
		<thead style="background-color: #80808045;text-align: center;line-height: 154%;">
			<tr>
				<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;border-color: transparent;">#</td>
				<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;border-color: transparent;">Item</td>
				<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;border-color: transparent;">Qty</td>
				<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;border-color: transparent;">Rate</td>
				<td style="border-left:1px solid;border-top: 1px solid;border-bottom: 1px solid;border-color: transparent;">Tax</td>
				<td style="border-left:1px solid;border-top: 1px solid;border-right: 1px solid;border-bottom: 1px solid;border-color: transparent;">Amount</td>
			</tr>
		</thead>
		<tbody style="text-align: center;line-height: 154%;">
			<?php
			$i = 1;
			foreach ($proposal_details as $key => $proposal_detail) { ?>
			<tr>
				<td>{{$i}}</td>
				<td>{{$proposal_detail->name}}</td>
				<td>{{$proposal_detail->qty}}</td>
				<td>₹{{number_format($proposal_detail->rate,2)}}</td>
				<td>{{$proposal_detail->tax}}%</td>
				<td>₹{{number_format($proposal_detail->amount,2)}}</td>
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
				<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{number_format($total_amount,2)}}</td>
			</tr>
			<tr>
				<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
                <td style="border-top: 1px solid;text-align: right;border-color: transparent;">Total Discount</td>
				<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{number_format($discount,2)}}</td>
			</tr>
			<tr>
				<td style="border-left: 1px solid; border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;border-color: transparent;"></td>
				<td style="border-top: 1px solid;text-align: right;border-color: transparent;">Total Tax</td>
				<td style="border-top: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{number_format($total_tax_amount,2)}}</td>
			</tr>
			<tr>
				<td style="border-bottom: 1px solid;border-left: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;border-color: transparent;"></td>
				<td style="border-bottom: 1px solid;text-align: right;border-color: transparent;">Grand Total</td>
				<td style="border-bottom: 1px solid;border-right: 1px solid;text-align: center;border-color: transparent;">₹{{number_format($grand_total,2)}}</td>
            </tr>
		</tfoot>
	</table>
    <br><br>
    @if($tnc)
    <footer>
        <table class="table" style="width: 90%;margin-left: 30px;">
            <tbody>
                <tr>
                    <td class="border-0">TERMS & CONDITIONS :<br/>
                    <?php echo htmlspecialchars_decode($tnc); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </footer>
    @endif
</body>
</html>
