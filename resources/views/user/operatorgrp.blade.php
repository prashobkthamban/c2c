@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <a href="{{route('OperatorGroup')}}"><h1> Operator Groups </h1></a>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive" id="operator_groups">
                        <table id="zero_configuration_table" class="table" style="width:25%">
                           <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>View</th>    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($operatordept as $listOne)
                                <tr>
                                    <td>{{$listOne->dept_name}}</td>
                                    <td><i class="i-Checked-User" onClick="showDetails('{{$listOne->id}}');return false;"></i></td>   
                                </tr>
                                @endforeach
                              
                            </tbody>
                        </table>
                    </div>
                    <div class="container" style="width: 100%;display: none;" id="operator_groups_details">
                        <div id="grid1" name="grid1" align="left" class="table-responsive" style="visibility: visible;width: 50%;display: inline-block;">
                            <table table="" id="department-basic" class="table">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="grid2" name="grid2" align="center" style="left: 450px; top: 50px; visibility: visible; width: 50%;display: inline-block;">
                            <table table="" id="settings" class="table">
                                <tbody>
                                </tbody>
                            </table></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end of row -->

    <div class="modal fade" id="add_number" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
             {!! Form::open(['class' => 'add_number_form', 'method' => 'post']) !!} 
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 form-group mb-3"> 
                            <input type="hidden" name="departmentid" id="num_departmentid" />
                        </div>

                        <div class="col-md-8 form-group mb-3">
                            <label for="firstName1">Phone Number
                             *</label> 
                             <input type="text" class="form-control" placeholder="Phone Number" name="phonenumber">
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-2 form-group mb-3"> 
                        </div>

                        <div class="col-md-8 form-group mb-3">
                            <label for="firstName1">Operator Name
                             *</label> 
                            <input type="text" class="form-control" placeholder="Operator Name" name="opname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group mb-3"> 
                        </div>

                        <div class="col-md-8 form-group mb-3">
                            <label for="firstName1">Priority
                             *</label> 
                            <input type="text" class="form-control" placeholder="Priority" name="priority" value="9">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group mb-3"> 
                        </div>

                        <div class="col-md-8 form-group mb-3">
                            <label for="firstName1">Live Transfer No
                             *</label> 
                            <input type="text" class="form-control" placeholder="Live Transfer No" name="livetransfer" value="99">
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

    <!-- add operator modal -->
    <div class="modal fade" id="add_operator" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Operator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 {!! Form::open(['class' => 'add_operator_form', 'method' => 'post']) !!} 
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                                <input type="hidden" name="departmentid" id="departmentid" />
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Operator
                                 *</label> 
                                 {!! Form::select('operatorid', ['Select Operator'],  null,array('class' => 'form-control', 'id' => 'operatorid')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Priority
                                 *</label> 
                                <input type="text" class="form-control" placeholder="Priority" name="priority" value="8">
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

    <!-- add operator modal -->
    <div class="modal fade" id="edit_settings" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 {!! Form::open(['class' => 'setting_form', 'method' => 'post']) !!} 
                <div class="modal-body">
                    <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                                <input type="hidden" name="oprid" id="oprid" />
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label>Call Distribution *</label> 
                                 {!! Form::select('opt_calltype', ['Call_Hunting' => 'Call_Hunting', 'Round_Robin' => 'Round_Robin'],  null,array('class' => 'form-control', 'id' => 'opt_calltype')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label>Sticky Agent *</label> 
                                {!! Form::select('sticky_agent', ['Yes' => 'Yes', 'No' => 'No'],  null,array('class' => 'form-control', 'id' => 'sticky_agent')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label>Call Recording *</label> 
                                {!! Form::select('recordcall', ['yes' => 'Yes', 'no' => 'No'],  null,array('class' => 'form-control', 'id' => 'recordcall')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label>Dial Time *</label> 
                                <input type="text" class="form-control" placeholder="In Seconds" name="dialtime" value="" id="dialtime">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label>Start Hour *</label> 
                                <input type="text" class="form-control" name="starttime" id="starttime" value="00:00:00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label>End Hour *</label> 
                                <input type="text" class="form-control" name="endtime" id="endtime" value="23:59:59">
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
<script>

    function showDetails(id) {
        $("#operator_groups").hide();
        $("#departmentid").val(id);
        $("#num_departmentid").val(id);
        $.ajax({
            url: '/operatorgrp_details/'+id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var depHTML = "";
                var settings = "";
                $("#operator_groups_details").attr("style","display:table;");
                if(res) {
                    depHTML += '<tr><td><h4>Department Name :</h4></td><td colspan="2" ><h4>'+res.details.dept_name+'</h4></td></tr>';
                    $("#operatorid option").remove();
                    $.each(res.operators, function(idx, obj) {                
                        $('#operatorid').append($('<option>', { 
                            value: obj.id,
                            text : obj.opername 
                        }));
                    });
               
                    if(res.account_det.length > 0) {
                        $.each(res.account_det, function(idx, obj) {
                        depHTML += '<tr><td><i class="fa fa-desktop" aria-hidden="true"></i> '+obj.opername+'('+obj.phonenumber+') priority: '+obj.priority+'</td><td></td><td><a href="?" onclick="xajax_delete('+obj.id+','+id+');return false;">Delete</a></td></tr>';
                        });
                        depHTML += '<tr><td colspan="3"> Total '+ res.account_det.length +' Operators in '+res.details.dept_name+'</td></tr><tr><td colspan="3" align="right"><a href="#" data-toggle="modal" class="btn btn-success btn-sm"  data-target="#add_operator">Add Operator</a><a href="#" data-toggle="modal" class="btn btn-success btn-sm"  data-target="#add_number">Add Number</a></td></tr>';
                    } else {
                        depHTML += '<tr><td><center>No Data !!!</center></td><td></td><td></td></tr><tr><td colspan="3" align="right"><a href="#" data-toggle="modal" class="btn btn-success btn-sm"  data-target="#add_operator">Add Operator</a><a href="#" data-toggle="modal" class="btn btn-success btn-sm"  data-target="#add_number">Add Number</a></td></tr>';
                    }
                    settings += '<tr><td colspan="2"> <h4>Settings</h4></td><td><a title="Edit Settings" href="#" data-toggle="modal" data-target="#edit_settings" onclick="xajax_edit(' + res.details.id + ');return false;" class="btn btn-primary"> Edit Settings </a></td></tr><tr><td>Call Distribution :  </td><td>'+res.details.opt_calltype+'</td><td>    <a href="?" onclick="xajax_edit(1,1);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Sticky Agent :  </td><td>'+res.details.sticky_agent+'</td><td><a href="?" onclick="xajax_edit(1,2);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Call Recording  :  </td><td>'+res.details.recordcall+'</td><td><a href="?" onclick="xajax_edit(1,3);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Dial Time :   </td><td>'+res.details.dialtime+'(In Seconds)</td><td><a href="?" onclick="xajax_edit(1,4);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Working Hour Start :   </td><td id="start_hour">'+res.details.starttime+'</td><td></td></tr><tr><td>Working Hour End :   </td><td>'+res.details.endtime+'</td><td></td></tr>';
                }
               
                $("#department-basic tbody").html(depHTML);
                $("#settings tbody").html(settings);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        });
    }

    function xajax_edit(opr_id) {
        $.ajax({
        type: "GET",
        url: '/operatorgrp_details/'+ opr_id,
        success: function(res) { 
            //var res = result[0];
            console.log(res);
            $("#oprid").val(res.details.id);
            $("#opt_calltype").val(res.details.opt_calltype);
            $("#sticky_agent").val(res.details.sticky_agent);
            $("#recordcall").val(res.details.recordcall);
            $("#dialtime").val(res.details.dialtime);        
            $("#starttime").val(res.details.starttime);        
            $("#endtime").val(res.details.endtime);              
        },
        error: function(jqXHR, textStatus, errorThrown) { }
        });   
    }

    $( '.setting_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("EditOperatorDept") }}', // This is the url we gave in the route
            data: $('.setting_form').serialize(),
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
                    $("#edit_settings").modal('hide');
                    $(".setting_form")[0].reset();
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 400);            
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
    });

    function xajax_delete(opid, dpid) {
        var action = confirm('Are you sure you want to delete this?');
        if (action == true) {
                $.ajax({
                    url: "delete_op_group/"+opid+"/"+dpid,
                    type: 'DELETE',
                    success: function (res) {
                        showDetails(res.dpid);
                        if(res.status == 1) {
                           toastr.success('Row delete successfully.')
                        }
                        
                    }
                });
            }
    }

    $(document).ready(function() {
        $( '.add_operator_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addOptassign") }}', // This is the url we gave in the route
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
                    var departmentid = $("#departmentid").val();
                    showDetails(departmentid);
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $( '.add_number_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addNumassign") }}', // This is the url we gave in the route
            data: $('.add_number_form').serialize(),
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
                    $("#add_number").modal('hide');
                    var departmentid = $("#departmentid").val();
                    $(".add_number_form")[0].reset();
                    showDetails(departmentid);
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

