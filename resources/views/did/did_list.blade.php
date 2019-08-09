@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Did </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


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
                                            <td>{{$did->outgoing_callerid}}</td>
                                            <td>{{$did->did}}</td>
                                            <td>{{$did->Gprovider}}</td>
                                            <td>{{$did->name}}</td>
                                            <td><a href="{{ route('editDid', $did->id) }}" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteDid', $did->id) }}" onclick="return confirm('Are you sure you want to delete this Did?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>
                                                <a href="#" data-toggle="modal" class="did_list" id="{{$did->id}}" data-target="#list_modal">
                                                    List
                                                </a>
                                                <a href="#" data-toggle="modal" class="did_list_form" id="{{$did->id}}" data-target="#extra_did_modal">
                                                     Extra
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
                         {!! Form::open(['action' => 'DidController@add_extra_did', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        <input type="hidden" name="did_id" id="did_id"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">DID Number*</label> 
                                        <input type="text" class="form-control" placeholder="Did Number" name="did_no">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">DID Name</label> 
                                        <input type="text" class="form-control" placeholder="Did Name" name="did_name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">  
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Outgoing Callerid</label>
                                        <input type="text" class="form-control" placeholder="Outgoing Callerid" name="set_pri_callerid">
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


@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.did_list').click(function() {
          // alert(this.id);  
          $.ajax({
            url: '/extra_did/'+this.id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var response = JSON.stringify(res);
                console.log(res.length);
                var didHTML = "";
                if(res.length > 0) {
                    $.each(res, function(idx, obj) {
                         console.log(obj);
                        didHTML += "<tr>";
                        didHTML += "<td>" + obj.did_no  + "</td>";
                        didHTML += "<td>" + obj.did_name  + "</td>";
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

        $('.did_list_form').click(function() {
            $("#did_id").val(this.id);
        });


        $(document).on("click", ".delete_extra_did", function(event)
        {
            var action = confirm('Are you sure you want to delete this extra did data?');

            if (action == true) {
                $.ajax({
                    url: "delete_extra_did/"+this.id,
                    type: 'DELETE',
                    success: function (res) {

                        if(res.status == 1) {
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
