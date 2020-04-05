@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Invoice </h1> 
    </div>
            <div class="separator-breadcrumb border-top"></div>

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" id="add_converted" href="{{route('InvoiceAdd')}}" class="btn btn-primary" style="float: right;"> Add Invoice </a>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="date_from">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_to">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo date('Y-m-d');?>">
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-6" style="margin-top: 23px;">
                                    <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                    <button id="export_invoice" class="btn btn-outline-secondary" name="btn">Export Invoice</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered insert_to_excel" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Invoice Number</th>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Discount</th>
                                            <th>Total Amount</th>
                                            <th>Grand Total</th>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                                                <th>User ID</th>
                                            <?php }?>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody class="filter_data">
                                        @foreach($list_invoices as $list_invoice)
                                        <tr>
                                            <td>{{$list_invoice->id}}</td>
                                            <td>{{$list_invoice->invoice_number}}</td>
                                            <td>{{$list_invoice->first_name.' '.$list_invoice->last_name}}</td>
                                            <td>{{$list_invoice->date}}</td>
                                            <td>{{$list_invoice->discount}}</td>
                                            <td>{{$list_invoice->total_amount}}</td>
                                            <td>{{$list_invoice->grand_total}}</td>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                                                <td>{{$list_invoice->user_id}}</td>
                                            <?php }?>
                                            <td>
                                                <a href="{{ route('editInvoice', $list_invoice->id) }}" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('deleteInvoice', $list_invoice->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>  
                                                <a href="javascript:void(0)" class="text-warning mr-2" data-toggle="modal" data-target="#payment" data-id="{{$list_invoice->id}}" data-amount="{{$list_invoice->grand_total}}" id="payment_modal"><i class="fa fa-credit-card" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('ViewInvoice', $list_invoice->id) }}" class="text-info mr-2"><i class="nav-icon fa fa-eye font-weight-bold"></i>
                                                </a>  
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Invoice Number</th>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Discount</th>
                                            <th>Total Amount</th>
                                            <th>Grand Total</th>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                                                <th>User ID</th>
                                            <?php }?>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
            </div>

            <div class="modal fade Payment" id="payment" tabindex="-1" role="dialog" aria-lavelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
                            
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="modal-header">
                                        <div class="col-md-12 modal-title" style="font-size: 20px;">Add Payment<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['action' => 'InvoiceController@payment', 'method' => 'post']) !!}
                                        <form method="post">
                                             {{ csrf_field() }}
                                            <div class="modal-body">
                                                <input type="hidden" name="invoice_id" id="invoice_id">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="amount">Payment Amount*</label>
                                                                <input type="text" name="amount" id="amount" class="form-control" required="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="transaction_id">Transaction ID</label>
                                                                <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="payment_date">Payment Date*</label>
                                                                <input type="date" name="payment_date" id="payment_date" class="form-control" required="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="payment_mode">Payment Mode*</label>
                                                                <select id="payment_mode" class="form-control" name="payment_mode">
                                                                    <option value="">Select Payment</option>
                                                                    <option value="Bank">Bank</option>
                                                                    <option value="Cash">Cash</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="note">Note</label>
                                                                <textarea id="note" name="note" placeholder="Admin Note" class="form-control" style="height: 150px;"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer col-md-12">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary" style="float: right;">Submit</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.table2excel.min.js')}}"></script>

<script type="text/javascript">
    /*$('#payment_modal').click(function(){*/
    $(document).on("click", "#payment_modal", function () {
        var myid = $(this).data('id');
        var amount = parseFloat($(this).data('amount')).toFixed(2);
        $(".Payment #invoice_id").val(myid);
        $(".Payment #amount").val(amount);

    });
</script>
<script type="text/javascript">
    $('#btn').click(function(){
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        //alert(date_from+date_to);
        jQuery.ajax({
            type: "POST",
            url: '{{ URL::route("FilterDataInvoice") }}',
            dataType: 'text',
            data: {date_to:date_to,date_from:date_from},
            success: function(data) 
            {
                //console.log(data);
                var obj = jQuery.parseJSON(data);
                //console.log(obj);
                var html = '';

                $.each(obj,function(index,data){
                    //alert(index+data.tbl_booking_id);
                    var url_edit = '{{ route("editInvoice", ":data") }}';

                    url_edit = url_edit.replace(':data', data.id);

                    var url_delete = '{{ route("deleteInvoice", ":data") }}';

                    url_delete = url_delete.replace(':data',data.id);

                    var url_view = '{{ route("ViewInvoice", ":data") }}';

                    url_view = url_view.replace(':data',data.id);

                    html += '<tr><td>'+data.id+'</td>'+'<td>'+data.invoice_number+'</td>'+'<td>'+data.first_name+'</td>'+'<td>'+data.date+'</td>'+'<td>'+data.discount+'</td>'+'<td>'+data.total_amount+'</td>'+'<td>'+data.grand_total+'</td>'+'<td>'+data.user_id+'</td>'+'<td><a href="'+url_edit+'" class="text-success mr-2"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a><a href="'+url_delete+'" onclick="return confirm("Are you sure you want to delete this Data?")" class="text-danger mr-2"><i class="nav-icon i-Close-Window font-weight-bold"></i></a> <a href="javascript:void(0)" class="text-warning mr-2" data-toggle="modal" data-target="#payment" data-id="'+data.id+'" data-amount="{{$list_invoice->grand_total}}" id="payment_modal"><i class="fa fa-credit-card" aria-hidden="true"></i></a><a href="'+url_view+'" class="text-info mr-2"><i class="nav-icon fa fa-eye font-weight-bold"></i></a></td>';

                });
                //alert(html);
                $('.filter_data').html(html);
            }
        });
    });

    $('#export_invoice').click(function(){
        //alert('excel');
        exportexcel();
    });

    function exportexcel() {  
        $(".insert_to_excel").table2excel({  
            name: "Table2Excel",  
            filename: "Invoice_Data",  
            fileext: ".xls"  
        });  
    }  
</script>
@endsection
