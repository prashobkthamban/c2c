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
        <h1> Proposal </h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title mb-3">
                            Edit Proposal
                            <a href="{{ route('mailProposal',$proposal->id) }}" class="btn btn-primary" style="float: right;">Mail</a>
                            <a href="javascript:void(0)" class="btn btn-warning invoice_convert" data-toggle="modal" data-target="#invoice" style="float:right;margin-right:20px;">
                                Convert to Invoice**
                            </a>
                        </div>
                            {!! Form::model($proposal, ['method' => 'PATCH', 'route' => ['updateProposal', $proposal->id]]) !!}
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="subject">Subject*</label>
                                        <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" value="<?php echo $proposal->subject;?>" required>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="customer_id">Customer*</label>
                                        <select id="customer_id" name="customer_id" class="js-example-basic-single" required="">
                                            <option value="{{$proposal->c_id}}">{{$proposal->first_name .' '.$proposal->last_name}}</option>
                                            <option value="">Select Customer</option>
                                            @if(!empty($customers))
                                                @foreach($customers as $customer )
                                                    <option value="{{$customer->id}}">{{$customer->first_name.' '.$customer->last_name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="date">Date*</label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $proposal->date;?>" required >
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
                                                        @if(!empty($proposal_details))
                                                        @foreach($proposal_details as $pro_det)
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
                                                                <input readonly type="text" id="total_amount" name="total_amount" value="{{$proposal->total_amount}}" style="border:none;float:right;"><br>
                                                                <label for="dis_val">Discount:</label>
                                                                <input readonly type="text" id="dis_val" name="dis_val" value="{{$discount_value}}" style="border:none;float:right;"><br>
                                                                <label for="total_tax">Total Tax:</label>
                                                                <input readonly type="text" name="total_tax" id="total_tax" value="{{$proposal->total_tax_amount}}" style="border:none;float:right;"><br>
                                                                <label for="grand_total">Total:</label>
                                                               <input readonly type="text" id="grand_total" name="grand_total" value="{{$proposal->grand_total}}" style="border:none;float:right;">
                                                            </th>
                                                            <th colspan="5">
                                                                <button id="btnAdd" type="button" class="btn btn-success" data-toggle="tooltip" data-original-title="Add more" style="float: right;">+</button>
                                                            </th>
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
                        <div class="card-footer"><label class="text-danger float-right">**Please save Proposal before converting to Invoice.</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- end of row -->
            <div class="modal fade Invoice" id="invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="margin-top: 80px; margin-left: 23%; display: block; max-width: 800px; padding-right: 16px;z-index: -99;">
                <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Invoice<a href="" style="float: right;">X</a></div>
                            {!! Form::open(['action' => 'InvoiceController@store', 'method' => 'post','autocomplete' => 'off']) !!}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="invoice_number">Invoice Number</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">INV-</span>
                                            </div>
                                           <input type="text" name="invoice_number" id="invoice_number" value="00{{$invoice_number+1}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="customer_id">Customer*</label>
                                        <select id="customer_id" name="customer_id" class="js-example-basic-single" required="">
                                            <option value="{{$proposal->c_id}}">{{$proposal->first_name .' '.$proposal->last_name}}</option>
                                            <option value="">Select Customer</option>
                                            @if(!empty($customers))
                                                @foreach($customers as $customer )
                                                    <option value="{{$customer->id}}">{{$customer->first_name.' '.$customer->last_name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="address">Address*</label>
                                        <textarea id="address" name="address" class="form-control"><?php echo $proposal->address;?></textarea>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('address', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="date">Date*</label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $proposal->date;?>">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('date', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="pid" value={{$proposal->id}}>
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('.js-example-basic-multiple').select2();

        $("body").on("click", ".invoice_convert", function () {
            $("#invoice").css('z-index','1050')
        });
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

