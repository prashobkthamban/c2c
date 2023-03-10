@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1> Operator Department Manager </h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <!-- search bar -->
    @include('layouts.search_panel', ['request' => '{{request}}'])
    <!-- search bar ends -->

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_operator" class="btn btn-primary add_operator_dept"> Add New </a>
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered" id="oper_dept_list_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Ivrlevel</th>
                                    <th>Department</th>
                                    <th>Ivr option</th>
                                    <th>Ring type</th>
                                    <th>Office open</th>
                                    <th>Office close</th>
                                    <th>Sticky Agent</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($operatordept as $listOne)
                                <tr id="row_{{ $listOne->id }}">
                                    <td>{{$listOne->name}}</td>
                                    <td>{{$listOne->ivr_level_name}}</td>
                                    <td>{{$listOne->dept_name}}</td>
                                    <td>{{$listOne->ivr_option}}</td>
                                    <td>{{$listOne->opt_calltype}}</td>
                                    <td>{{$listOne->starttime}}</td>
                                    <td>{{$listOne->endtime}}</td>
                                    <td>{{$listOne->sticky_agent}}</td>
                                    <td>{{$listOne->adddate}}</td>
                                    <td><a href="#" data-toggle="modal" data-target="#add_operator" class="text-success mr-2 edit_operator" id="{{$listOne->id}}">
                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                        </a>
                                        <a href="javascript:void(0)" onClick="deleteItem({{$listOne->id}}, 'operatordepartment')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                        </a></td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Customer</th>
                                    <th>Ivrlevel</th>
                                    <th>Department</th>
                                    <th>Ivr option</th>
                                    <th>Ring type</th>
                                    <th>Office open</th>
                                    <th>Office close</th>
                                    <th>Sticky Agent</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add operator modal -->
    <div class="modal fade" id="add_operator" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Operator Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    {!! Form::open(['class' => 'add_operator_form', 'method' => 'post']) !!} 
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                                {!! Form::hidden('id', '', array('id' =>'operator_id')) !!}
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Coperate Account</label> 
                                    {!! Form::select('resellerid', getResellers()->prepend('Select Coperate', '0'), null,array('class' => 'form-control', 'id' => 'resellerid')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Customer *</label> 
                                    {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'customerId', 'onClick' => 'selectIvr()')) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">IVR Level *</label> 
                                {!! Form::select('ivrlevel_id', ['' => 'Select Ivr Level'], null,array('class' => 'form-control', 'id' => 'ivr_level')) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Department *</label> 
                                {!! Form::text('dept_name', null, ['class' => 'form-control', 'id' => 'dept_name']) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">IVR Option</label>
                                {!! Form::select('ivr_option', ['0' => 'Select Option', '1' => '1', '2' => '2','3' => '3', '4' => '4','5' => '5', '6' => '6','7' => '7', '8' => '8','9' => '9'], null,array('class' => 'form-control', 'id' => 'ivr_option')) !!} 
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Non-Operator department</label> 
                                {!! Form::select('complaint', ['no' => 'No', 'yes' => 'Yes'], null,array('class' => 'form-control', 'id' => 'complaint')) !!}
                            </div>
                        </div>  
                        @if(Auth::user()->usertype == 'admin')
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">DT</label> 
                                <label class="radio-inline"> {{ Form::radio('DT', 1, '', array('id' => 'dt_yes')) }} Yes</label>
                                    <label class="radio-inline">{{ Form::radio('DT', 0, true, array('id' => 'dt_no')) }} No</label>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">C2C</label> 
                                <label class="radio-inline"> {{ Form::radio('C2C', 1, '', array('id' => 'c2c_yes')) }} Yes</label>
                                    <label class="radio-inline">{{ Form::radio('C2C', 0, true, array('id' => 'c2c_no')) }} No</label>   
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Generate Ticketid</label> 
                                <label class="radio-inline"> {{ Form::radio('generateticket', 'Yes', '', array('id' => 'ticket_yes')) }} Yes</label>
                                    <label class="radio-inline">{{ Form::radio('generateticket', 'No', true, array('id' => 'ticket_no')) }} No</label>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Sms to caller</label> 
                                <label class="radio-inline"> {{ Form::radio('sms_to_caller', 'Yes', '', array('id' => 'sms_caller_yes')) }} Yes</label>
                                    <label class="radio-inline">{{ Form::radio('sms_to_caller', 'No', true, array('id' => 'sms_caller_no')) }} No</label>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Sms to operator</label> 
                                <label class="radio-inline"> {{ Form::radio('sms_to_operator', 'Yes', '', array('id' => 'sms_operator_yes')) }} Yes</label>
                                    <label class="radio-inline">{{ Form::radio('sms_to_operator', 'No', true, array('id' => 'sms_operator_no')) }} No</label>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">RING Type *</label> 
                                <label class="radio-inline"> {{ Form::radio('opt_calltype', 'Round_Robin', true, array('id' => 'round_robin')) }} Round Robin</label>
                                <label class="radio-inline">{{ Form::radio('opt_calltype', 'ring_all', '', array('id' => 'ring_all')) }} Ring All</label>   
                                <label class="radio-inline">{{ Form::radio('opt_calltype', 'Call_Hunting', '', array('id' => 'call_hunting')) }} Call Hunting</label>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Office Open *</label> 
                                {!! Form::text('starttime', null, ['class' => 'form-control', 'placeholder' => '00:00:00', 'id' => 'starttime']) !!} (24 hours time format)
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Office Close *</label> 
                                {!! Form::text('endtime', null, ['class' => 'form-control', 'placeholder' => '23:59:59', 'id' => 'endtime']) !!} (24 hours time format)
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">After office hours Call Transfer To *</label> 
                                {!! Form::select('call_transfer', ['aom' => 'After office message', 'number' => 'Number', 'voicemail' => 'Voicemail'], null,array('class' => 'form-control', 'id' => 'call_transfer')) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Default action</label> 
                                {!! Form::select('defaultaction', ['message' => 'Operators Busy Message', 'number' => 'Number', 'voicemail' => 'Voicemail'], null,array('class' => 'form-control', 'id' => 'defaultaction')) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Phone</label> 
                                {!! Form::text('phone_no', null, ['class' => 'form-control', 'id' => 'phone_no']) !!}
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Voicemail</label> 
                                {!! Form::text('email_id', null, ['class' => 'form-control', 'id' => 'email_id']) !!}
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">SMS to one number</label> 
                                {!! Form::select('default_sms', ['No' => 'No', 'Yes' => 'Yes'], null,array('class' => 'form-control', 'id' => 'default_sms')) !!}
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Default sms no</label> 
                                {!! Form::text('default_sms_no', null, ['class' => 'form-control', 'id' => 'default_sms_no']) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Sticky Agent</label> 
                                {!! Form::select('sticky_agent', ['No' => 'Disable', 'Yes' => 'Enable'], null,array('class' => 'form-control', 'id' => 'sticky_agent')) !!}
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Misscall Alert To</label> 
                                {!! Form::select('misscallalert', ['oper' => 'Operator', 'alertno' => ' Misscall Alert No', 'both' => 'Both'], null,array('class' => 'form-control', 'id' => 'misscallalert')) !!}
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
    $('#oper_dept_list_table').DataTable({
        "order": [
            [8, "desc"]
        ]
    });
    function selectIvr(ivrLevel) {
        var groupid = $("#customerId").val();
        $.ajax({
            type: "GET",
            url: '/get_ivr/'+ groupid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
               // $('#ivr_level').find('option').remove();
               $('#ivr_level').find('option').not(':first').remove();
                $.each(res, function (i, item) {
                        $('#ivr_level').append($('<option>', { 
                            value: i,
                            text : item 
                        }));
                        $("#ivr_level").val(ivrLevel);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        });
    }
    $(document).ready(function() {
     
        $( '.add_operator_form' ).on( 'submit', function(e) {
            e.preventDefault();
            //var noteHTML = "";
            var errors = ''; 
          $.ajax({
            type: "POST",
            url:  '{{ URL::route("addOperator") }}', // This is the url we gave in the route
            data: $('.add_operator_form').serialize(),
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
                    $("#add_operator").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 300);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $(document).on('click', '.edit_operator', function(e)
        {
            var id = $(this).attr("id");
            $("#modal-title").text('Edit Operator Department');
            $.ajax({
            type: "GET",
            url: '/get_operator/'+ id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                console.log(res);
               // $('#ivr_level').append(ivr_menu);
                $("#operator_id").val(res.id);
                $("#resellerid").val(res.resellerid);
                $("#customerId").val(res.groupid);
                selectIvr(res.ivrlevel_id);
                $("#dept_name").val(res.dept_name);
                $("#ivr_option").val(res.ivr_option);
                $("#complaint").val(res.complaint);
                $("#starttime").val(res.starttime);
                $("#endtime").val(res.endtime);
                $("#call_transfer").val(res.call_transfer);
                $("#defaultaction").val(res.defaultaction);
                $("#default_sms").val(res.default_sms);
                $("#phone_no").val(res.phone_no);
                $("#email_id").val(res.email_id);
                $("#default_sms_no").val(res.default_sms_no);
                $("#sticky_agent").val(res.sticky_agent);
                $("#misscallalert").val(res.misscallalert);
                if(res.DT == 1) {
                    $("#dt_yes").prop("checked", true);
                } else {
                    $("#dt_no").prop("checked", true);
                } 
                if(res.C2C == 1) {
                    $("#c2c_yes").prop("checked", true);
                } else {
                    $("#c2c_no").prop("checked", true);
                } 
                if(res.generateticket == 'Yes') {
                    $("#ticket_yes").prop("checked", true);
                } else {
                    $("#ticket_no").prop("checked", true);
                } 
                if(res.sms_to_caller == 'Yes') {
                    $("#sms_caller_yes").prop("checked", true);
                } else {
                    $("#sms_caller_no").prop("checked", true);
                }
                if(res.sms_to_operator == 'Yes') {
                    $("#sms_operator_yes").prop("checked", true);
                } else {
                    $("#sms_operator_no").prop("checked", true);
                }
                if(res.opt_calltype == "Round_Robin") {
                    $("#round_robin").prop("checked", true);
                } else if(res.opt_calltype == "ring_all") {
                    $("#ring_all").prop("checked", true);
                } else {
                    $("#call_hunting").prop("checked", true);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('.add_operator_dept').on('click',function(e) {
            $("#modal-title").text('Add Operator Department');
            $(".add_operator_form")[0].reset();
        });
    });
 </script>
@endsection

