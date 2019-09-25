@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Login Manager</h1>
  </div>

  <div class="separator-breadcrumb border-top"></div>


    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Add New Login" data-toggle="modal" data-target="#login_manager" href="#" class="btn btn-primary"> Add New Login </a>
                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                           <thead>
                                <tr>
                                    <th>User Name</th>
                                    <!-- <th>Password</th> -->
                                    <th>User Type</th>
                                    <th>Corporate</th>
                                    <th>Customer</th>
                                    <th>Phone Number</th>
                                    <th>Add Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach($accounts as $account)
                                <tr>
                                    <td>{{$account->username}}</td>
                                    <!-- <td>{{$account->password}}</td> -->
                                    <td>{{$account->usertype}}</td>
                                    <td>{{$account->resellername}}</td>
                                    <td></td>
                                    <td>{{$account->phone_number}}</td>
                                    <td>{{ date('d-m-Y', strtotime($account->adddate)) }}</td>
                                    <td><a href="#" class="text-success mr-2 edit_login" id="{{$account->id}}">
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
                                    <!-- <th>Password</th> -->
                                    <th>User Type</th>
                                    <th>Corporate</th>
                                    <th>Customer</th>
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
                                        <!-- <input type="hidden" name="did_id" id="did_id">  -->
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">User Name</label> 
                                        <input type="text" class="form-control" placeholder="User Name" name="username">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Password</label> 
                                        <input type="text" class="form-control" placeholder="Password" name="password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">User Type</label> 
                                        {!! Form::select('usertype', array('' => 'Select UserType', 'reseller' => 'Coperate Admin', 'groupadmin' => 'Group Admin'), null,array('class' => 'form-control', 'id' => 'usertype')) !!}
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
                                        {!! Form::select('groupid', ['' => 'Select Customer'], null,array('class' => 'form-control', 'id' => 'groupid')) !!} 
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
            //var cdrid = $('input[name="cdrid"]').val();
            var noteHTML = "";
            // console.log($('input[name="cdrid"]').val());
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '/add_account/', // This is the url we gave in the route
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
                    //console.log(moment(new Date()).format('YYYY-MM-DD hh:mm:ss'));
                    // noteHTML += "<tr>";
                    // noteHTML += "<td>" + res.result.operator  + "</td>";
                    // noteHTML += "<td>" + moment(res.result.datetime.date).format('YYYY-MM-DD hh:mm:ss')  + "</td>";
                    // noteHTML += "<td>" + res.result.note  + "</td>";
                    // noteHTML += "<td><a href='#' class='text-danger mr-2'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                    // noteHTML += "</tr>";
                    // $("#comments_table tbody").append(noteHTML);
                    // $("#notes_"+cdrid).removeClass('d-none');
                    // $("#add_note_"+cdrid).addClass('d-none');
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

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
                });
                
                
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('.edit_login').click(function() {
           //alert(this.id);  
          $.ajax({
            url: '/edit_account/'+this.id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                //var response = JSON.stringify(res);
                console.log(res);
                console.log(res.username);
                if(res) {
                    $("input[name=email]").val(res.email);
                    $("input[name=phone_number]").val(res.phone_number);
                    $("input[name=groupid]").val(res.groupid);
                    $("input[name=resellerid]").val(res.resellerid);
                    $("input[name=usertype]").val(res.usertype);
                    $("input[name=username]").val(res.username);
                    $("#login_title").text('Edit Login');
                    $("#login_manager").modal('show');
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });
    });
</script>

@endsection

