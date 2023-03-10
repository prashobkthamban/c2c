@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1> Operator </h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <?php $operatorCount = sizeof($operators);
                    $target = ($operatorCount < Auth::user()->load('accountdetails')->accountdetails['operator_no_logins']) ? '#operator_account' : '';
                    ?>
                    <div class="row">
                        <div class="col-md-6 float-left">
                            <a title="Add Operator" href="javascript:void(0)" data-toggle="modal" data-target="<?php echo $target; ?>" class="btn btn-primary add_account">Add Operator</a>
                        </div>
                        <div class="col-md-6 float-right">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Live Transfer ID</th>
                                    <th>Operator</th>
                                    <th>Phone</th>
                                    <th>LoginId</th>
                                    <th>Password</th>
                                    <th>Stickey Agent</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($operators as $operator)
                                <tr id="row_{{ $operator->id }}">
                                    <td>{{ $operator->livetrasferid }}</td>
                                    <td>{{$operator->opername}}</td>
                                    <td>{{$operator->phonenumber}}</td>
                                    <td>{{($operator->accounts != null) ? $operator->accounts->username : ''}}</td>
                                    <td>{{($operator->accounts != null) ? $operator->accounts->user_pwd : ''}}</td>
                                    <td><a href="#" data-toggle="modal" class="stickey_list" id="{{$operator->id}}" data-opername="{{$operator->opername}}" data-target="#stickey_modal"><i class="i-Administrator"></i></a></td>
                                    <td>{{$operator->oper_status}}</td>
                                    <td><a href="#" data-toggle="modal" data-target="#operator_account" class="text-success mr-2 edit_account" id="{{$operator->id}}">
                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                        </a>
                                        <a href="javascript:void(0)" onClick="deleteItem({{$operator->id}}, 'operatoraccount')" class="text-danger mr-2 deleteItem">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i></a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Live Transfer ID</th>
                                    <th>Operator</th>
                                    <th>Phone</th>
                                    <th>LoginId</th>
                                    <th>Password</th>
                                    <th>Stickey Agent</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="pull-right">{{ $operators->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="stickey_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Stickey Numbers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center" id="operator_name"></h6>
                    <table id="stickey_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Department Name</th>
                                <th>CallerId</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- add account modal -->
    <div class="modal fade" id="operator_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add Operator Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    {!! Form::open(['class' => 'add_account_form', 'method' => 'post', 'autocomplete' => 'off']) !!}
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                                <input type="hidden" name="id" id="account_id" />
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Phone number *</label>
                                    <input type="text" class="form-control phone_number" onpaste="return false;" placeholder="Phone number" name="phonenumber" id="phonenumber">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Operator *</label>
                                    <input type="text" class="form-control" placeholder="Operator" name="opername" id="opername">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Login Id *</label>
                                    <input type="text" class="form-control" placeholder="Login Id" name="username" id="username">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Password *</label>
                                    <input type="text" class="form-control" placeholder="Password" name="password" id="password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Status</label>
                                {!! Form::select('oper_status', array('Online' => 'Online', 'Offline' => 'Offline'), 'Online', array('class' => 'form-control', 'id' => 'oper_status')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Live Transfer no *</label>
                                    <input type="number" class="form-control" placeholder="Live Transfer no" name="livetrasferid" id="livetrasferid" value="{{$nextLiveTransferId}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Shift *</label>
                                {!! Form::select('shift_id',
                                shiftList()->prepend('Select Shift', ''), 0,array('class' => 'form-control', 'id' => 'shift_id')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"></div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Working Days</label></br>
                                <button type="button" id="Sun" class="btn btn-rounded m-1 week_days" onClick="selectDay('Sun');" title="Sunday">S</button>
                                <button type="button" id="Mon" class="btn btn-rounded m-1 week_days" onClick="selectDay('Mon');" title="Monday">M</button>
                                <button type="button" id="Tue" class="btn btn-rounded m-1 week_days" onClick="selectDay('Tue');" title="Tuesday">T</button>
                                <button type="button" id="Wed" class="btn btn-rounded m-1 week_days" onClick="selectDay('Wed');" title="Wednesday">W</button>
                                <button type="button" id="Thu" class="btn btn-rounded m-1 week_days"onClick="selectDay('Thu');" title="Thursday">T</button>
                                <button type="button" id="Fri" class="btn btn-rounded m-1 week_days" onClick="selectDay('Fri');" title="Friday">F</button>
                                <button type="button" id="Sat" class="btn btn-rounded m-1 week_days" onClick="selectDay('Sat');" title="Saturday">S</button>
                                <input type='hidden' id="working_days" name='working_days' value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Andriod App</label>
                                {!! Form::select('app_use', array('Yes' => 'Yes', 'No' => 'No'), 0,array('class' => 'form-control', 'id' => 'app_use')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">CDR Download</label>
                                {!! Form::select('edit', array('1' => 'Yes', '0' => 'No'), 0,array('class' => 'form-control', 'id' => 'edit')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Rec Download</label>
                                {!! Form::select('download', array('1' => 'Yes', '0' => 'No'), 0,array('class' => 'form-control', 'id' => 'download')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="picker1">Rec Play</label>
                                {!! Form::select('play', array('1' => 'Yes', '0' => 'No'), 0,array('class' => 'form-control', 'id' => 'play')) !!}
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
    var operatorLimits = "<?php echo Auth::user()->load('accountdetails')->accountdetails['operator_no_logins']; ?>";
    var days = [];
    function selectDay(day) {
        if(!$("#" + day).hasClass('btn-primary')) {
            $("#" + day).addClass('btn-primary');
            days.push(day);
        } else {
            $("#" + day).removeClass('btn-primary');
            var dayIndex = days.indexOf(day);
            days.splice(dayIndex, 1);
        }
        $("#working_days").val(days);
    }

    $(document).ready(function() {
        $( '.add_account_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = '';
          $.ajax({
            type: "POST",
            url: '{{ URL::route("AddOperatorAccount") }}',
            data: $('.add_account_form').serialize(),
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
                    $("#operator_account").modal('hide');
                    $(".add_account_form")[0].reset();
                    toastr.success(res.success);
                    // if (res.crm_access_error == 1) {
                    //     $('#crm_error').css('display','block');
                    // }
                    // else{
                    //  $('#crm_error').css('display','none');
                    // }
                    setTimeout(function(){ location.reload() }, 300);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.edit_account').on('click',function(e)
        {
            $("#modal-title").text('Edit Operator Account');
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_operator_account/'+ id, // This is the url we gave in the route
            success: function(result) {
                var res = result[0];
                $("#account_id").val(res.id);
                $("#phonenumber").val(res.phonenumber);
                $("#opername").val(res.opername);
                $("#username").val(res.username);
                $("#password").val(res.user_pwd);
                $("#oper_status").val(res.oper_status);
                $("#livetrasferid").val(res.livetrasferid);
                $("#shift_id").val(res.shift_id);
                $("#app_use").val(res.app_use);
                $("#edit").val(res.edit);
                $("#download").val(res.download);
                $("#play").val(res.play);
                var obj = JSON.parse(res.working_days);
                days = obj;
                $("#working_days").val(obj);
                $.each(obj, function(index, value)
                {
                    $("#" + value).addClass('btn-primary');
                });
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('.stickey_list').click(function() {
            $("#operator_name").text('Operator Name : ' + $(this).attr("data-opername"));

            $.ajax({
                url: '/stickey_list/'+this.id, // This is the url we gave in the route
                success: function(res){ // What to do if we succeed
                var response = JSON.stringify(res);
                console.log(res.length);
                var stickeyHTML = "";
                if(res.length > 0) {
                    $.each(res, function(idx, obj) {
                         console.log(obj);
                        stickeyHTML += "<tr id='stickey_row_" + obj.id + "'>";
                        stickeyHTML += "<td>" + obj.dept_name  + "</td>";
                        stickeyHTML += "<td>" + obj.caller  + "</td>";
                        stickeyHTML += "<td><a href='#' id='" + obj.id + "' class='delete_stickey text-danger mr-2'><i class='nav-icon i-Close-Window font-weight-bold'></i></a></td>";;
                        stickeyHTML += "</tr>";

                    });
                } else {
                    stickeyHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
                }
                $("#stickey_table tbody").html(stickeyHTML);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('.add_account').click(function() {
            $.ajax({
                url: "operator_count",
                type: 'GET',
                success: function (count) {
                    if(Number(count)  >= Number(operatorLimits) ) {
                        toastr.error('Limit exceeded');
                    } else {
                        $('.add_account').attr('data-target', '#operator_account');
                    }
                }
            });
            $("#modal-title").text('Add Operator Account');
            $(".week_days").removeClass('btn-primary');
            $(".add_account_form")[0].reset();
        });

        $(document).on("click", ".delete_stickey", function(event)
        {
            var action = confirm('Are you sure you want to delete this stickey number?');
            var stickeyId = this.id;
            if (action == true) {
                $.ajax({
                    url: "delete_stickey/"+stickeyId,
                    type: 'DELETE',
                    success: function (res) {

                        if(res.status == 1) {
                           $("#stickey_row_" + stickeyId).remove();
                           toastr.success('Stickey data delete successfully.')
                        }

                    }
                });
            }
        });

    });
</script>
@endsection

