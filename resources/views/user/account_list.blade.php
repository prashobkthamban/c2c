@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Login Manager</h1>
  </div>

  <div class="separator-breadcrumb border-top"></div>

    <!-- search bar -->
    @include('layouts.search_panel', ['request' => '{{request}}'])
    <!-- search bar ends -->

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Add New Login" data-toggle="modal" data-target="#login_manager" href="#" class="btn btn-primary login_manager" style="margin: 0px 0px 15px 15px;"> Add New Login </a>
                    <div class="table-responsive">
                        <table class="display table table-striped table-bordered zero-configuration-table" style="width:100%">
                           <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Password</th>
                                    <th>User Type</th>
                                    <th>Customer</th>
                                    <th>Corporate</th>
                                    <th>Phone Number</th>
                                    <th>Add Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach($accounts as $account)
                                <tr>
                                    <td>{{$account->username}}</td>
                                    <td>{{$account->user_pwd}}</td>
                                    <td>{{$account->usertype}}</td>
                                    <td>{{$account->customerName}}</td>
                                    <td>{{$account->resellername}}</td>
                                    <td>{{$account->phone_number}}</td>
                                    <td>{{ date('d-m-Y', strtotime($account->adddate)) }}</td>
                                    <td><a href="#" data-toggle="modal" data-target="#login_manager" class="text-success mr-2 edit_login" id="{{$account->id}}">
                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                        </a><a href="{{ route('deleteUser', $account->id) }}" onclick="return confirm('You want to delete this user?')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                        </a></td>
                                </tr>
                                @endforeach
                              
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>User Name</th>
                                    <th>Password</th>
                                    <th>User Type</th>
                                    <th>Customer</th>
                                    <th>Corporate</th>
                                    <th>Phone Number</th>
                                    <th>Add Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add New Login Manager -->
            <div class="modal fade" id="login_manager" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="login_title">Add New Login</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['id' => 'add_login']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        <input type="hidden" name="id" id="account_id">
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">User Name *</label> 
                                        <input type="text" class="form-control" placeholder="User Name" name="username">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Password *</label> 
                                        <input type="text" class="form-control" placeholder="Password" name="password" id="password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">User Type *</label> 
                                        {!! Form::select('usertype', array('' => 'Select UserType', 'admin' => 'Admin', 'reseller' => 'Coperate Admin', 'groupadmin' => 'Group Admin'), null,array('class' => 'form-control', 'id' => 'usertype')) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Coperate Account</label>
                                        {!! Form::select('resellerid', $coperate, null,array('class' => 'form-control', 'id' => 'resellerid')) !!}  
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Customer</label> 
                                        {!! Form::select('groupid', $customer, null,array('class' => 'form-control', 'id' => 'groupid')) !!} 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Phone Number</label> 
                                        <input type="text" class="form-control" placeholder="Phone Number" name="phone_number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Email</label> 
                                        <input type="text" class="form-control" placeholder="Email" name="email">
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

        $( '#add_login' ).on( 'submit', function(e) {
            e.preventDefault();
            var noteHTML = "";
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addAccount") }}', // This is the url we gave in the route
            data: $('#add_login').serialize(),
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
                    $("#login_manager").modal('hide');
                    toastr.success(res.success);  
                    setTimeout(function(){ location.reload() }, 300);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        var selectedGroupId = '';
        $("#resellerid, #usertype").on('change',function(){
            var resellerid = $("#resellerid").val();
            var usertype = $("#usertype").val();

          $.ajax({
            url: '/get_customer/'+usertype+'/'+resellerid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                $('#groupid').find('option').not(':first').remove();
                $.each(res, function (i, item) {
                    $('#groupid').append($('<option>', { 
                        value: i,
                        text : item 
                    }));
                    console.log('1');
                });
                setTimeout(function() {
                    if(selectedGroupId) {
                        console.log('2');
                        $("#groupid").val(selectedGroupId);
                        console.log($("#groupid").val());
                        console.log('3');
                        selectedGroupId = '';
                    }
                }, 300);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $("#usertype").on('change',function(){
            if ($(this).val() == 'admin') {
                $("#resellerid").prop('disabled', true);
                $("#groupid").prop('disabled', true);
                $("#resellerid").val(0);
                $("#groupid").val('');
            } else if ($(this).val() == 'reseller') {
                $("#resellerid").prop('disabled', false);
                $("#groupid").prop('disabled', true);
                $("#resellerid").val(0);
                $("#groupid").val('');
            } else {
                $("#resellerid").prop('disabled', false);
                $("#groupid").prop('disabled', false);
                $("#resellerid").val(0).trigger('change');
            }
        });

        $("#groupid").on('change',function(){
            var groupid = $(this).val();
            selectedGroupId = groupid;
            $.ajax({
                url: '/get_customer_reseller_id/'+groupid, // This is the url we gave in the route
                success: function(res){ // What to do if we succeed
                    $("#resellerid").val(res.resellerid).trigger('change');
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });
        });

        $('.edit_login').click(function() {  
          $.ajax({
            url: '/edit_account/'+this.id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                if(res) {
                    $("input[name=email]").val(res.email);
                    $("input[name=phone_number]").val(res.phone_number);
                    if(res.groupid) {
                        selectedGroupId = res.groupid;
                        $("#groupid").val(res.groupid).trigger('change');
                    } else {
                        $("#groupid").val(res.groupid);
                    }
                    $("#resellerid").val(res.resellerid);
                    $("#account_id").val(res.id);
                    $("#usertype").val(res.usertype);
                    $("#password").val(res.user_pwd);
                    $("input[name=username]").val(res.username);
                    $("#login_title").text('Edit Login');
                    $("#login_manager").modal('show');

                    if (res.usertype == 'admin') {
                        $("#resellerid").prop('disabled', true);
                        $("#groupid").prop('disabled', true);
                    } else if (res.usertype == 'reseller') {
                        $("#resellerid").prop('disabled', false);
                        $("#groupid").prop('disabled', true);
                    } else {
                        $("#resellerid").prop('disabled', false);
                        $("#groupid").prop('disabled', false);
                    }
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });
        
        $('.login_manager').click(function() {
            $("#add_login").trigger("reset");
            $("#login_title").text('Add New Login');
        });
    });
</script>

@endsection

