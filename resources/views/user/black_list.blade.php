@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1> Blacklist </h1>
    </div>
        <div class="separator-breadcrumb border-top"></div>
           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" data-toggle="modal" data-target="#blacklist" href="#" class="btn btn-primary"> Add Blacklist </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Phone</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                        @foreach($blacklists as $blacklist)
                                        <tr id="row_{{ $blacklist->id }}">
                                            <td>{{$blacklist->phone_number}}</td>
                                            <td>{{$blacklist->reason}}</td>                                           
                                            <td><a href="javascript:void(0)" onClick="deleteItem({{$blacklist->id}}, 'blacklist')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <th>Phone</th>
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tfoot>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

 <!-- add blacklist modal -->
    <div class="modal fade" id="blacklist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Blacklist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    {!! Form::open(['class' => 'add_blacklist_form', 'method' => 'post', 'autocomplete' => 'off']) !!} 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 form-group mb-3"> 
                            <input type="hidden" name="id" id="account_id" />
                        </div>
                        <div class="col-md-8 form-group mb-3">
                            <label for="picker1">Phone number *</label>
                            <input type="number" class="form-control phone_number" placeholder="Phone number" name="phone_number" id="phone_number" onpaste="return false;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group mb-3"> 
                        </div>
                        <div class="col-md-8 form-group mb-3">
                            <label for="picker1">Reason *</label>
                            <textarea rows="8" cols="20" class="form-control" placeholder="Reason" name="reason"></textarea>
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
        $( '.add_blacklist_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addBlackList") }}',
            data: $('.add_blacklist_form').serialize(),
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
                    $("#blacklist").modal('hide');
                    $(".add_blacklist_form")[0].reset();
                    toastr.success(res.success);
                    setTimeout(function(){ location.reload() }, 300);               
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

