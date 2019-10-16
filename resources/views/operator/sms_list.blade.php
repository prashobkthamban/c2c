@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Configure SMS </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_sms" class="btn btn-primary"> Add New </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Type</th>
                                            <th>Sms To</th>
                                            <th>Content</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($sms as $listOne)
                                        <tr>
                                            <td>{{$listOne->name}}</td>
                                            <td>{{$listOne->type}}</td>
                                            <td>{{$listOne->sms_to}}</td>
                                            <td>{{$listOne->content}}</td> 
                                            <td><a href="#" data-toggle="modal" data-target="#add_sms" class="text-success mr-2 edit_sms" id="{{$listOne->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteSms', $listOne->id) }}" onclick="return confirm('Are you sure want to delete this sms ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Type</th>
                                            <th>Sms To</th>
                                            <th>Content</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $sms->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- add sms modal -->
            <div class="modal fade" id="add_sms" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Record</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'add_sms_form', 'method' => 'post']) !!} 
                        <div class="modal-body">
                            
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                         {!! Form::hidden('id', '', array('id' =>'sms_id')) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Customer *</label> 
                                         {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'groupid')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Type*</label> 
                                        {!! Form::select('type', ['MISSED' => 'MISSED', 'ANSWERED' => 'ANSWERED'], null,array('class' => 'form-control', 'id' => 'type')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">SMS To</label> 
                                        {!! Form::select('sms_to', ['customer' => 'Customer', 'executive' => 'Executive'], null,array('class' => 'form-control', 'id' => 'sms_to')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Miss call alert to</label> 
                                        {!! Form::text('addtional_alert', null, ['class' => 'form-control', 'id' => 'addtional_alert']) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <!-- <label for="firstName1">Sms Content</label>  -->
                                        <select id="param" name="param" multiple="multiple" onchange="copyval()" size="6">
                                            <option value="{CUSTOMERNAME}">CUSTOMERNAME </option>
                                            <option value="{OPERATORNAME}">OPERATORNAME</option>
                                            <option value="{TICKETID}">TICKETID</option>
                                            <option value="{DEPARTMENTNAME}">DEPARTMENTNAME</option>
                                            <option value="{OPERATOR PHONENUMBER}">OPERATOR PHONENUMBER</option>
                                            <option value="{CUSTOMER DID}">CUSTOMER DID</option>
                                            <option value="{CALLERID}">CALLER ID</option>
                                            <option value="{DATETIME}">DATETIME</option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Sms Content</label> 
                                        <textarea id="content" name="content" rows="8" cols="32" class="form-control"></textarea>
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
    function copyval(){
        document.getElementById("content").value = document.getElementById("content").value + $("#param").val() ;
    }
    $(document).ready(function() {
        $( '.add_sms_form' ).on( 'submit', function(e) {
            e.preventDefault();
            //var noteHTML = "";
            var errors = ''; 
              $.ajax({
                type: "POST",
                url: '{{ URL::route("addSms") }}', // This is the url we gave in the route
                data: $('.add_sms_form').serialize(),
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
                        $("#add_sms").modal('hide');
                        toastr.success(res.success); 
                        setTimeout(function(){ location.reload() }, 3000);               
                    }
                   
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
              });
        });

        $('.edit_sms').on('click',function(e)
        {
            var id = $(this).attr("id");
            console.log(id);
            $.ajax({
            type: "GET",
            url: '/get_sms/'+ id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                console.log(res[0])
                var result = res[0];
                $("#groupid").val(result.groupid);
                $("#type").val(result.type);
                $("#content").val(result.content);
                $("#sms_to").val(result.sms_to);
                $("#addtional_alert").val(result.addtional_alert);
                $("#sms_id").val(result.id);
                
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });
    });
 </script>

@endsection

