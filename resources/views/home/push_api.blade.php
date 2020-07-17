@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1>Push API Configuration</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Add Push Api" href="#" data-toggle="modal" data-target="#add_push_api" class="btn btn-primary"> Add Push Api </a>
                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Push DATA Type</th>
                                <th>API Type</th>
                                <th>API Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr id="row_{{ $row->id }}">
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->type }}</td>
                                <td>{{ $row->apitype }}</td>
                                <td>{{ $row->api }}</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#add_push_api" class="text-success mr-2 edit_push_api" id="{{$row->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    <a href="javascript:void(0)" onClick="deleteItem({{$row->id}}, 'pushapi')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a></td>
                            </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Customer</th>
                                <th>Push DATA Type</th>
                                <th>API Type</th>
                                <th>API Details</th>
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
    <div class="modal fade" id="add_push_api" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Announce</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    {!! Form::open(['class' => 'push_api_form', 'method' => 'post']) !!} 
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                                {!! Form::hidden('id', null, ['class' => 'form-control', 'id' => 'push_api_id']) !!}
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Customer</label> 
                                {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'customerId')) !!}
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Data Push Type *</label> 
                                {!! Form::select('type', ['POST' => 'POST', 'GET' => 'GET'], null,array('class' => 'form-control', 'id' => 'type')) !!}
                            </div>
                        </div>               
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Api Type *</label> 
                                {!! Form::select('apitype', ['CallLog' => 'Call LOG', 'AgentPopup' => 'Agent Popup', 'CallRouting' => 'Call Routing'], null,array('class' => 'form-control', 'id' => 'apitype')) !!}
                            </div>
                        </div>                 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <select id="api_param" name="param" multiple="multiple" onchange="copyval('api_param', 'api')" size="6">
                                    <option value="{CUSTOMERNAME}">ACCOUNTNAME</option>
                                    <option value="{OPERATORNAME}">OPERATORNAME</option>
                                    <option value="{DEPARTMENTNAME}">DEPARTMENTNAME</option>
                                    <option value="{OPERATORPHONENUMBER}">OPERATOR PHONENUMBER</option>
                                    <option value="{CUSTOMERDID}">CUSTOMER DID</option>
                                    <option value="{CALLERID}">CALLER ID</option>
                                    <option value="{DATETIME}">DATETIME</option>
                                    <option value="{CALLUNIQUEID}">CALLUNIQUEID</option>
                                    <option value="{RECLOCATION}">Recordings Location</option>
                                    <option value="{DURATION}">Call Duration</option>
                                </select>
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Api Url *</label> 
                                <textarea id="api" name="api" rows="8" cols="32" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <select id="post_param" name="param" multiple="multiple" onchange="copyval('post_param', 'postvalues')" size="6">
                                    <option value="{CUSTOMERNAME}">ACCOUNTNAME</option>
                                    <option value="{OPERATORNAME}">OPERATORNAME</option>
                                    <option value="{DEPARTMENTNAME}">DEPARTMENTNAME</option>
                                    <option value="{OPERATORPHONENUMBER}">OPERATOR PHONENUMBER</option>
                                    <option value="{CUSTOMERDID}">CUSTOMER DID</option>
                                    <option value="{CALLERID}">CALLER ID</option>
                                    <option value="{DATETIME}">DATETIME</option>
                                    <option value="{CALLUNIQUEID}">CALLUNIQUEID</option>
                                    <option value="{RECLOCATION}">Recordings Location</option>
                                    <option value="{DURATION}">Call Duration</option>
                                </select>
                            </div>
                        </div>                  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Post Values *</label> 
                                {!! Form::textarea('postvalues', null, ['class' => 'form-control', 'id' => 'postvalues', 'rows' => 8, 'cols' => 32]) !!}
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
    $( '.push_api_form' ).on( 'submit', function(e) {
        e.preventDefault();
        var errors = ''; 
        $.ajax({
        type: "POST",
        url: '{{ URL::route("addPushApi") }}', // This is the url we gave in the route
        data: $('.push_api_form').serialize(),
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
                $("#add_push_api").modal('hide');
                toastr.success(res.success); 
                setTimeout(function(){ location.reload() }, 300);               
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            toastr.error('Some errors are occured');
        }
        });
    });

    $('.edit_push_api').on('click',function(e)
    {
        $("#modal-title").text('Edit Push Api');
        var id = $(this).attr("id");
        $.ajax({
        type: "GET",
        url: '/get_data/pushapi/'+ id, // This is the url we gave in the route
        success: function(result) { 
            var res = result[0];
            $("#push_api_id").val(res.id);
            $("#customerId").val(res.groupid);
            $("#type").val(res.type);
            $("#apitype").val(res.apitype);
            $("#api").val(res.api);
            $("#postvalues").val(res.postvalues);       
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        }
        });
    });

});
</script>

@endsection