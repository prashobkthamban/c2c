@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Holiday </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#holiday_modal" class="btn btn-primary"> Add Holiday </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                        @foreach($holiday_list as $listOne)
                                        <tr>
                                            <td>{{ ($listOne->date) ? $listOne->date : $listOne->day }}</td>
                                            <td>{{$listOne->reason}}</td>
                                            <td><a href="{{ route('deleteHoliday', $listOne->id) }}" onclick="return confirm('You want to delete this holiday?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
           </div>

            <!-- add holiday modal -->
            <div class="modal fade" id="holiday_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Holiday</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'holiday_form', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Select Format *</label>
                                           {!! Form::select('format', ['' => 'Select Format', 'day' => 'Day Format', 'date' => 'Date Format' ], null,array('class' => 'form-control', 'id' => 'format', 'onChange' => 'setFormat()')) !!} 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>
                                <div class="col-md-8 form-group mb-3">
                                    <div id="date_wise">
                                    <label for="firstName1">Date *</label>
                                    <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="date" id="date_input" autocomplete="off">
                                    </div>
                                    <div id="day_wise">
                                        <label for="firstName1">Day *</label> 
                                         {!! Form::select('day', ['' => 'Select Day', 'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday','sunday' => 'Sundayy'], null,array('class' => 'form-control', 'id' => 'day_input')) !!}
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Reason</label> 
                                        <textarea rows="8" cols="20" class="form-control" placeholder="Reason" name="reason"></textarea>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('reason', ':message') : '' !!}</p>
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
    function setFormat() {
        hideFormat();
        var format = $("#format").val();
        if(format == 'day') {
            $("#day_wise").show();
            $('#day_input').attr('type', 'text');
        } else if(format == 'date'){
            $("#date_wise").show();
            $('#date_input').attr('type', 'text');
        } else {
            hideFormat();
        }
    }

    function hideFormat() {
        $("#day_wise").hide();
        $("#date_wise").hide();
        $('#date_input').attr('type', 'hidden');
        $('#day_input').attr('type', 'hidden');
    }

    $(document).ready(function() {
        hideFormat();
        $( '.holiday_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
             if($("#format").val() != '') {
              $.ajax({
                type: "POST",
                url: '{{ URL::route("holidayStore") }}',
                data: $('.holiday_form').serialize(),
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
                        //$("#holiday_modal").modal('hide');
                        toastr.success(res.success); 
                        // setTimeout(function(){ location.reload() }, 300);               
                    }
                   
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
              });
            } else {
                toastr.error('Select the format.');
            }
        });
    });
</script>

@endsection

