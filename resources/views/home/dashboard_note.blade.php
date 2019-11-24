@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Dashboard Manager</h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

    @if(session()->has('success'))  
      <div class="col-12 alert alert-success text-center mt-1" id="alert_message">{{ session()->get('success') }}</div>   
    @endif


            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_note" class="btn btn-primary"> Help </a>
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                    
                                        <td>{{ $row->msg }}</td>
                                        <td>{{ $row->date }}</td>
                                        <td><a href="{{ route('deleteAnnouncement', $row->id) }}" onclick="return confirm('Are you sure want to delete this announcement ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                            </a></td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Description</th>
                                        <th>Date</th>
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
            <div class="modal fade" id="add_note" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Announce</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'announce_form', 'method' => 'post']) !!} 
                        <div class="modal-body">  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Announcement *</label> 
                                        {!! Form::textarea('msg', null, ['class' => 'form-control', 'id' => 'msg', 'rows' => 5, 'cols' => 15]) !!}
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

 <script type="text/javascript">
     setTimeout(function(){ $(".alert-success").hide(); }, 2500);
 </script>   
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
     $(document).ready(function() {
        $( '.announce_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addAnnouncement") }}', // This is the url we gave in the route
            data: $('.announce_form').serialize(),
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
                    $("#add_note").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 2000);               
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