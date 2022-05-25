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
                <h5 class="ml-3">Search Panel</h5></br>
                    <div class="row" style="margin-right: 24px;margin-left: 24px;">
                        <div class="col-md-12">
                            <form id="reminder_form" method="GET" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-perpage">Departments</label>
                                    {!! Form::select('department', $depts->prepend('All', ''), (isset($_GET['department'])) ? $_GET['department'] : '',array('class' => 'form-control', 'id' => 'department')) !!}
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-perpage">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All</option>
                                        <option value="live" <?= ($params['status'] == 'live') ? 'selected' : ''; ?>>Live</option>
                                        <option value="close" <?= ($params['status'] == 'close') ? 'selected' : ''; ?>>Close</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-search">By Caller Number</label>
                                    <input type="text" class="form-control input-sm" value="<?= $params['caller']; ?>" name="caller_number">
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-search">By Operator Name</label>
                                    <input type="text" class="form-control input-sm" value="<?= $params['operator']; ?>" name="operator">
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-perpage">Date</label>
                                    <select class="form-control" name="date" id="date_select">
                                        <option value="">All</option>
                                        <option value="today" <?= ($params['date'] == 'today') ? 'selected' : ''; ?>>Today</option>
                                        <option value="yesterday" <?= ($params['date'] == 'yesterday') ? 'selected' : ''; ?>>Yesterday</option>
                                        <option value="week" <?= ($params['date'] == 'week') ? 'selected' : ''; ?>>1 Week</option>
                                        <option value="month" <?= ($params['date'] == 'month') ? 'selected' : ''; ?>>1 Month</option>
                                        <option value="custom" <?= ($params['date'] == 'custom') ? 'selected' : ''; ?>>Custom</option>
                                    </select>
                                </div>
                                <div class="col-md-4 custom_date_div d-none">
                                    <label class="filter-col"  for="pref-search">Date From</label>
                                    <input type="text" name="date_from" value="<?= (isset($_GET['date_from'])) ? $_GET['date_from'] : ''; ?>" class="form-control input-sm datepicker" >
                                </div>
                                <div class="col-md-4 custom_date_div d-none">
                                    <label class="filter-col"  for="pref-search">Date To</label>
                                    <input type="text" class="form-control input-sm datepicker" name="date_to" value="<?= (isset($_GET['date_to'])) ? $_GET['date_to'] : ''; ?>">
                                </div>
                                <div class="col-md-4 custom_date_div d-none" style="display:none">
                                </div>
                                <div class="col-md-6" style="margin-top: 24px;">
                                    <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                    <a href="{{url('reminder')}}" class="btn btn-outline-secondary" name="btn">Clear</a>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <br><br>
                    <div class="table-responsive">
                        <table id="reminder_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Caller</th>
                                <th>Reminder Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Department</th>
                                @if(Auth::user()->usertype=='groupadmin')
                                <th>Follower</th>
                                @endif
                                <th>Assigned to</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php //dd($result); ?>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr data-toggle="collapse" data-target="#accordion_{{$row->id}}" class="clickable" id="row_{{ $row->id }}">
                                <td>
                                    <a href="?" id="callerid_{{$row->id}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->number }}" class="tag_btn_{{$row->cdrId}}" data-tag="{{$row->tag}}" data-unique-id="{{$row->uniqueid}}" data-show-notes="@if(sizeof($row['cdrNotes']) > 0) inline @else none @endif" data-contact-id="{{ $row->contacts && $row->contacts->id ? $row->contacts->id : ''}}" data-email="{{ $row->contacts && $row->contacts->email ? $row->contacts->email : ''}}" data-fname="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : ''}}" data-lname="{{ $row->contacts && $row->contacts->lname ? $row->contacts->lname : ''}}" data-phone="{{$row->number}}" onClick="moreOption('{{$row->cdrId}}', '{{$row->id}}', '{{ $row->contacts && $row->contacts->id ? true : false}}');return false;"><i class="fa fa-phone"></i>{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                </td>
                                <td>{{$row->followupdate}}</td>
                                <td>{{$row->secondleg}}</td>
                                <td>{{$row->appoint_status}}</td>
                                <td><a>{{$row->deptname}}</a></td>
                                @if(Auth::user()->usertype=='groupadmin')
                                    <td>{{$row->follower}}</td>
                                @endif
                                <td>{{$row->assignedtoname}}</td>
                                <td>
                                @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator')
                                        <a href="#" class="btn play_audio" <?php echo (!empty($row->recordedfilename)) ? "style=''" : "style='visibility:hidden'"; ?> title="Play Audio" data-toggle="modal" data-target="#play_modal" data-file="{{$row->recordedfilename}}" id="play_{{$row->groupid}}"><i class="i-Play-Music"></i></a>
                                @endif
                                @if($row->reminder_seen == '0')
                                    <a href="javascript:void(0)" class="text-warning mr-2 reminder-seen" title="Mark as seen" data-id="{{$row->id}}">
                                            <i class="nav-icon i-Flag-2 font-weight-bold"></i>
                                    </a>
                                @endif
                                    <a href="#" data-toggle="modal" data-target="#edit_reminder" class="text-success mr-2 edit_reminder" id="{{$row->id}}">
                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    </a>

                                    <a href="javascript:void(0)" onClick="deleteItem({{$row->id}}, 'reminders')" class="text-danger mr-2">
                                        <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                                @endif

                            </tbody>

                        </table>
                    </div>
                </div>
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

    <!-- play modal -->
    <div class="modal fade" id="play_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                            <!-- <h5 class="modal-title">Dial A Number</h5> -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body" id="play_src">
                    </div>
                </div>
            </div>
        </div>

<!-- tag modal -->
<div class="modal fade" id="tag_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tag_title">Add Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'tag_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                        {!! Form::hidden('cdrid', null, ['class' => 'form-control', 'id' => 'cdrid']) !!}
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Tag</label>
                        {!! Form::select('tag', $tags->prepend('Select Tag', ''), null,array('class' => 'form-control', 'id' => 'cdr_tag')) !!}
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
<!--end of tag modal -->

<!-- notes modal -->
<div class="modal fade" id="notes_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="notes_list_table" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Operator</th>
                            <th>Date</th>
                            <th>Comments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--end of notes modal -->

<!-- add note modal -->
<div class="modal fade" id="add_note_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'notes_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="uniqueid" id="uniqueid" />
                        <textarea class="form-control" rows="5" name="note" placeholder="Comment"></textarea>
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
<!-- end of add note modal -->

<!-- add contact -->
<div class="modal fade" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contact_title">Add Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'contact_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3"></div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">First Name</label>
                        <input type="hidden" name="contact_id" id="contact_id">
                        <input type="hidden" name="phone" id="phone">
                        <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"> </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"> </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Email</label>
                        <input type="email" name="email" id="emailaddress" class="form-control" placeholder="Email">
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
<!-- end of add contact -->

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
<script type="text/javascript">
    $('#timepicker1').timepicker();
    //$('.more_option').hide();
    $('#reminder_table').DataTable();

    function moreOption(cdrId, id, isContactAdded) {
        console.log(id);
        console.log(name);
        var elem = $("#callerid_"+id);
        var className = $("#second_row").attr('class');
        var tag = elem.data('tag');
        var uniqueId = elem.data('unique-id');
        var showNotes = elem.data('show-notes');
        var contactId = elem.data('contact-id');
        var email = elem.data('email');
        var firstName = elem.data('fname');
        var lastName = elem.data('lname');
        var phoneNumber = elem.data('phone');
        if(className == 'show') {
            $("#second_row").remove();
        } else {
            let contactText = 'Add Contact';
            if(isContactAdded) {
                contactText = 'Edit Contact';
            }

            $('#row_'+id).after('<tr id="second_row" class="show"><td colspan="8"><div>'+
            '<button type="button" id="edit_contact_'+contactId+'" data-toggle="modal" data-target="#contact_modal" class="btn btn-info m-1 clickable edit_contact" data-contact-id="'+contactId+'" data-email="'+email+'" data-fname="'+firstName+'" data-lname="'+lastName+'" data-phone="'+phoneNumber+'">'+contactText+'</button>'+
            '<button type="button" id="notes" data-cdr-id="'+cdrId+'" data-toggle="modal" data-target="#notes_modal" data-unique-id="'+uniqueId+'" class="btn btn-info m-1 notes_list" style="display:'+showNotes+'">Notes</button>'+
            '<button type="button" id="add_notes" data-cdr-id="'+cdrId+'" data-toggle="modal" data-target="#add_note_modal" data-unique-id="'+uniqueId+'" class="btn btn-info m-1 add_note">Add Notes</button>'+
            '<button type="button" id="tag" data-cdr-id="'+cdrId+'" data-tag="'+tag+'" data-toggle="modal" data-target="#tag_modal" class="btn btn-info m-1 edit_tag tag_btn_'+cdrId+'">Tag</button>'+
            '</div></td></tr>');
        }
    }

    $(document).ready(function() {
        $('.play_audio').on('click',function(e)
        {
            var file = $(this).attr("data-file");
            file = 'voicefiles/' + file;
            $("#play_src").html('<audio controls id="audioSource"><source src="' + file + '" type="video/mp4"></source></audio>' );
        });

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
            url: '{{ URL::route("addReminder") }}', // This is the url we gave in the route
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

        $(".reminder-seen").on("click", function() {
            let elem = $(this);
            var data = {'id': elem.data('id')};
            var url = "{{ url('reminder_seen') }}";
            ajaxCall(url, data)
            .then(function(result) {
                if(result.status) {
                    toastr.success(result.message);
                    elem.remove();
                    setTimeout(function(){ location.reload() }, 3000);
                } else {
                    toastr.error(result.message);
                }
            });
        })

        $(document).on('click', '.edit_tag', function(e) {
            var cdrId = $(this).attr("data-cdr-id");
            var tag = $(this).attr("data-tag");
            console.log('cdrId : '+cdrId);
            console.log('tag : '+tag);
            $("#cdrid").val(cdrId);
            if (tag != '') {
                $("#tag_title").text("Update Tag");
                $("#cdr_tag").val(tag);
            } else {
                $("#tag_title").text("Add Tag");
                $("#cdr_tag").val('');
            }
        });

        $(document).on('click', '.notes_list', function(e) {
            var uniqueid = $(this).attr("data-unique-id");
            $.ajax({
                url: '/notes/' + uniqueid, // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    var response = JSON.stringify(res);
                    var noteHTML = "";
                    if (res.length > 0) {
                        $.each(res, function(idx, obj) {
                            noteHTML += "<tr class='cmnt_row_" + obj.id + "'>";
                            noteHTML += "<td>" + obj.operator + "</td>";
                            noteHTML += "<td>" + obj.datetime + "</td>";
                            noteHTML += "<td>" + obj.note + "</td>";
                            noteHTML += "<td><a href='#' class='text-danger mr-2 delete_comment' id='" + obj.id + "'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                            noteHTML += "</tr>";

                        });
                    } else {
                        noteHTML += "<tr><td colspan='4'><center>No Data Found</center></td></tr>";
                    }
                    $("#notes_list_table tbody").html(noteHTML);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });

        });

        $(document).on('click', '.add_note', function(e) {
            var uniqueid = $(this).attr("data-unique-id");
            $("#uniqueid").val(uniqueid);
        });

        $(document).on('click', '.edit_contact', function(e) {
            var contactid = $(this).attr("data-contact-id");
            var email = $(this).attr("data-email");
            var fname = $(this).attr("data-fname");
            var lname = $(this).attr("data-lname");
            var phone = $(this).attr("data-phone");
            $("#contact_id").val(contactid);
            $("#emailaddress").val(email);
            $("#fname").val(fname);
            $("#lname").val(lname);
            $("#phone").val(phone);
            if (contactid != '') {
                $("#contact_title").text("Update Contact");
            } else {
                $("#contact_title").text("Add Contact");
            }

        });
    });
 </script>
@endsection

