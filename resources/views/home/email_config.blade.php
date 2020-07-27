@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1>Email Config</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Add Config" href="#" data-toggle="modal" data-target="#add_config" class="btn btn-primary"> Add Config </a>
                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Host</th>
                                <th>User</th>
                                <th>Password</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr id="row_{{ $row->id }}">
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->smtp_host }}</td>
                                <td>{{ $row->smtp_user }}</td>
                                <td>{{ $row->smtp_pass }}</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#add_config" class="text-success mr-2 edit_config" id="{{$row->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    <a href="javascript:void(0)" onClick="deleteItem({{$row->id}}, 'email_config')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a></td>
                            </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Host</th>
                                <th>User</th>
                                <th>Password</th>
                                <th>Action</th>
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

    <!-- add notifiaction modal -->
    <div class="modal fade" id="add_config" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Config</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    {!! Form::open(['class' => 'config_form', 'method' => 'post']) !!} 
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                                {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'config_id']) !!}
                            </div>

                            <div class="col-md-8 form-group mb-3">
                            <label for="firstName1">Group Admin *</label> 
                                {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'groupid')) !!}
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Host *</label> 
                                {!! Form::text('smtp_host', null, ['class' => 'form-control', 'id' => 'smtp_host']) !!}
                            </div>
                        </div>               
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">User *</label> 
                                {!! Form::text('smtp_user', null, ['class' => 'form-control', 'id' => 'smtp_user']) !!}
                            </div>
                        </div>                 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Password *</label> 
                                {!! Form::text('smtp_pass', null, ['class' => 'form-control', 'id' => 'smtp_pass']) !!}
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
    $( '.config_form' ).on( 'submit', function(e) {
        e.preventDefault();
        var errors = ''; 
        $.ajax({
        type: "POST",
        url: '{{ URL::route("addConfig") }}', 
        data: $('.config_form').serialize(),
        success: function(res){ 
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
                $("#add_config").modal('hide');
                toastr.success(res.success); 
                setTimeout(function(){ location.reload() }, 500);               
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) { 
            toastr.error('Some errors are occured');
        }
        });
    });

    $('.edit_config').on('click',function(e)
    {
        $("#modal-title").text('Edit Config');
        var id = $(this).attr("id");
        $.ajax({
        type: "GET",
        url: '/get_data/email_config/'+ id, // This is the url we gave in the route
        success: function(result) { 
            var res = result[0];
            $("#config_id").val(res.id);
            $("#groupid").val(res.groupid);
            $("#smtp_host").val(res.smtp_host);
            $("#smtp_user").val(res.smtp_user);
            $("#smtp_pass").val(res.smtp_pass);       
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        }
        });
    });

});
</script>

@endsection