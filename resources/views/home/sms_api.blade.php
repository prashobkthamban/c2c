@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1>Sms Apis</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Add Sms Api" href="#" data-toggle="modal" data-target="#add_sms_api" class="btn btn-primary"> Add Sms Api </a>
                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Mobile Parameter</th>
                                <th>User Parameter</th>
                                <th>Password Parameter</th>
                                <th>User Parameter</th>
                                <th>Message Parameter</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr id="row_{{ $row->id }}">
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->url }}</td>
                                <td>{{ $row->mobile_param_name }}</td>
                                <td>{{ $row->user_param_name }}</td>
                                <td>{{ $row->password_parm_name }}</td>
                                <td>{{ $row->sender_param_name }}</td>
                                <td>{{ $row->message_para_name }}</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#add_sms_api" class="text-success mr-2 edit_sms" id="{{$row->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    <a href="javascript:void(0)" onClick="deleteItem({{$row->id}}, 'sms_api_gateways')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a></td>
                            </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Mobile Parameter</th>
                                <th>User Parameter</th>
                                <th>Password Parameter</th>
                                <th>User Parameter</th>
                                <th>Message Parameter</th>
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
    <div class="modal fade" id="add_sms_api" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Announce</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    {!! Form::open(['class' => 'sms_api_form', 'method' => 'post']) !!} 
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                                {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'sms_id']) !!}
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Name *</label> 
                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Url *</label> 
                                {!! Form::text('url', null, ['class' => 'form-control', 'id' => 'url']) !!}
                            </div>
                        </div>               
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Mobile Parameter *</label> 
                                {!! Form::text('mobile_param_name', null, ['class' => 'form-control', 'id' => 'mob_para']) !!}
                            </div>
                        </div>                 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">User Parameter *</label> 
                                {!! Form::text('user_param_name', null, ['class' => 'form-control', 'id' => 'user_para']) !!}
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Password parameter *</label> 
                                {!! Form::text('password_parm_name', null, ['class' => 'form-control', 'id' => 'pwd_para']) !!}
                            </div>
                        </div>                 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Sender parameter *</label> 
                                {!! Form::text('sender_param_name', null, ['class' => 'form-control', 'id' => 'sender_para']) !!}
                            </div>
                        </div>               
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Message parameter *</label> 
                                {!! Form::text('message_para_name', null, ['class' => 'form-control', 'id' => 'msg_para']) !!}
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
    $( '.sms_api_form' ).on( 'submit', function(e) {
        e.preventDefault();
        var errors = ''; 
        $.ajax({
        type: "POST",
        url: '{{ URL::route("addSmsApi") }}', // This is the url we gave in the route
        data: $('.sms_api_form').serialize(),
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
                $("#add_sms_api").modal('hide');
                toastr.success(res.success); 
                setTimeout(function(){ location.reload() }, 500);               
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            toastr.error('Some errors are occured');
        }
        });
    });

    $('.edit_sms').on('click',function(e)
    {
        $("#modal-title").text('Edit Sms Api');
        var id = $(this).attr("id");
        $.ajax({
        type: "GET",
        url: '/get_data/sms_api_gateways/'+ id, // This is the url we gave in the route
        success: function(result) { 
            var res = result[0];
            $("#sms_id").val(res.id);
            $("#name").val(res.name);
            $("#url").val(res.url);
            $("#mob_para").val(res.mobile_param_name);
            $("#user_para").val(res.user_param_name);
            $("#pwd_para").val(res.password_parm_name);        
            $("#sender_para").val(res.sender_param_name);        
            $("#msg_para").val(res.message_para_name);        
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        }
        });
    });

});
</script>

@endsection