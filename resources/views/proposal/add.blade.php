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
                            <div class="card-title mb-3">Add Proposal</div>
                            {!! Form::open(['action' => 'ProposalController@store', 'method' => 'post','autocomplete' => 'off']) !!}
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="subject">Subject*</label>
                                        <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" required="">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="customer_id">Customer*</label>
                                        <select id="customer_id" name="customer_id" class="js-example-basic-single" required="">
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
                                        <input type="date" class="form-control" id="date" name="date" required="">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('date', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="products">Products</label>
                                            <div class="row">
                                                <section class="col-md-12">
                                                    <div class="table table-responsive">
                                                    <table id="ppsale" class="table table-striped table-bordered" border="0">
                                                      <tbody id="TextBoxContainer">
                                                        <tr>
                                                            <td style="text-align: center;width:27%;">Product Name</td>
                                                            <td style="text-align: center;width:10%;">Quantity</td>
                                                            <td style="text-align: center;width:10%;">Rate</td>
                                                            <td style="text-align: center;width:10%;">Discount(%)</td>
                                                            <td style="text-align: center;width:15%;">Tax(%)</td>
                                                            <td style="text-align: center;width:25%;">Amount</td>
                                                            <td style="text-align: center;width:3%;"></td>
                                                        </tr>
                                                        <tr>
                                                        <td style="width: 27%;">
                                                          <select name="products[name][]" id="products" class="form-control js-example-basic-single products" required="">
                                                              <option value="">Select Products</option>
                                                              @if(!empty($products))
                                                                @foreach($products as $prod )
                                                                <option value="{{$prod->id}}" data-price="{{$prod->selling_cost}}">
                                                                    {{$prod->name}}
                                                                </option>
                                                                @endforeach
                                                            @endif
                                                          </select>
                                                          <td style="width: 10%;">
                                                            <input type="number" name="products[quantity][]" id="quantity" class="form-control quantity" placeholder="Quantity" min="1" value="1" />
                                                            </td>
                                                            <td style="width: 10%;">
                                                            <input type="text" name="products[rate][]" id="rate" placeholder="Rate" class="rate form-control">
                                                            </td>
                                                            <td style="width: 10%;">
                                                            <input type="number"  placeholder="Discount" min="0" max="100" name="products[product_discount][]" class="form-control product_discount" value="0">
                                                            <input type="hidden" name="products[discount_amount][]" id="discount_amount" class="discount_amount">
                                                            </td>
                                                            <td style="width: 15%;">
                                                                <select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple>
                                                                    <option value="5.00">5</option>
                                                                    <option value="10.00">10</option>
                                                                    <option value="18.00">18</option>
                                                                </select>
                                                                <input type="hidden" name="products[tax][]" class="htax">
                                                                <input type="hidden" name="products[tax_amount][]" id="tax_amount" class="tax_amount">
                                                                <input type="hidden" name="add_tax" id="add_tax">
                                                            </td>
                                                            <td style="width: 25%;">
                                                                <input type="text" name="products[amount][]" id="amount" class="form-control amount" placeholder="Amount" readonly="" />
                                                            </td>
                                                            <td style="width:3%;"></td>
                                                        </tr>
                                                      </tbody>
                                                      <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>
                                                                {{-- <label for="discount">Discount</label>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                   <input type="number" min="0" max="100" name="discount" id="discount" class="form-control discount" value="0">
                                                                </div> --}}
                                                            </th>
                                                            <th>
                                                                <label for="total_amount">Sub Total:</label>
                                                                <input readonly type="text" id="total_amount" name="total_amount" style="border:none;float:right;"><br>
                                                                <label for="dis_val">Discount:</label>
                                                                <input readonly type="text" id="dis_val" name="dis_val" style="border:none;float:right;"><br>
                                                                <label for="total_tax">Total Tax:</label>
                                                                <input readonly type="text" name="total_tax" id="total_tax" style="border:none;float:right;"><br>
                                                                <label for="grand_total">Total:</label>
                                                               <input readonly type="text" id="grand_total" name="grand_total" style="border:none;float:right;" value="0.0">
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
            if(pro_id){
                var thiss = $(this);
                var price = $(this).find(':selected').data('price');
                thiss.closest("tr").find("input.rate").val(parseFloat(price).toFixed(2));
                var quantity = parseInt(thiss.closest("tr").find("input.quantity").val());
                var rate = $(this).closest("tr").find("input.rate").val();
                var tax = $(this).closest("tr").find("select.tax").val();
                var ammount = rate*quantity;
                var total_tax = 0.0;
                var disct = $(this).closest("tr").find("input.product_discount").val();
                if(disct > 0){
                    var dis_amount = parseFloat((ammount*disct)/100).toFixed(2);
                    $(this).closest("tr").find("input.discount_amount").val(parseFloat(dis_amount).toFixed(2));
                }else{
                    $(this).closest("tr").find("input.discount_amount").val(parseFloat(0));
                }
                $.each(tax,function(index,data){
                    total_tax += Number(data);
                });
                var dsamnt = Number($(this).closest("tr").find("input.discount_amount").val());
                if(total_tax > 0){
                var amount_tax = parseFloat(((ammount - dsamnt)*total_tax)/100).toFixed(2);
                console.log(amount_tax);
                    $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                    // var ttl_am = Number(ammount) + Number(amount_tax);
                    $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
                }else{
                    $(this).closest("tr").find("input.tax_amount").val(0);
                    $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
                }
                all_in_one();
            }
        });
        $("body").on("change", ".quantity", function () {
            var quantity = $(this).val();
            var rate = $(this).closest("tr").find("input.rate").val();
            var tax = $(this).closest("tr").find("select.tax").val();
            var ammount = rate*quantity;
            var total_tax = 0.0;
            var disct = $(this).closest("tr").find("input.product_discount").val();
            if(disct > 0){
                var dis_amount = parseFloat((ammount*disct)/100).toFixed(2);
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(dis_amount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(0));
            }
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            var dsamnt = Number($(this).closest("tr").find("input.discount_amount").val());
            if(total_tax > 0){
                var amount_tax = parseFloat(((ammount - dsamnt)*total_tax)/100).toFixed(2);
                console.log(amount_tax);
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
            var disct = $(this).closest("tr").find("input.product_discount").val();
            if(disct > 0){
                var dis_amount = parseFloat((ammount*disct)/100).toFixed(2);
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(dis_amount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(0));
            }
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            var dsamnt = Number($(this).closest("tr").find("input.discount_amount").val());
            if(total_tax > 0){
                var amount_tax = parseFloat(((ammount - dsamnt)*total_tax)/100).toFixed(2);
                console.log(amount_tax);
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
            var disct = $(this).closest("tr").find("input.product_discount").val();
            if(disct > 0){
                var dis_amount = parseFloat((ammount*disct)/100).toFixed(2);
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(dis_amount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(0));
            }
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            var dsamnt = Number($(this).closest("tr").find("input.discount_amount").val());
            if(total_tax > 0){
                var amount_tax = parseFloat(((ammount - dsamnt)*total_tax)/100).toFixed(2);
                console.log(amount_tax);
                $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                // var ttl_am = Number(ammount) + Number(amount_tax);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.tax_amount").val(0);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }
            all_in_one();
        });
        $("body").on("change",".product_discount",function(){
            var disct = Number($(this).val());
            var quantity = $(this).closest("tr").find("input.quantity").val();
            var rate = $(this).closest("tr").find("input.rate").val();
            var tax = $(this).closest("tr").find("select.tax").val();
            var ammount = rate*quantity;
            var total_tax = 0.0;
            if(disct > 0){
                var dis_amount = parseFloat((ammount*disct)/100).toFixed(2);
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(dis_amount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.discount_amount").val(parseFloat(0));
            }
            $.each(tax,function(index,data){
                total_tax += Number(data);
            });
            var dsamnt = Number($(this).closest("tr").find("input.discount_amount").val());
            if(total_tax > 0){
                var amount_tax = parseFloat(((ammount - dsamnt)*total_tax)/100).toFixed(2);
                console.log(amount_tax);
                console.log(dsamnt,disct,amount_tax);
                $(this).closest("tr").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
                // var ttl_am = Number(ammount) + Number(amount_tax);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }else{
                $(this).closest("tr").find("input.tax_amount").val(0);
                $(this).closest("tr").find("input.amount").val(parseFloat(ammount).toFixed(2));
            }
            all_in_one();
        });
        // $("body").on("change",".discount",function(){
        //     $(this).val($(this).val());
        //     all_in_one();
        // });
        function all_in_one(){
            var ttax = tamt = gttl = tdis = 0;
            $('.tax_amount').each(function(){
                ttax += Number($(this).val());
            });
            $('.amount').each(function(){
                tamt += Number($(this).val());
            });
            $('.discount_amount').each(function(){
                tdis += Number($(this).val());
            });
            $('#total_amount').val(parseFloat(tamt,2).toFixed(2)).change();
            $('#total_tax').val(parseFloat(ttax,2).toFixed(2)).change();
            $('#dis_val').val(parseFloat(tdis,2).toFixed(2)).change();

            // var disc_rate = Number($('.discount').val());
            // var discount_value = parseFloat((tamt*disc_rate)/100).toFixed(2);
            // $('#dis_val').val(parseFloat(discount_value,2).toFixed(2));
            gttl = (tamt - tdis) + ttax;
            console.log(tamt,tdis,ttax,gttl);
            $('#grand_total').val(parseFloat(gttl,2).toFixed(2)).change();
        }
      });
      function GetDynamicTextBox(value)
      {
          return '<td style="width: 27%"> <select name="products[name][]" id="products" class="form-control js-example-basic-single products"> <option>Select Products</option> @if(!empty($products)) @foreach($products as $prod) <option value="{{$prod->id}}" data-price="{{$prod->selling_cost}}">{{$prod->name}}</option>+ @endforeach @endif </select> </td> <td style="width: 10%"> <input type="number" name="products[quantity][]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="1" /> </td> <td style="width: 10%"> <input type="text" name="products[rate][]" id="rate" placeholder="Rate" class="form-control rate"> </td> <td style="width: 10%;"> <input type="number" placeholder="Discount" min="0" max="100" name="products[product_discount][]" class="form-control product_discount" value="0"> <input type="hidden" name="products[discount_amount][]" id="discount_amount" class="discount_amount"> </td> <td style="width: 15%"> <select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple> <option value="5.00">5</option> <option value="10.00">10</option> <option value="18.00">18</option> </select> <input type="hidden" name="products[tax][]" class="htax"> <input type="hidden" name="products[tax_amount][]" id="tax_amount" class="tax_amount"> </td> <td style="width: 25%"> <input type="text" name="products[amount][]" id="amount" class="form-control amount" placeholder="Amount" readonly="" /> </td> <td style="width: 3%"> <button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"> <i class="nav-icon i-Close-Window"></i> </button> </td>';
      }
</script>
@endsection
