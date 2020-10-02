@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.bubble.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.snow.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
<link href="{{asset('assets/styles/vendor/select2.min.css')}}" rel="stylesheet" />
<style type="text/css">
        .select2-container {
        width: 100%!important;
    }
</style>
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
                            <div class="card-title mb-3">Edit Invoice</div>

                            {!! Form::model($invoice, ['method' => 'PATCH', 'route' => ['updateInvoice', $invoice->id]]) !!}
                                <div class="row">

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="customer_id">Customer*</label>
                                        <select id="customer_id" name="customer_id" class="js-example-basic-single" required="">
                                            <option value="{{$invoice->c_id}}">{{$invoice->company_name}}</option>
                                            <option value="">Select Customer</option>
                                            @if(!empty($customers))
                                                @foreach($customers as $customer )
                                                    <option value="{{$customer->id}}">{{$customer->company_name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="address">Address*</label>
                                        <textarea id="address" name="address" class="form-control">{{$invoice->billing_address}}</textarea>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('address', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <table border="1" cellspacing="3" cellpadding="0" style="width: 100%;text-align:center;">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Mobile Number</th>
                                                    <th>GST Number</th>
                                                </tr>
                                            </thead>
                                            <tbody id="customer_detail">
                                              <tr>
                                                <td>{{$invoice->first_name}}</td>
                                                <td>{{$invoice->last_name}}</td>
                                                <td>{{$invoice->email}}</td>
                                                <td>{{$invoice->mobile_no}}</td>
                                                <td>{{$invoice->gst_no}}</td>
                                              </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="invoice_number">Invoice Number</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">INV-</span>
                                            </div>
                                           <input type="text" name="invoice_number" id="invoice_number" value="{{$invoice->invoice_number}}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="date">Date*</label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $invoice->date;?>">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('date', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="products">Products</label>
                                            <div class="row">
                                                 <section class="container col-xs-12">
                                                    <div class="table table-responsive">
                                                    <!-- <h4>Select Details</h4> -->
                                                    <table id="ppsale" class="table table-striped table-bordered" border="0">

                                                        <tbody id="TextBoxContainer">
                                                            @if(!empty($invoice_details))
                                                            @foreach($invoice_details as $pro_det)
                                                            <td style="width: 20%;">
                                                              <select name="products[name][]" class="form-control js-example-basic-single products" required="">
                                                                 {{-- <option value="{{$pro_det->p_id}}">{{$pro_det->name}}</option> --}}
                                                                  <option value="">Select Products</option>
                                                                  @if(!empty($products))
                                                                    @foreach($products as $prod )
                                                                    <option value="{{$prod->id}}" {{$pro_det->product_id == $prod->id ? 'selected':''}} data-price="{{$prod->selling_cost}}">
                                                                        {{$prod->name}}
                                                                    </option>
                                                                    @endforeach
                                                                @endif
                                                              </select>
                                                            </td>
                                                              <td>
                                                                <input type="number" name="products[quantity][]" class="form-control quantity" placeholder="Enter Quantity" min="1" value="{{$pro_det->qty}}" />
                                                              </td>
                                                              <td>
                                                                <input type="text" name="products[rate][]" placeholder="Rate" class="rate form-control" value="{{$pro_det->rate}}">
                                                              </td>
                                                              <td>
                                                                  <select name="tax[]" class="tax form-control js-example-basic-multiple" multiple>
                                                                      <option value="5.00" {{ $pro_det->tax == "5.00"?'selected':''}}>5.00%</option>
                                                                      <option value="10.00" {{ $pro_det->tax == "10.00"?'selected':''}}>10.00%</option>
                                                                      <option value="18.00" {{ $pro_det->tax == "18.00"?'selected':''}}>18.00%</option>
                                                                  </select>
                                                                  <?php
                                                                    $qty = $pro_det->qty;
                                                                    $rate = $pro_det->rate;
                                                                    $qty_rate = $qty*$rate;
                                                                    $tax = array_sum(explode(",",$pro_det->tax));
                                                                    $tax_amount = ($qty_rate*$tax) / 100;
                                                                    ?>
                                                                  <input type="hidden" name="products[tax][]" class="htax" value="{{$pro_det->tax}}">
                                                                  <input type="hidden" name="products[tax_amount][]" class="tax_amount" value="{{$tax_amount}}">
                                                              </td>
                                                              <td>
                                                                  <input type="text" name="products[amount][]" class="form-control amount" placeholder="Amount" readonly="" value="{{$pro_det->amount}}" />
                                                              </td>
                                                              <td>
                                                                <button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button>
                                                            </td>
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                      <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>
                                                                <label for="discount">Discount</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                    <input type="number" min="0" max="100" name="discount" id="discount" class="form-control discount" value="{{$discount}}">
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <label for="total_amount">Sub Total:</label>
                                                                <input readonly type="text" id="total_amount" name="total_amount" value="{{$invoice->total_amount}}" style="border:none;float:right;"><br>
                                                                <label for="dis_val">Discount:</label>
                                                                <input readonly type="text" id="dis_val" name="dis_val" value="{{$discount_value}}" style="border:none;float:right;"><br>
                                                                <label for="total_tax">Total Tax:</label>
                                                                <input readonly type="text" name="total_tax" id="total_tax" value="{{$invoice->total_tax_amount}}" style="border:none;float:right;"><br>
                                                                <label for="grand_total">Total:</label>
                                                               <input readonly type="text" id="grand_total" name="grand_total" value="{{$invoice->grand_total}}" style="border:none;float:right;">
                                                            </th>
                                                          <th colspan="5">
                                                          <button id="btnAdd" type="button" class="btn btn-success" data-toggle="tooltip" data-original-title="Add more" style="float: right;">+</button></th>
                                                        </tr>
                                                      </tfoot>
                                                    </table>
                                                    </div>
                                                  </section>
                                            </div>
                                    </div>
                                    <div class="col-md-12">
                                         <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
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
<script src="{{asset('assets/js/quill.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/quill.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>

<script type="text/javascript">
    $('#customer_id').change(function(){
        var customer_id = $(this).val();
        if(customer_id){
            jQuery.ajax({
                type: "POST",
                url: '{{ URL::route("CustomerAddress") }}',
                dataType: 'text',
                data: {customer_id:customer_id},
                success: function(data)
                {
                    //console.log(data);
                    var html = '';
                    var obj = jQuery.parseJSON(data);
                    //console.log(obj.address);
                    $('#address').html(obj.address);
                    html = '<tr><td>'+obj.first_name+'</td><td>'+obj.last_name+'</td><td>'+obj.email+'</td><td>'+obj.mobile_no+'</td><td>'+obj.gst_no+'</td></tr>';
                    $('#customer_detail').html(html);
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('.js-example-basic-multiple').select2();

        $("#btnAdd").bind("click", function () {
            var div = $("<tr />");
            div.html(GetDynamicTextBox(""));
            $("#TextBoxContainer").append(div);
            $('.js-example-basic-single').select2();
            $('.js-example-basic-multiple').select2();

        });
        $("body").on("click", ".remove", function () {
            var sub = $(this).closest("tr").find("input.amount").val();
            if (sub != '') {
                $(this).closest("tr").remove();
                all_in_one();
            }else{
                $(this).closest("tr").remove();
                all_in_one();
            }
        });

        $("body").on("change", ".products", function () {
            var pro_id = $(this).val();
            var thiss = $(this);
            var price = $(this).find(':selected').data('price');
            thiss.closest("tr").find("input.rate").val(parseFloat(price).toFixed(2));
            var quantity = parseInt(thiss.closest("tr").find("input.quantity").val());
            var rate = $(this).closest("tr").find("input.rate").val();
            var tax = $(this).closest("tr").find("select.tax").val();
            var ammount = rate*quantity;
            var total_tax = 0.0;
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            if(total_tax > 0){
                var amount_tax = parseFloat((ammount*total_tax)/100).toFixed(2);
                $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                // var ttl_am = Number(ammount) + Number(amount_tax);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.tax_amount").val(0);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }
            all_in_one();
        });
        $("body").on("change", ".quantity", function () {
            var quantity = $(this).val();
            var rate = $(this).closest("tr").find("input.rate").val();
            var tax = $(this).closest("tr").find("select.tax").val();
            var ammount = rate*quantity;
            var total_tax = 0.0;
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            if(total_tax > 0){
                var amount_tax = parseFloat((ammount*total_tax)/100).toFixed(2);
                $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                // var ttl_am = Number(ammount) + Number(amount_tax);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.tax_amount").val(0);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }
            all_in_one();
        });
        $("body").on("change",".tax",function(){
            var quantity = $(this).closest("tr").find("input.quantity").val();
            var rate = $(this).closest("tr").find("input.rate").val();
            var tax = $(this).val();
            $(this).closest("tr").find("input.htax").val(tax);
            console.log(tax);
            var ammount = rate*quantity;
            var total_tax = 0.0;
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            if(total_tax > 0){
                var amount_tax = parseFloat((ammount*total_tax)/100).toFixed(2);
                $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                // var ttl_am = Number(ammount) + Number(amount_tax);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.tax_amount").val(0);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }
            all_in_one();
        });
        $("body").on("keyup",".rate",function(){
            var quantity = $(this).closest("tr").find("input.quantity").val();
            var rate = $(this).val();
            var tax = $(this).closest("tr").find("select.tax").val();
            var ammount = rate*quantity;
            var total_tax = 0.0;
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            if(total_tax > 0){
                var amount_tax = parseFloat((ammount*total_tax)/100).toFixed(2);
                $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                // var ttl_am = Number(ammount) + Number(amount_tax);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.tax_amount").val(0);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }
        });
        $("body").on("change",".discount",function(){
            $(this).val($(this).val());
            all_in_one();
        });
        function all_in_one(){
            var ttax = tamt = gttl = 0;
            $('.tax_amount').each(function(){
                ttax += Number($(this).val());
            });
            $('.amount').each(function(){
                tamt += Number($(this).val());
            });
            $('#total_amount').val(parseFloat(tamt,2).toFixed(2)).change();
            $('#total_tax').val(parseFloat(ttax,2).toFixed(2)).change();

            var disc_rate = Number($('.discount').val());
            var discount_value = parseFloat((tamt*disc_rate)/100).toFixed(2);
            $('#dis_val').val(parseFloat(discount_value,2).toFixed(2));
            console.log(tamt,discount_value,ttax);
            gttl = (tamt - discount_value) + ttax;
            $('#grand_total').val(parseFloat(gttl,2).toFixed(2)).change();
        }
      });
      function GetDynamicTextBox(value)
      {
          return '<td><select name="products[name][]" id="products" class="form-control js-example-basic-single products" required><option value="">Select Products</option>@if(!empty($products)) @foreach($products as $prod )<option value="{{$prod->id}}" data-price="{{$prod->selling_cost}}">{{$prod->name}}</option>@endforeach @endif</select></td><td><input type="number" name="products[quantity][]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="1" /></td><td><input type="text" name="products[rate][]" id="rate" placeholder="Rate" class="form-control rate"></td><td><select name="tax[]" class="tax form-control js-example-basic-multiple" multiple><option value="5.00">5.00%</option><option value="10.00">10.00%</option><option value="18.00">18.00%</option></select><input type="hidden" name="products[tax][]" class="htax"><input type="hidden" name="products[tax_amount][]" id="tax_amount" class="tax_amount"></td><td><input type="text" name="products[amount][]" id="amount" class="form-control amount" placeholder="Amount" readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td>';
      }
</script>

@endsection

