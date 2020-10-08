<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
  <meta http-equiv="Cache-control" content="no-cache">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSS only -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

  <style type="text/css">
    .page-item.disabled .page-link {
      border-color: #f4f4f4;
    }

    .card .card-header tr {
      border-bottom: 1px solid rgba(0, 0, 0, .1) !important;
    }

    #logo {
      max-width: 130px;
    }

    body {
      font-size: 11px;
    }

    .card-header {
      padding: 3px 10px;
    }

    .table td,
    .table th {
      padding: 3px 10px;
      vertical-align: top;
      word-wrap: normal;

    }

    .invoice-container {
      margin: 15px auto;
      padding: 20px 10px;
      max-width: 1200px;
      background-color: #fff;
      border: 1px solid #ccc;
      -moz-border-radius: 6px;
      -webkit-border-radius: 6px;
      -o-border-radius: 6px;
      border-radius: 6px;
    }

    @media (max-width: 767px) {
      .invoice-container {
        padding: 35px 20px 70px 20px;
        margin-top: 0px;
        border: none;
        border-radius: 0px;
      }
    }

    @media print {

      .table td,
      .table th {
        background-color: transparent !important;
      }

      .table td.bg-light,
      .table th.bg-light {
        background-color: #FFF !important;
      }

      .table td.bg-light-1,
      .table th.bg-light-1 {
        background-color: #f9f9fb !important;
      }

      .table td.bg-light-2,
      .table th.bg-light-2 {
        background-color: #f8f8fa !important;
      }

      .table td.bg-light-3,
      .table th.bg-light-3 {
        background-color: #f5f5f5 !important;
      }

      .table td.bg-light-4,
      .table th.bg-light-4 {
        background-color: #eff0f2 !important;
      }

      .table td.bg-light-5,
      .table th.bg-light-5 {
        background-color: #ececec !important;
      }
    }

    .bg-light {
      background-color: #FFF !important;
    }

    .bg-light-1 {
      background-color: #f9f9fb !important;
    }

    .bg-light-2 {
      background-color: #f8f8fa !important;
    }

    .bg-light-3 {
      background-color: #f5f5f5 !important;
    }

    .bg-light-4 {
      background-color: #eff0f2 !important;
    }

    .bg-light-5 {
      background-color: #ececec !important;
    }

    .border-0 {
      border: 0 !important;
    }
  </style>
</head>

<body>
  <!-- Container -->
  <div class="container-fluid invoice-container">
    <!-- Header -->
    <header>
      <table class="table border-0">
        <tbody>
          <tr>
            <td class="text-right">
              <strong>Invoice Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INV-{{$invoice->invoice_number}}</strong>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table border-0">
        <tbody class="border-0">
            <tr>
                <th colspan="2">Client's Details</th>
                <th colspan="2">Company's Details</th>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>Company Name</strong></th>
                <td class="border-0">{{$invoice->company_name ?? ''}}</td>
                <th class="border-0"><strong>Company Name</strong></th>
                <td class="border-0">{{$company_details->companyname ?? ''}}</td>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>Address</strong></th>
                <td class="border-0">{{$invoice->billing_address}}</td>
                <th class="border-0"><strong>GST Number</strong></th>
                <td class="border-0">{{$company_details->GST ?? ''}}</td>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>First Name</strong></th>
                <td class="border-0">{{$invoice->first_name}}</td>
                <th class="border-0"><strong>Address</strong></th>
                <td class="border-0">{{$company_details->shipping_address ?? ''}}</td>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>Last Name</strong></th>
                <td class="border-0">{{$invoice->last_name ?? ''}}</td>
                <th class="border-0"></th>
                <td class="border-0"></td>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>Email Address</strong></th>
                <td class="border-0">{{$invoice->email ?? ''}}</td>
                <th class="border-0"></th>
                <td class="border-0"></td>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>Phone Number</strong></th>
                <td class="border-0">{{$invoice->mobile_no ?? ''}}</td>
                <th class="border-0"></th>
                <td class="border-0"></td>
            </tr>
            <tr class="border-0">
                <th class="border-0"><strong>GST Number</strong></th>
                <td class="border-0">{{$invoice->gst_no ?? ''}}</td>
                <th class="border-0"></th>
                <td class="border-0"></td>
            </tr>
        </tbody>
      </table>
    </header>

    <!-- Main Content -->
    <main>
      <div class="card border-0">
        <div class="py-0 border-0">
          <table class="table mb-0 border-0">
            <thead class="card-header border-0">
              <tr class="border-0">
                <td class=" border-0"><strong>#</strong></td>
                <td class=" border-0"><strong>Item</strong></td>
                <td class=" border-0"><strong>Qty</strong></td>
                <td class=" border-0"><strong>Rate</strong></td>
                <td class=" border-0"><strong>Tax</strong></td>
                <td class=" border-0"><strong>Amount</strong></td>
              </tr>
            </thead>
            <tbody class="border-0">
              @foreach ($invoice_details as $key=> $item)
              <tr class="border-0">
                <td class=" border-0">{{ $key+1 }}</td>
                <td class=" border-0">{{ $item->name }}</td>
                <td class=" border-0">{{ $item->qty }}</td>
                <td class=" border-0">{{ number_format($item->rate,2) }}</td>
                <td class=" border-0">{{ $item->tax}}</td>
                <td class=" border-0">{{ number_format($item->amount,2) }}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="4" class="bg-light-2 text-right"><strong>Sub Total</strong></td>
                <td colspan="2" class="bg-light-2 text-right">{{ number_format($invoice->total_amount,2) }}</td>
              </tr>
              <tr>
                <td colspan="4" class="bg-light-2 text-right"><strong>Total Discount</strong></td>
                <td colspan="2" class="bg-light-2 text-right">{{ number_format($invoice->discount,2) }}</td>
              </tr>
              <tr>
                <td colspan="4" class="bg-light-2 text-right"><strong>Total Tax</strong></td>
                <td colspan="2" class="bg-light-2 text-right">{{ number_format($invoice->total_tax_amount,2) }}</td>
              </tr>
              <tr>
                <td colspan="4" class="bg-light-2 text-right"><strong>Grand Total</strong></td>
                <td colspan="2" class="bg-light-2 text-right">{{ number_format($invoice->grand_total,2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer>
      <table class="table">
        <tbody>
          <tr>
            <td class="border-0">
              TERMS & CONDITIONS :<br/>
              <?php echo htmlspecialchars_decode($tnc->name); ?>
            </td>
          </tr>
          <tr>
            <td class="border-0">
                <p style="float: right;padding-top:30px;margin-right:10%">Signature</p>
            </td>
          </tr>
        </tbody>
      </table>
    </footer>
</body>
</html>
