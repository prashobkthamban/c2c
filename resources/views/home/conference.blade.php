@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1>Conference Report </h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>


    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_conference" class="btn btn-primary"> Dial a Conference Call </a>
                   <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                               @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                                <th>Customer</th>
                               @endif
                                <th>Call Time</th>
                                <th>Moderator</th>
                                <th>CallStatus</th>
                                <th>Duration</th>
                                <th>Coins Used</th>
                                @if(Auth::user()->usertype == 'groupadmin')
                                <th>Comments</th>
                                @endif
                                <th>Call Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr>
                                @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                                <td>{{ $row->name }}</td>
                                @endif
                                <td>{{$row->datetime}}</td>
                                <td>{{$row->moderator}}</td>
                                <td>{{$row->moderator_dialstatus}}</td>
                                <td>{{$row->duration}}</td>
                                <td>{{$row->coins}}</td>
                                 @if(Auth::user()->usertype == 'groupadmin')
                                <td>{{$row->comments}}</td>
                                @endif
                                <td><a href="#" data-toggle="modal" data-target="#log_modal" class="text-primary mr-2 conference_list" id="{{$row->id}}">{{$row->conferenceLog->count()}}<i class="fa fa-info-circle"></i></a></td>
                                <td><a href="#" data-toggle="modal" data-target="#comment_sec" class="text-success mr-2 edit_comment" onClick="edit_comment('{{$row->id}}', '{{$row->comments}}');return false;">
                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                </a></td>
                            </tr>
                            @endforeach
                                @endif

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Customer</th>
                                <th>Call Time</th>
                                <th>Moderator</th>
                                <th>CallStatus</th>
                                <th>Duration</th>
                                <th>Coins Used</th>
                                <th>Comments</th>
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

    <!-- add conference modal -->
    <div class="modal fade" id="add_conference" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create a Conference</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 {!! Form::open(['class' => 'conference_form', 'method' => 'post']) !!} 
                <div class="modal-body">
                    <table id="zero_configuration_table" class="table-borderless table" style="width:100%;">
                    <tr>
                        <td>
                        <label for="firstName1">Comments *</label>
                         {!! Form::textarea('comments', null, ['class' => 'form-control', 'id' => 'comments', 'rows' => 3]) !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <label for="firstName1">Moderator No *</label>
                        <input type="number" class="form-control" name="moderator" value="{{Auth::user()->phone_number}}" id="moderator"> 
                        </td>
                        <td>
                            Unmute
                        </td>
                        <td>
                            Mute
                        </td>
                        <td>
                            KickOut
                        </td>
                        </tr> 
                    <?php  
                    $max_conf = Auth::user()->load('accountdetails')->accountdetails->max_no_confrence;
                    for($i = 0; $i < $max_conf; $i++)
                    { $j = $i + 1; ?>
                        <tr>
                            <td>
                                <label for="firstName1">Member {{$j}}</label> 
                                    {!! Form::number($j, null, ['class' => 'form-control', 'id' => $j]) !!}
                            </td>
                            <td>
                                <a href="#"><i class="font-weight-bold i-Microphone-3"></i></a>
                            </td>
                            <td>
                                <a href="#"><i class="font-weight-bold i-Microphone-3"></i></a>
                            </td>
                            <td>
                                <a href="#"><i class="font-weight-bold i-Arrow-Out-Right"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
                                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Disconnect Conference</button>
                    <button type="submit" class="btn btn-primary">Dial Now</button>
                </div>
                 {!! Form::close() !!}
            </div>
        </div>

    </div>

    <!-- edit comment modal -->
        <div class="modal fade" id="comment_sec" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                     {!! Form::open(['class' => 'comment_form', 'method' => 'post', 'autocomplete' => 'off']) !!} 
                    <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2 form-group mb-3"> 
                                </div>
                                <div class="col-md-8 form-group mb-3">
                                    <label for="picker1">Comment</label>
                                    <input type="hidden" name="conf_id" id="conf_id" /> 
                                    {!! Form::textarea('comments', null, ['class' => 'form-control', 'id' => 'comment_box', 'rows' => 3]) !!}
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

    <!-- conference log modal -->
     <!-- list modal -->
            <div class="modal fade" id="log_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Call Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table id="call_list_table" class="display table table-striped table-bordered" style="width:100%">
                               <thead>
                                    <tr>
                                        <th>Member No</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>

@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
    function edit_comment(id, comment) {
        $("#comment_box").val(comment);
        $("#conf_id").val(id);
    }

    $(document).ready(function() {
        $( '.conference_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("AddConference") }}',
            data: $('.conference_form').serialize(),
            success: function(res){
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
                    $("#add_conference").modal('hide');
                    $(".conference_form")[0].reset();
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 400);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { 
                toastr.error('Some errors are occured');
            }
          });
        });

        $( '.comment_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("EditComment") }}',
            data: $('.comment_form').serialize(),
            success: function(res){
                $("#comment_sec").modal('hide');
                $(".comment_form")[0].reset();
                toastr.success(res.success); 
                setTimeout(function(){ location.reload() }, 400);                        
            },
            error: function(jqXHR, textStatus, errorThrown) { 
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.conference_list').click(function() {
          // alert(this.id);  
          $.ajax({
            url: '/call_list/'+this.id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var response = JSON.stringify(res);
                console.log(res.length);
                
                var listHTML = "";
                if(res.length > 0) {
                     $.each(res, function(idx, obj) {
                         console.log(obj);
                         listHTML += "<tr>";
                         listHTML += "<td>" + obj.member  + "</td>";
                         listHTML += "<td>" + obj.duration  + "</td>";
                         listHTML += "<td>" + obj.dialstatus + "</td>";
                         listHTML += "<td>" + obj.coin + "</td>";
                         listHTML += "</tr>";

                     }); 
                } else {
                    listHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
                } 
                $("#call_list_table tbody").html(listHTML);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });
    });
</script>
@endsection
