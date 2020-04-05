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
                            <div class="card-title mb-3">Edit Proposal<a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#invoice" style="float: right;">Convert to Invoice
                            </a></div>

                            {!! Form::model($proposal, ['method' => 'PATCH', 'route' => ['updateProposal', $proposal->id]]) !!}
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="subject">Subject*</label>
                                        <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" value="<?php echo $proposal->subject;?>">
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
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $proposal->date;?>">
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
                                                        @foreach($proposal_details as $pro_det )
                                                        <td style="width: 20%;">
                                                          <select name="products[]" id="products" class="form-control js-example-basic-single products">
                                                             <option value="{{$pro_det->p_id}}">{{$pro_det->name}}</option>
                                                              <option>Select Products</option>
                                                              @if(!empty($products))
                                                                @foreach($products as $prod )
                                                                    <option value="{{$prod->id}}">{{$prod->name}}
                                                                    </option>
                                                                @endforeach
                                                            @endif 
                                                          </select> 
                                                        </td>
                                                          <td>
                                                            <input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="{{$pro_det->qty}}" />  
                                                          </td>
                                                          <td>
                                                            <input type="text" name="rate[]" id="rate" placeholder="Rate" class="rate form-control" value="{{$pro_det->rate}}">
                                                          </td>
                                                          <td>
                                                              <!-- <input type="text" name="tax[]" id="tax" class="tax form-control" placeholder="Tax" value="{{$pro_det->tax}}"> -->
                                                              <select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple="">
                                                                <!-- <option value="{{$pro_det->tax}}">{{$pro_det->tax}}%</option> -->
                                                                <?php
                                                                  $tax_ex = '';
                                                                  $tax_ex = explode(",",$pro_det->tax);
                                                                  //print_r($tax_ex);
                                                                  for ($i=0; $i < count($tax_ex); $i++) { 
                                                                      if ($tax_ex[$i] == '5.00') { ?>
                                                                          <option value="5.00" selected="">5.00%</option>
                                                                      <?php }elseif ($tax_ex[$i] == '10.00') { ?>
                                                                          <option value="10.00" selected="">10.00%</option>
                                                                      <?php }elseif ($tax_ex[$i] == '18.00') { ?>
                                                                         <option value="18.00" selected="">18.00%</option>
                                                                      <?php }
                                                                  }
                                                                  ?>
                                                                  <option value="0">No Tax</option>
                                                                  <option value="5.00">5.00%</option>
                                                                  <option value="10.00">10.00%</option>
                                                                  <option value="18.00">18.00%</option>
                                                              </select>
                                                              <input type="hidden" name="tax_amount[]" id="tax_amount" class="tax_amount">
                                                          </td>
                                                          <td>
                                                              <input type="text" name="amount[]" id="amount" class="form-control amount" placeholder="Amount" readonly="" value="{{$pro_det->amount}}" /> 
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
                                                                   <input type="text" name="discount" id="discount" class="form-control discount">
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <label for="total_amount">Sub Amount:</label>
                                                                <!-- <div id="total_amount" name="total_amount" style="margin-top: -27px;text-align: right;">0.00</div> -->
                                                                <input type="text" id="total_amount" name="total_amount" value="<?php echo sprintf("%.2f", $proposal->total_amount);?>"  style="border:none;width: 82px;text-align: right;"><br>
                                                                <label for="dis_val">Discount:</label>
                                                                <!-- <div id="dis_val" name="dis_val" style="margin-top: -27px;text-align: right;">-₹0.00</div> -->
                                                                <input type="text" id="dis_val" name="dis_val" value="<?php echo sprintf("%.2f", $proposal->discount);?>" style="border:none;width: 100px;text-align: right;"><br>
                                                                
                                                                <label for="total_tax">Total Tax:</label>
                                                                <!-- <div id="total_tax" style="margin-top: -27px;text-align: right;">0%</div> -->
                                                                <input type="text" name="total_tax" id="total_tax" value="<?php echo sprintf("%.2f", $proposal->total_tax_amount);?>"style="border:none;text-align: right;width: 100px;"><br>

                                                                <label for="grand_total">Grand Total:</label>
                                                               <!--  <div id="grand_total" name="grand_total" style="margin-top: -27px;text-align: right;">₹0.00</div> -->
                                                               <input type="text" id="grand_total" name="grand_total" value="<?php echo sprintf("%.2f", $proposal->grand_total);?>" style="border:none;width: 82px;text-align: right;">
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

            <div class="modal fade Invoice" id="invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="margin-top: 80px;margin-left: 20px;">
                <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Invoice<a href="" style="float: right;">X</a></div>
                            {!! Form::open(['action' => 'InvoiceController@store', 'method' => 'post','autocomplete' => 'off']) !!} 
                                <div class="row">
                                    <!-- <div class="col-md-6 form-group mb-3">
                                        <label for="subject">Subject*</label>
                                        <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" value="<?php echo $proposal->subject;?>">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                    </div> -->

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
                                        <label for="products">Products</label>
                                            <div class="row">
                                                 <section class="container col-xs-12">
                                                    <div class="table table-responsive">
                                                    <!-- <h4>Select Details</h4> -->
                                                    <table id="ppsale" class="table table-striped table-bordered" border="0">
                                                      
                                                      <tbody id="TextBoxContainer">
                                                        @if(!empty($proposal_details))
                                                        @foreach($proposal_details as $pro_det )
                                                        <td style="width: 20%;">
                                                          <select name="products[]" id="products" class="form-control js-example-basic-single products">
                                                             <option value="{{$pro_det->p_id}}">{{$pro_det->name}}</option>
                                                              <option>Select Products</option>
                                                              @if(!empty($products))
                                                                @foreach($products as $prod )
                                                                    <option value="{{$prod->id}}">{{$prod->name}}
                                                                    </option>
                                                                @endforeach
                                                            @endif 
                                                          </select> 
                                                        </td>
                                                          <td>
                                                            <input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="{{$pro_det->qty}}" />  
                                                          </td>
                                                          <td>
                                                            <input type="text" name="rate[]" id="rate" placeholder="Rate" class="rate form-control" value="{{$pro_det->rate}}" readonly="">
                                                          </td>
                                                          <td>
                                                              <input type="text" name="tax[]" id="tax" class="tax form-control" placeholder="Tax" value="{{$pro_det->tax}}">
                                                          </td>
                                                          <td>
                                                              <input type="text" name="amount[]" id="amount" class="form-control amount" placeholder="Amount" readonly="" value="{{$pro_det->amount}}" /> 
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
                                                                   <input type="text" name="discount" id="discount" class="form-control discount">
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <label for="total_amount">Sub Amount:</label>
                                                                <!-- <div id="total_amount" name="total_amount" style="margin-top: -27px;text-align: right;">0.00</div> -->
                                                                <input type="text" id="total_amount" name="total_amount" value="<?php echo sprintf("%.2f", $proposal->total_amount);?>"  style="border:none;width: 82px;text-align: right;"><br>
                                                                <label for="dis_val">Discount:</label>
                                                                <!-- <div id="dis_val" name="dis_val" style="margin-top: -27px;text-align: right;">-₹0.00</div> -->
                                                                <input type="text" id="dis_val" name="dis_val" value="<?php echo sprintf("%.2f", $proposal->discount);?>" style="border:none;width: 100px;text-align: right;"><br>
                                                                
                                                                <label for="total_tax">Total Tax:</label>
                                                                <!-- <div id="total_tax" style="margin-top: -27px;text-align: right;">0%</div> -->
                                                                <input type="text" name="total_tax" id="total_tax" value="<?php echo sprintf("%.2f", $proposal->total_tax_amount);?>"style="border:none;text-align: right;width: 100px;"><br>

                                                                <label for="grand_total">Grand Total:</label>
                                                               <!--  <div id="grand_total" name="grand_total" style="margin-top: -27px;text-align: right;">₹0.00</div> -->
                                                               <input type="text" id="grand_total" name="grand_total" value="<?php echo sprintf("%.2f", $proposal->grand_total);?>" style="border:none;width: 82px;text-align: right;">
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

        var a = new Array();
        $(".tax").children("option").each(function(x){
            test = false;
            b = a[x] = $(this).val();
            for (i=0;i<a.length-1;i++){
                if (b ==a[i]) test =true;
            }
            if (test) $(this).remove();
        })
        
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
                total_amount();
                discount();
               total_tax();
            }else{
                $(this).closest("tr").remove();
                total_amount();
                discount();
                total_tax();
            }
          
        });

        $("body").on("change", ".products", function () {
            //alert($(this).val());
            var pro_id = $(this).val();
            var thiss = $(this);
            //alert(thiss);
            jQuery.ajax({
                type: "POST",
                url: '{{ URL::route("ProductAmount") }}',
                dataType: 'text',
                data: {pro_id:pro_id},
                success: function(data) 
                {
                    //console.log(data);
                    var obj = jQuery.parseJSON(data);
                    //console.log(obj[0].selling_cost);
                   
                    thiss.closest("tr").find("input.rate").val(parseFloat(obj[0].selling_cost).toFixed(2));
                    
                    thiss.closest("tr").find("input.amount").val(parseFloat(obj[0].selling_cost).toFixed(2));

                    total_amount();
                    discount();
                    total_tax();
                    /*$('.pro_amount').val(obj[0].selling_cost);*/
                }
            });
        });

        $("body").on("change", ".quantity", function () {
            //alert($(this).val());
            var quantity = $(this).val();
            var am = $(this).closest("tr").find("input.rate").val();
            var sub_amount = quantity * am;
            //alert(quantity*am);
            $(this).closest("tr").find("input.amount").val(parseFloat(sub_amount).toFixed(2));
            total_amount();
            discount();
        });

        $("body").on("change",".tax",function(){
            var thisss = $(this);
            var tax = $(this).val();
            var am = 0.0;
            var amount_tax = 0.0;
            var total_tax = 0.0;
            //alert(tax);
            $.each(tax,function(index,data){
                //alert(data);
                total_tax += Number(data);
            });
            am = thisss.closest("tr").find("input.amount").val();
            amount_tax = parseFloat((am*total_tax)/100).toFixed(2);
            //alert(total_tax);
            $(this).closest("td").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
            $('#total_tax').val(parseFloat(amount_tax).toFixed(2));
            //$('#total_tax').html(tax+"%");
        });

        $("body").on("keyup",".rate",function(){
            var rate = $(this).closest("tr").find("input.rate").val();
            $(this).closest("tr").find("input.amount").val(parseFloat(rate).toFixed(2));
            //alert(rate);
        });

      });
      function GetDynamicTextBox(value) 
      {
          return '<td><select name="products[]" id="products" class="form-control js-example-basic-single products"><option>Select Products</option>@if(!empty($products)) @foreach($products as $prod )<option value="{{$prod->id}}">{{$prod->name}}</option>@endforeach @endif</select></td><td><input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="1" /></td><td><input type="text" name="rate[]" id="rate" placeholder="Rate" class="form-control rate"></td><td><select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple><option value="0">No Tax</option><option value="5.00">5.00%</option><option value="10.00">10.00%</option><option value="18.00">18.00%</option></select><input type="hidden" name="tax_amount[]" id="tax_amount" class="tax_amount"></td><td><input type="text" name="amount[]" id="amount" class="form-control amount" placeholder="Amount" readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td>';
      }

      function total_amount(){
                var sum = 0.0;
                $('.amount').each(function(){
                    //alert($(this).val());
                    sum += Number($(this).val()); 
                });
                $('#total_amount').val(parseFloat(sum).toFixed(2));
            }

    function discount(){
        $('#discount').keyup(function(){
            var discount = 0.0;
            var sub_total = $('#total_amount').val();
            //alert(sub_total);
            var dis_val = $(this).val();
            discount = parseFloat((sub_total*dis_val)/100).toFixed(2);
            var tax = $('#total_tax').val();
            //alert(tax);
            //alert(discount);
            $('#dis_val').val(discount);
            var grand_total = sub_total - discount + Number(tax);
            //alert(grand_total);
            $('#grand_total').val(parseFloat(grand_total).toFixed(2));
        });
    }

    function total_tax(){
        var tax = 0;
        $('.tax_amount').each(function(){
            tax += Number($(this).val());
        });
        //alert(tax);
        $('#total_tax').val(tax);
    };
</script>

@endsection

