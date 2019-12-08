@extends('layouts.master')
@section('page-css')
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
                            <a title="Compact Sidebar" href="#" class="btn btn-primary"> Add Pri Gateway </a>
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
                                    <tr>
                                        <td>{{ $row->Gprovider }}</td>
                                        <td>{{ $row->Gchannel }}</td>
                                        <td>{{ $row->pluse_rate }}</td>
                                        <td>{{ $row->billingdate }}</td>
                                        <td>{{ $row->used_units }}</td>
                                        <td>
                                            <a href="" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="" onclick="return confirm('Are you sure you want to delete this Did?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>
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
});
</script>

@endsection
