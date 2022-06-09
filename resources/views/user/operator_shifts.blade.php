@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Operator Shifts</h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#operator_shift" class="btn btn-primary add_shift">Add Operator Shift</a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Shift Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($results as $result)
                                        <tr>
                                            <td>{{$result->shift_name}}</td>
                                            <td>{{$result->start_time}}</td>
                                            <td>{{$result->end_time}}</td>   
                                            <td><a href="#" data-toggle="modal" data-target="#operator_shift" class="text-success mr-2 edit_shift" id="{{$result->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('DeleteShift', $result->id) }}" onclick="return confirm('You want to delete this operator shift?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Shift Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                {{ $results->links() }}
                            </div>

                        </div>
                    </div>
                </div>
           </div>

           <!-- add holiday modal -->
            <div class="modal fade" id="operator_shift" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title">Add Operator Shift</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'add_shift_form', 'method' => 'post', 'autocomplete' => 'off']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        <input type="hidden" name="id" id="shift_id" />
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Shift Name *</label> 
                                        <input type="text" class="form-control" placeholder="Shift Name" name="shift_name" id="shift_name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Start Time *</label> 
                                        <input type="text" class="form-control time-picker-24-hr" placeholder="00:00:00" name="start_time" id="start_time">
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">End Time *</label> 
                                        <input type="text" class="form-control time-picker-24-hr" placeholder="23:59:59" name="end_time" id="end_time">
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
        if($('.time-picker-24-hr').length) {
            $('.time-picker-24-hr').datetimepicker({
                //use24hours: true,
                format: 'HH:mm:ss'
            });
        }
        $( '.add_shift_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("AddShift") }}', // This is the url we gave in the route
            data: $('.add_shift_form').serialize(),
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
                    $("#operator_shift").modal('hide');
                    $(".add_shift_form")[0].reset();
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 500);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.edit_shift').on('click',function(e)
        {
            $("#modal-title").text('Edit Operator Shift');
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_shift/'+ id, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                var res = result[0];
                console.log(res);
                $("#shift_id").val(res.id);
                $("#shift_name").val(res.shift_name);
                $("#start_time").val(res.start_time);
                $("#end_time").val(res.end_time);        
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('.add_shift').on('click',function(e) {
            $("#modal-title").text('Add Operator Shift');
            $(".add_shift_form")[0].reset();
        });
    });
</script>

@endsection

