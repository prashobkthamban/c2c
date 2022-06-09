@extends('layouts.master')
@section('page-css')
<style>
.datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>PRI Gateway</h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" class="btn btn-primary add_gateway" data-toggle="modal" data-target="#add_pri"> Add Pri Gateway </a>
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Provider</th>
                                        <th>Channel</th>
                                        <th>Pluse Rate</th>
                                        <th>Billing Date</th>
                                        <th>Used units</th>
                                        <th>Actions</th>
                                        <th>Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr id="row_{{ $row->id }}">
                                        <td>{{ $row->Gprovider }}</td>
                                        <td>{{ $row->Gchannel }}</td>
                                        <td>{{ $row->pluse_rate }}</td>
                                        <td>{{ $row->billingdate }}</td>
                                        <td>{{ $row->used_units }}</td>
                                        <td>
                                            <a href="" class="text-success mr-2 edit_pri" data-toggle="modal" data-target="#add_pri" id="{{$row->id}}">
                                                <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                            </a>
                                            <a href="javascript:void(0)" onClick="deleteItem({{$row->id}}, 'prigateway')" class="text-danger mr-2 deleteItem">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i></a>
                                            </td>
                                            <td><a href="#" data-toggle="modal" class="log_list" id="{{$row->id}}" data-target="#log_modal">
                                                    Details
                                                </a></td>
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Provider</th>
                                        <th>Channel</th>
                                        <th>Pluse Rate</th>
                                        <th>Billing Date</th>
                                        <th>Used units</th>
                                        <th>Actions</th>
                                        <th>Details</th>
                                    </tr>

                                    </tfoot>

                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->

            <!-- list modal -->
            <div class="modal fade" id="log_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pri Bill Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table id="pri_log_table" class="display table table-striped table-bordered" style="width:100%">
                               <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add_pri" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title">Add Pri Gateway</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                            {!! Form::open(['class' => 'add_pri_form', 'method' => 'post', 'autocomplete' => 'off']) !!}
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                        <input type="hidden" name="id" id="pri_id" />
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                        <label for="picker1">Provider *</label>
                                            <input type="text" class="form-control" placeholder="Provider" name="Gprovider" id="Gprovider">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                        <label for="picker1">Channel Name *</label>
                                            <input type="text" class="form-control" placeholder="Channel Name" name="Gchannel" id="Gchannel">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                        <label for="picker1">Pulse Rate</label>
                                            <input type="text" class="form-control" placeholder="Pulse Rate" name="pluse_rate" id="pluse_rate">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                        <label for="billingdate">Billing Date</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" name="billingdate" id="billingdate">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                    <label for="picker1">Used Units</label>
                                        <input type="text" class="form-control" placeholder="Used Units" name="used_units" id="used_units">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                    <label for="picker1">Dial Prefix</label>
                                        <input type="text" class="form-control" placeholder="Dial Prefix" name="dial_prefix" id="dial_prefix">
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary save_changes">Save changes</button>
                        </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
$(document).ready(function() {
    $('.log_list').click(function() {  
      $.ajax({
        url: '/pri_log/'+this.id, // This is the url we gave in the route
        success: function(res){ // What to do if we succeed
            var response = JSON.stringify(res);
            var logHTML = "";
            if(res.length > 0) {
                $.each(res, function(idx, obj) {
                     console.log(obj);
                    logHTML += "<tr>";
                    logHTML += "<td>" + obj.unit_used  + "</td>";
                    logHTML += "<td>" + obj.bill_cycle  + "</td>";
                    logHTML += "</tr>";
                }); 
            } else {
                logHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
            } 
            $("#pri_log_table tbody").html(logHTML);
        },
        error: function(jqXHR, textStatus, errorThrown) { 
        }
      });
    });
    
    $('.add_gateway').click(function() {
        $("#modal-title").text('Add Pri Gateway');
        $(".add_pri_form")[0].reset();
    });

    $( '.add_pri_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = '';
          $.ajax({
            type: "POST",
            url: '{{ URL::route("AddGateway") }}',
            data: $('.add_pri_form').serialize(),
            success: function(res){ // What to do if we succeed
                console.log(res);
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
                    $(this).prop('disabled',true);
                    $("#add_pri").modal('hide');
                    $(".add_pri_form")[0].reset();
                    toastr.success(res.success);
                    setTimeout(function(){ location.reload() }, 300);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
    });

    $('.edit_pri').on('click',function(e)
        {
            $("#modal-title").text('Edit Pri Gateway');
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_pri_gateway/'+ id, // This is the url we gave in the route
            success: function(result) {
                var res = result[0];
                $("#pri_id").val(res.id);
                $("#Gprovider").val(res.Gprovider);
                $("#pluse_rate").val(res.pluse_rate);
                $("#used_units").val(res.used_units);
                $("#billingdate").val(res.billingdate);
                $("#Gchannel").val(res.Gchannel);
                $("#dial_prefix").val(res.dial_prefix);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });
});
</script>

@endsection
