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
                            <div class="card-title mb-3">Add Invoice</div>
                            {!! Form::open(['action' => 'InvoiceController@store', 'method' => 'post','autocomplete' => 'off']) !!} 
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="customer_id">Customer*</label>
                                        <select id="customer_id" name="customer_id" class="js-example-basic-single" required="">
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
                                        <textarea id="address" name="address" class="form-control"></textarea>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('address', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                        <table border="1" cellspacing="3" cellpadding="0" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td>First Name</td>
                                                    <td>Last Name</td>
                                                    <td>Email</td>
                                                    <td>Mobile Number</td>
                                                    <td>GST Number</td>
                                                </tr>
                                            </thead>
                                            <tfoot id="customer_detail"></tfoot>
                                        </table>
                                    </div>

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
                                        <label for="date">Date*</label>
                                        <input type="date" class="form-control" id="date" name="date">
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
                                                        <td style="width: 20%;">
                                                          <select name="products[]" id="products" class="form-control js-example-basic-single products">
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
                                                            <input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="1" />  
                                                          </td>
                                                          <td>
                                                            <input type="text" name="rate[]" id="rate" placeholder="Rate" class="rate form-control">
                                                          </td>
                                                          <td>
                                                              <!-- <input type="text" name="tax[]" id="tax" class="tax form-control" placeholder="Tax"> -->
                                                              <select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple="">
                                                                  <option value="0">No Tax</option>
                                                                  <option value="5.00">5.00%</option>
                                                                  <option value="10.00">10.00%</option>
                                                                  <option value="18.00">18.00%</option>
                                                              </select>
                                                              <input type="hidden" name="tax_amount[]" id="tax_amount" class="tax_amount">
                                                          </td>
                                                          <td>
                                                              <input type="text" name="amount[]" id="amount" class="form-control amount" placeholder="Amount" readonly="" /> 
                                                          </td>
                                                        </tr>
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
                                                                <input type="text" id="total_amount" name="total_amount" style="border:none;width: 82px;text-align: right;"><br>
                                                                <label for="dis_val">Discount:</label>
                                                                <!-- <div id="dis_val" name="dis_val" style="margin-top: -27px;text-align: right;">-₹0.00</div> -->
                                                                <input type="text" id="dis_val" name="dis_val" style="border:none;width: 100px;text-align: right;"><br>
                                                                <label for="total_tax">Total Tax:</label>
                                                                <!-- <div id="total_tax" style="margin-top: -27px;text-align: right;">0%</div> -->
                                                                <input type="text" name="total_tax" id="total_tax" style="border:none;text-align: right;width: 100px;"><br>
                                                                <label for="grand_total">Grand Total:</label>
                                                               <!--  <div id="grand_total" name="grand_total" style="margin-top: -27px;text-align: right;">₹0.00</div> -->
                                                               <input type="text" id="grand_total" name="grand_total" style="border:none;width: 82px;text-align: right;" value="0.0">
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
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
        $('.js-example-basic-single').select2();
        
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

