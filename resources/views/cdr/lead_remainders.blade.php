@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Lead Reminders </h1>
    </div>
            <div class="separator-breadcrumb border-top"></div>

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Reminder Task</th>
                                            <th>Date and Time</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Phoneno</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($show_all_remainder as $remainders)
                                        <tr>
                                            <td>{{$remainders->id}}</td>
                                            <td>{{$remainders->title}}</td>
                                            <td>{{$remainders->task}}</td>
                                            <td>{{$remainders->date.' '.$remainders->time}}</td>
                                            <td>{{$remainders->first_name}}</td>
                                            <td>{{$remainders->last_name}}</td>
                                            <td>{{$remainders->company_name}}</td>
                                            <td>{{$remainders->email}}</td>
                                            <?php
                                            if ($remainders->phoneno == '0') { ?>
                                               <td></td>
                                            <?php }
                                            else
                                            { ?>
                                                <td>{{$remainders->phoneno}}</td>
                                            <?php }
                                            ?>
                                            <td>
                                                <a href="javascript:void(0)" class="text-success mr-2" data-toggle="modal" data-target="#view" onClick="viewModal({{json_encode($remainders)}})">
                                                    <i class="nav-icon i-Folder-Open font-weight-bold" data-toggle="tooltip" data-placement="top" title="View Remainder"></i>
                                                </a>
                                                <a href="{{ route('deleteRemainder', $remainders->id) }}" onclick="return confirm('Are you sure you want to delete this Remainder?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="Delete Remainder">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>
                                                <a href="#" data-toggle="modal" data-target="#edit_reminder" class="text-success mr-2 edit_reminder" onClick="editModal({{json_encode($remainders)}})">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Reminder Task</th>
                                            <th>Date and Time</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Phoneno</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>

                        </div>
                        <div class="pull-right">{{ $show_all_remainder->links() }}</div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Reminder<span id="notify_title"></span></h5>
                            <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3 class="title"></h3>
                            <div class="name"></div>
                            <div class="content"></div>
                            <div class="time"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="edit_reminder" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Edit Reminder</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <form method="POST" action="{{route('updateLeadReminder')}}">
                        @csrf()
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"></div>
                                    <div class="col-md-8 form-group mb-3">
                                        <b><h5 class="title"></h5></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                        {!! Form::hidden('id', '', array('id' =>'reminder_id')) !!}
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Reminder Date</label>
                                        {!! Form::text('date', null, ['class' => 'form-control datepicker', 'id' => 'startdate', 'placeholder' => 'Followup Date']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">
                                    </div>
                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Reminder Time</label>
                                        {!! Form::text('time', null, ['class' => 'form-control', 'id' => 'timepicker1', 'placeholder' => 'Followup Time', 'data-rel' => 'timepicker', 'data-template' => 'dropdown', 'data-maxHours' => '24', 'data-show-meridian' => 'false', 'data-minute-step' => '10']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/moment.min.js')}}"></script>
{{-- <script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script> --}}
<script>
    $('#timepicker1').timepicker();
    function viewModal(remainder) {
        $('#view .title','#view .name','#view .content','#view .time').html('');
        $('#view .title').html(remainder.title);
        $('#view .name').html(remainder.first_name + ' '+ remainder.last_name);
        $('#view .content').html(remainder.task);
        $('#view .time').html(remainder.date + ' ' + remainder.time);
        $.ajax({
            type: "GET",
            url: 'remainder/view/'+ remainder.id,
            success: function(res){
                // console.log(res)
            }
        });
    }
    function editModal(remainder) {
        var date = moment(remainder.date).format("DD-MM-YYYY");
        // var time = moment(remainder.time).format("HH:mm");
        $('#edit_reminder #reminder_id','#edit_reminder #startdate','#edit_reminder #timepicker1','#edit_reminder .title').val('');
        $('#edit_reminder #reminder_id').val(remainder.id);
        $('#edit_reminder .title').html(remainder.title);
        $('#edit_reminder #startdate').val(date);
        $('#edit_reminder #timepicker1').val(remainder.time);
    }
    $('.close-btn').on('click',function(e) {
        window.location.reload();
    });
</script>
@endsection
