@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1> Did </h1>

</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div id="filter-panel" class="col-lg-12 col-md-12 filter-panel collapse {{count($requests) > 0 ? 'show' : ''}}">
        <div class="card mb-2">
            <div class="card-body">
                <div>
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="cdr_filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            <div class="col-md-4" id="customer_div">
                                <label class="filter-col" for="pref-perpage">Customers</label>
                                <select name="customer" class="form-control" id="customer_id">
                                    <option value="">All</option>
                                    @if(!empty($customers))
                                    @foreach($customers as $customer )
                                    <option value="{{$customer->id}}" @if(isset($requests['customer']) && $customer->id == $requests['customer']) selected @endif>{{$customer->name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 24px;">
                            <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                            <a href="{{url('did_list')}}" class="btn btn-outline-secondary" name="btn">Clear</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <a title="Compact Sidebar" href="{{route('addDid')}}" class="btn btn-primary"> Add Did </a>
                <div class="table-responsive">
                    <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Mobile No</th>
                                <th>Did No</th>
                                <th>PRI gateway</th>
                                <th>Assigned to</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($dids as $did)
                            <tr>
                                <td>{{$did->rdins}}</td>
                                <td>{{$did->did}}</td>
                                <td>{{$did->Gprovider}}</td>
                                <td>{{$did->name}}</td>
                                <td>
                                    <a href="{{ route('editDid', $did->id) }}" class="text-success mr-2" title="Edit Did">
                                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    </a>
                                    <a href="{{ route('deleteDid', $did->id) }}" onclick="return confirm('Are you sure you want to delete this Did?')" class="text-danger mr-2" title="Delete Did">
                                        <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a>
                                    <a href="#" data-toggle="modal" class="did_list text-warning mr-2" id="{{$did->id}}" data-target="#list_modal" title="Extra Did List">
                                        <i class="nav-icon i-File font-weight-bold"></i>
                                    </a>
                                    <a href="#" data-toggle="modal" class="did_list_form text-info mr-2" id="{{$did->id}}" data-groupid="{{$did->assignedto}}" data-target="#extra_did_modal" title="Add Extra Did">
                                        <i class="nav-icon i-Add font-weight-bold"></i>
                                    </a>

                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Mobile No</th>
                                <th>Did No</th>
                                <th>PRI gateway</th>
                                <th>Assigned to</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    {{ $dids->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- list modal -->
<div class="modal fade" id="list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Extra Did List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="extra_did_table" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Did No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> -->
        </div>
    </div>
</div>

<!-- extra did modal -->
<div class="modal fade" id="extra_did_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Extra Did</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'add_extra_did', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                        <input type="hidden" name="did_id" id="did_id">
                        <input type="hidden" name="groupid" id="groupid">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">DID Number *</label>
                        <input type="number" class="form-control phone_number" placeholder="Did Number" name="did_no">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">DID Name *</label>
                        <input type="text" class="form-control" placeholder="Did Name" name="did_name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Outgoing Callerid</label>
                        <input type="number" class="form-control phone_number" placeholder="Outgoing Callerid" name="set_pri_callerid">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Outgoing PRI</label>
                        {!! Form::select('pri_id', $prigateway, null,array('class' => 'form-control')) !!}
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
<!-- end of row -->

<div class="customizer" title="Search" style="top:73px">
    <a href="#" data-toggle="collapse" data-target="#filter-panel">
        <div class="handle collapsed">
            <i class="i-Search-People"></i>
        </div>
    </a>
</div>
@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.did_list').click(function() {
            // alert(this.id);  
            $.ajax({
                url: '/extra_did/' + this.id, // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    var response = JSON.stringify(res);
                    console.log(res.length);
                    var didHTML = "";
                    if (res.length > 0) {
                        $.each(res, function(idx, obj) {
                            didHTML += "<tr>";
                            didHTML += "<td>" + obj.did_no + "</td>";
                            didHTML += "<td>" + obj.did_name + "</td>";
                            didHTML += "<td><a href='#' id='" + obj.id + "' class='delete_extra_did text-danger mr-2'><i class='nav-icon i-Close-Window font-weight-bold'></i></a></td>";
                            didHTML += "</tr>";

                        });
                    } else {
                        didHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
                    }
                    $("#extra_did_table tbody").html(didHTML);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });
        });

        $('.add_extra_did').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "post",
                url: '{{ URL::route("addExtraDid") }}', // This is the url we gave in the route
                data: $('.add_extra_did').serialize(),
                success: function(res) { // What to do if we succeed
                    if (res.error) {
                        $.each(res.error, function(index, value) {
                            if (value.length != 0) {
                                errors += value[0];
                                errors += "</br>";
                            }
                        });
                        toastr.error(errors);
                    } else {
                        $("#extra_did_modal").modal('hide');
                        toastr.success(res.success);
                        setTimeout(function() {
                            location.reload()
                        }, 500);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        $('.did_list_form').click(function() {
            $("#did_id").val(this.id);
            $("#groupid").val($(this).attr("data-groupid"));
        });


        $(document).on("click", ".delete_extra_did", function(event) {
            var action = confirm('Are you sure you want to delete this extra did data?');

            if (action == true) {
                $.ajax({
                    url: "delete_extra_did/" + this.id,
                    type: 'DELETE',
                    success: function(res) {

                        if (res.status == 1) {
                            $("#list_modal").modal('hide');
                            toastr.success('Extra did data delete successfully.')
                        }

                    }
                });
            }
        });

    });
</script>

@endsection