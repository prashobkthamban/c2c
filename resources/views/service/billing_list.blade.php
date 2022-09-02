@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Billing Manager</h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

            <!-- search bar -->
            @include('layouts.search_panel', ['request' => '{{request}}'])
            <!-- search bar ends -->

            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="billing_list_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>User</th>
                                        <th>Co-operate</th>
                                        @endif
                                        <th>Credit/Balance</th>
                                        <th>C2C Balance</th>
                                        <th>Call Pulse</th>
                                        <th>C2C Pulse</th>
                                        <th>Billing Mode</th>
                                        <th>Billing Day</th>
                                        <th>CreditLimit</th>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>Recharge</th>
                                        @endif
                                        <th>Transactions & Bills</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $index => $row )
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        @if(Auth::user()->usertype == 'admin')
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->resellername }}</td>
                                        @endif
                                        <td>{{ $row->main_balance }}</td>
                                        <td>{{ $row->c2c_balance }}</td>
                                        <td>{{ $row->call_pulse_setup }}</td>
                                        <td>{{ $row->c2c_pulse_setup }}</td>
                                        <td>{{ $row->billingmode }}</td>
                                        <td>{{ $row->billdate }}</td>
                                        <td>{{ $row->creditlimit }}</td>
                                        @if(Auth::user()->usertype == 'admin')
                                        <td><a href="#" class="text-success mr-2 edit_billing" data-toggle="modal" data-target="#edit_billing" id="{{$row->id}}">
                                                <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                            </a>
                                        </td>
                                        @endif
                                        <td><a href="#" class="text-primary mr-2 bill_details" data-toggle="modal" data-target="#bill_details" id="{{$row->groupid}}">
                                                <i class="nav-icon i-Information font-weight-bold"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>User</th>
                                        <th>Co-operate</th>
                                        @endif
                                        <th>Credit/Balance</th>
                                        <th>C2C Balance</th>
                                        <th>Call Pulse</th>
                                        <th>C2C Pulse</th>
                                        <th>Billing Mode</th>
                                        <th>Billing Day</th>
                                        <th>CreditLimit</th>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>Recharge</th>
                                        @endif
                                        <th>Transactions & Bills</th>
                                    </tr>

                                    </tfoot>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->

            <!-- bill list modal -->
            <div class="modal fade" id="bill_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Bill Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table id="bill_details_list" class="display table table-striped table-bordered" style="width:100%">
                               <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>DateTime</th>
                                        <th>Username</th>
                                        <th>Comments</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                </tbody>
                            </table> 
                                                   
                        </div>
                    </div>
                </div>

            </div>

            <!-- edit bill modal -->
            <div class="modal fade" id="edit_billing" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Edit Billing</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'billing_form', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('id', null, ['id' => 'billing_id']) !!}
                                        {!! Form::hidden('groupid', null, ['id' => 'groupid']) !!}
                                        {!! Form::hidden('main_bal', null, ['id' => 'main_bal']) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">User</label> 
                                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'readonly']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Credit/Balance</label> 
                                        {!! Form::text('main_balance', null, ['class' => 'form-control', 'id' => 'main_balance', 'readonly']) !!}
                                        <button id="reset"  type="button" class="btn btn-success" style="margin: 20px 0px 2px;" />Reset To Zero</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">C2C Balance</label> 
                                        {!! Form::text('c2c_balance', null, ['class' => 'form-control', 'id' => 'c2c_balance']) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Call Pulse</label> 
                                        {!! Form::text('call_pulse_setup', null, ['class' => 'form-control', 'id' => 'call_pulse_setup']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">C2C Pulse</label> 
                                        {!! Form::text('c2c_pulse_setup', null, ['class' => 'form-control', 'id' => 'c2c_pulse_setup']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Billing Mode</label> 
                                        {!! Form::select('billingmode', ['postpaid' => 'Postpaid', 'prepaid' => 'Prepaid'], null,array('class' => 'form-control', 'id' => 'billingmode')) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Bill Cycle day (Only For Postpaid account)</label> 
                                        {!! Form::select('billdate', [], null, ['class' => 'form-control', 'id' => 'billdate']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Credit Limit / Recharge amt</label> 
                                        {!! Form::text('creditlimit', null, ['class' => 'form-control', 'id' => 'creditlimit']) !!}
                                    </div>
                                </div>
                                                   
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                         {!! Form::close() !!}
                    </div>
                </div>

            </div>


@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $('#billing_list_table').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
        $("#reset").click(function() {
            $("#main_balance").val("");
            $("#main_bal").val("");
        });
        function billDateVals() {
            var i=0;
            var billd='';
            while(i < 32)
            {
                i="0"+i;
                $('#billdate').append($('<option>', { 
                        value: i,
                        text : i 
                    }));
                i++;
            } 
            //$('#billdate').append(billd);
        }
        billDateVals();
        $(document).on('click', '.bill_details', function() {
            var groupid = this.id;
            var billHtml = '';
            $("#bill_details_list tbody").empty();
            $.ajax({
            type: "GET",
            url: '/bill_details/'+groupid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                    $.each(res, function(index, val)
                    {
                        billHtml += "<tr>";
                            billHtml += "<td>" + val.amount  + "</td>";
                            billHtml += "<td>" + val.type  + "</td>";
                            billHtml += "<td>" + val.datetime  + "</td>";
                            billHtml += "<td>" + val.username  + "</td>";
                            billHtml += "<td>" + val.comments  + "</td>";       
                        billHtml += "</tr>";
                    });
                    $("#bill_details_list tbody").append(billHtml);
            }
            });
        });

        $(document).on('click', '.edit_billing', function(e)
        {
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_billing/'+ id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var result = res[0];
                $("#billing_id").val(result.id);
                $("#groupid").val(result.groupid);
                $("#name").val(result.name);
                $("#main_balance").val(result.main_balance);
                $("#main_bal").val(result.main_bal);
                $("#c2c_balance").val(result.c2c_balance);
                $("#call_pulse_setup").val(result.call_pulse_setup);
                $("#c2c_pulse_setup").val(result.c2c_pulse_setup);
                $("#billingmode").val(result.billingmode);
                $("#billdate").val(result.billdate);
                $("#creditlimit").val(result.creditlimit);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $( '.billing_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("editBilling") }}', // This is the url we gave in the route
            data: $('.billing_form').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    $("#edit_billing").modal('hide');
                    setTimeout(function(){ location.reload() }, 3000);
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });
    });
</script>

@endsection
