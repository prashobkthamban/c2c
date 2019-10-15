@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Coperate </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" data-toggle="modal" data-target="#reseller_form" href="#" class="btn btn-primary" id="add_coperate"> Add Coperate </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Coperate Name</th>
                                            <th>CDR API Key</th>
                                            <th>Add Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($resellers as $reseller)
                                        <tr>
                                            <td>{{$reseller->resellername}}</td>
                                            <td>{{$reseller->cdr_apikey}}</td>
                                            <td>{{ date('d-m-Y', strtotime($reseller->adddate)) }}</td>
                                            <td><a  id="{{$reseller->id}}" data-toggle="modal" data-target="#reseller_form" href="javaScript:void(0);" class="text-success mr-2 edit_form">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('destroyCoperate', $reseller->id) }}" onclick="return confirm('You want to delete this coperate?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Coporate Name</th>
                                            <th>CDR API Key</th>
                                            <th>Add Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $resellers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
           </div>

           <!-- Add New Coperate Manager -->
            <div class="modal fade" id="reseller_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="coperate_title">Add Coperate account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['method' => 'post', 'id' => 'coperate_form']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        <input type="hidden" name="id" id="id"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Coperate Name *</label> 
                                        <input type="text" class="form-control" placeholder="Coperate Name" name="resellername" id="resellername"> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Username *</label> 
                                        <input type="text" class="form-control" placeholder="Username" name="username" id="username">
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
                                        <label for="firstName1">CDR API Key *</label> 
                                        <input type="text" class="form-control" value="{{$cdr_api_key}}" placeholder="CDR API Key" name="cdr_apikey" id="cdr_apikey"> 
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
    $(document).ready(function() {
        $( '#coperate_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '/add_coperate/', // This is the url we gave in the route
            data: $('#coperate_form').serialize(),
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
                    toastr.success(res.success);
                    setTimeout( function() { 
                        location.reload(true); 
                    }, 1000);
                    
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.edit_form').click(function() {
            $("#coperate_title").text('Edit Coperate account');
            $.ajax({
            url: '/edit_coperate/'+this.id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var response = JSON.stringify(res);
                console.log(res.length);
                console.log(res);
                if(res.length > 0) {
                    $("#reseller_form").modal('show');
                    var data = res[0];
                    $("#id").val(data.id);
                    $("#resellername").val(data.resellername);
                    $("#cdr_apikey").val(data.cdr_apikey);
                    $("#username").val(data.username);
                    $("#password").val(data.password);
                } 
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('#add_coperate').click(function() {
            $("#coperate_form").trigger("reset");
        });
        
    });
</script>

@endsection

