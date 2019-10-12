@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>My Reminder </h1>

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
                                        <th>Caller</th>
                                        <th>Reminder Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Assigned to</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr data-toggle="collapse" data-target="#accordion_{{$row->id}}" class="clickable">
                                        <td>
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" id="callerid_{{$row->id}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->contacts->fname ? $row->contacts->fname : $row->number }}
                                                @else
                                                <a href="?" id="callerid_{{$row->id}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->followupdate}}</td>
                                        <td>{{$row->secondleg}}</td>
                                        <td>{{$row->appoint_status}}</td>
                                        <td><a>{{$row->deptname}}</a></td>
                                        <td>{{$row->opername}}</td>
                                        <td>{{$row->assignedname}}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#edit_reminder" class="text-success mr-2 edit_reminder" id="{{$row->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                            </a>
                                            <a href="{{ route('deleteReminder', $row->id) }}" onclick="return confirm('Are you sure want to delete this reminder ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                            </a>
                                        </td>


                                    </tr>
                                    <tr id="accordion_{{$row->id}}" class="collapse">
                                        <td colspan="8">
                                            <div >
                                                @if(!empty($row->contacts->fname))
                                                <button type="button" onClick="xajax_show('contact_form_{{$row->id}}')" class="btn btn-info m-1 clickable" >View Contact</button>
                                                @else
                                                <button type="button" onClick="xajax_show('contact_form_{{$row->id}}')" class="btn btn-info m-1 clickable">Add Contact</button>
                                                @endif
                                                <button type="button" onClick="xajax_show('notes_{{$row->id}}')" class="btn btn-info m-1">Notes</button>
                                                <button type="button" onClick="xajax_show('add_tag_{{$row->id}}')" class="btn btn-info m-1">Tag</button>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">
                                            <div class="row d-none cdr_form" id="contact_form_{{$row->id}}">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Add Contact</h5>
                                                            <form class="contact_form" id="add_contact{{$row->id}}">
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input type="hidden" name="contact_id" value="{{$row->contacts->id}}">
                                                                        <input type="hidden" name="phone" value="{{$row->number}}">
                                                                        <input type="hidden" name="groupid" value="{{$row->groupid}}">
                                                                        <input type="text" class="form-control" name="fname" value="{{$row->contacts->fname}}" placeholder="First Name">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="lname" value="{{$row->contacts->lname}}" class="form-control" placeholder="Last Name">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input type="email" name="email" value="{{$row->contacts->email}}" class="form-control" placeholder="Email">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="notes_{{$row->id}}">
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                            <h5>Notes for this Call</h5>
                                                            <button type="button" onClick="xajax_show('add_note_{{$row->id}}')" style="margin: 0px 0px 12px 288px !important;"class="btn btn-info">Add Note</button>
                                                            </div>
                                                            <table id="comments_table" class="display table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Operator</th>
                                                                        <th>Date</th>
                                                                        <th>Comments</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(!$row->cdrNotes->isEmpty())
                                                                    @foreach($row->cdrNotes as $note )
                                                                        <tr class="cmnt_row_{{$note->id}}">
                                                                            <td>{{$note->operator}}</td>
                                                                            <td>{{$note->datetime}}</td>
                                                                            <td>{{$note->note}}</td>
                                                                            <td><a href="javascript:void(0)" class="text-danger mr-2 delete_comment" id="{{$note->id}}"><i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                                            </a></td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @else 
                                                                        <tr>
                                                                            <td colspan="3">No Comments !!!</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="add_note_{{$row->id}}">
                                                <div class="col-md-5">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Add Note</h5>
                                                            <form class="notes_form" id="cdr_note_{{$row->id}}" autocomplete="off">
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                      <input type="hidden" name="cdrid" value="{{$row->id}}" />
                                                                      <input type="hidden" name="uniqueid" value="{{$row->uniqueid}}"/>
                                                                      <textarea class="form-control" rows="5" name="note" placeholder="Comment"></textarea>
                                                                    </div>
                                                                </div>                          
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="add_tag_{{$row->id}}">
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Tag Call</h5>
                                                            <form class="tag_form" id="tag_form{{$row->cdrid}}">
                                                                <input type="hidden" name="cdrid" value="{{$row->cdrid}}" />

                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                
                                                                        {!! Form::select('tag', $tags->prepend('Select Tag', ''), null,array('class' => 'form-control')) !!}
                                                                    
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                            
                                        </td>
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>

                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->
            <!-- add operator modal -->
            <div class="modal fade" id="edit_reminder" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Edit Reminder</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'edit_reminder_form', 'method' => 'post', 'autocomplete' => 'off']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('id', '', array('id' =>'reminder_id')) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Reminder Date</label> 
                                          {!! Form::text('startdate', null, ['class' => 'form-control datepicker', 'id' => 'startdate', 'placeholder' => 'Followup Date']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Reminder Time</label> 
                                          {!! Form::text('starttime', null, ['class' => 'form-control', 'id' => 'timepicker1', 'placeholder' => 'Followup Time', 'data-rel' => 'timepicker', 'data-template' => 'dropdown', 'data-maxHours' => '24', 'data-show-meridian' => 'false', 'data-minute-step' => '10']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Followup Status</label> 
                                        {!! Form::select('appoint_status', ['live' => 'Live', 'close' => 'Close'], null,array('class' => 'form-control', 'id' => 'appoint_status')) !!}
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
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
<script type="text/javascript">
    $('#timepicker1').timepicker();
    function xajax_show(id) {
        $(".cdr_form").addClass('d-none');
        $("#"+id).removeClass('d-none');
    } 

    function xajax_hide() {
        $(".cdr_form").addClass('d-none');
    }
    $(document).ready(function() {
        $('.edit_reminder').on('click',function(e)
        {
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_reminder/'+ id, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                var res = result[0];
                var date = moment(res.followupdate).format("DD-MM-YYYY");
                var time = moment(res.followupdate).format("HH:mm"); 
                $("#reminder_id").val(res.id);
                $("#startdate").val(date);
                $("#timepicker1").val(time);
                $("#appoint_status").val(res.appoint_status);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $( '.edit_reminder_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '/add_reminder/', // This is the url we gave in the route
            data: $('.edit_reminder_form').serialize(),
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
                    $("#edit_reminder").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 3000);               
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
