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
                                <tr id="row_{{ $listOne->id }}">
                                    <td>{{ ($listOne->date) ? $listOne->date : ucfirst($listOne->day) }}</td>
                                    <td>{{$listOne->reason}}</td>
                                    <td><a href="javascript:void(0)" onClick="deleteItem({{$listOne->id}}, 'holiday')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </td>
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
                        <div class="pull-right">{{ $holiday_list->links() }}</div>
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
                    {!! Form::open(['class' => 'holiday_form', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!} 
                <div class="modal-body">
                      
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>
                            <div class="col-md-8 form-group mb-3">
                               <label for="date_input">Date *</label>
                                <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="date" id="date_input" autocomplete="off">
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="audio_file">Holiday Message File</label>
                                <input type="file" class="form-control" name="holiday_msg_file" id="holiday_msg_file">
                            </div>
                        </div> -->
                        <!-- show message and validate .gsm, .wav, 8kHz  -->
                        <!-- /var/lib/asterisk/sounds/IVRMANGER -->
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="audio_file">Call Transfer To</label>
                                <select class="form-control" name="call_transfer_to" id="call_transfer_to">
                                    <option value="voicemail">Voicemail</option>
                                    <option value="message">Message</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>
                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Reason*</label> 
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
    
    $(document).ready(function() {
        $( '.holiday_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
            var formData = new FormData();

            // var files = $('#holiday_msg_file')[0].files;
            // // Check file selected or not
            // if(files.length > 0 ){
            //     formData.append('holiday_msg_file',files[0]);
            // }
            formData.append('_token', $('.holiday_form input[name="_token"]').val());
            formData.append('date', $('.holiday_form #date_input').val());
            formData.append('call_transfer_to', $('.holiday_form #call_transfer_to').val());
            formData.append('reason', $('.holiday_form textarea[name="reason"]').val());
            console.log(formData);
             $.ajax({
                type: "POST",
                url: '{{ URL::route("holidayStore") }}',
                data: formData,
                contentType: false,
                processData: false,
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
                        $("#holiday_modal").modal('hide');
                        toastr.success(res.success); 
                        setTimeout(function(){ location.reload() }, 300);
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

