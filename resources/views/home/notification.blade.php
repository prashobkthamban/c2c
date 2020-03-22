@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1>Notification Manager</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">

                <div class="card-body">
                   <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_notification" class="btn btn-primary"> Add New </a>
                   <input type="hidden" name="login_user" id="login_user" value={{Auth::user()->username}} />
                   <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr><?php if(Auth::user()->usertype == 'admin' || Auth::user()->usertype =='reseller'){ ?>
                                <th>IVR User</th> <?php } ?>
                                <th>Username</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr><?php if(Auth::user()->usertype == 'admin' || Auth::user()->usertype =='reseller'){ ?>
                                <td>{{$row->name}}</td> <?php } ?>
                                <td>{{$row->fromusername}}</td>
                                <td>{{$row->title}}</td>
                                <td>{{$row->description}}</td>
                                <td>{{$row->datetime}}</td>
                                <td><a href="#" title="View Notification" class="text-primary mr-2 view_modal" data-toggle="modal" data-target="#view" id="view_{{$row->id}}" onClick="replyModal({{$row->id}})">
                                    @if(Auth::user()->id == $row->send_from_id && ($row->sendfromusertype == 'operator' || $row->sendfromusertype == 'groupadmin' || $row->sendtousertype == 'admin'))
                                        <i class="nav-icon vghv {{($row->adm_read_status == '1') ? 'i-Folder-Open' :'i-Mail-2'}} font-weight-bold"></i>
                                    @elseif(Auth::user()->id == $row->send_to_id && ( $row->sendtousertype == 'operator' || $row->sendtousertype == 'groupadmin') || $row->sendtousertype == 'admin') 
                                        <i class="nav-icon jhg {{($row->grp_readstatus == '1') ? 'i-Folder-Open' :'i-Mail-2'}} font-weight-bold"></i>
                                    @endif
                                    </a>
                                    
                                    <a href="{{ route('deleteNotification', $row->id) }}" title="Delete Notification" onclick="return confirm('Are you sure want to delete this notification ?')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a>
                                    <a href="#" title="Reply Notification" class="text-primary mr-2 reply_modal" data-toggle="modal" data-target="#reply" onClick="replyModal({{$row->id}})">
                                        <i class="nav-icon i-Mail-Reply font-weight-bold"></i>
                                    </a>
                                    
                                </td>

                            </tr>
                            @endforeach
                                @endif

                            </tbody>
                            <tfoot>
                            <tr><?php if(Auth::user()->usertype == 'admin' || Auth::user()->usertype =='reseller'){ ?>
                                <th>IVR User </th> <?php  } ?>
                                <th>Username</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of col -->

    </div>
    <!-- end of row -->

    <!-- add notifiaction modal -->
    <div class="modal fade" id="add_notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 {!! Form::open(['class' => 'notification_form', 'method' => 'post']) !!} 
                <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>
 
                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Customer *</label>
                                <?php if(Auth::user()->usertype == 'groupadmin') { 
                                    $oprList = getOperatorList(); ?>
                                 <select name="send_to_id" class="form-control">
                                    <option value="1,admin">Admin</option>
                                    @if(!empty($oprList))
                                        @foreach($oprList as $opr )
                                            <option value="{{$opr->id}},{{$opr->usertype}}">{{$opr->opername}}
                                            </option>
                                        @endforeach
                                    @endif   
                                </select>  
                                <?php } ?> 
                                <?php if(Auth::user()->usertype == 'operator') { 
                                    $grpList = getGroupList(); ?>
                                <select name="send_to_id" class="form-control">
                                    <option value="1,admin">Admin</option>
                                    @if(!empty($grpList))
                                        @foreach($grpList as $grp )
                                            <option value="{{$grp->id}},groupadmin">{{$grp->name}}
                                            </option>
                                        @endforeach
                                    @endif  
                                </select>  
                                <?php } ?>

                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Title *</label> 
                                {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-2 form-group mb-3"> 
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Description *</label> 
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'rows' => 5, 'cols' => 15]) !!}
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

    <div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Notification <span id="notify_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    <div class="chat-content perfect-scrollbar ps ps--active-y" data-suppress-scroll-x="true">
        
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="reply" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle-2">Reply Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['class' => 'reply_form', 'method' => 'post']) !!} 
                <div class="modal-body"> 
                    <div class="chat-content perfect-scrollbar ps ps--active-y" data-suppress-scroll-x="true">
        
                    </div>
                    <div class="form-group">
                        {!! Form::hidden('not_id', '', array('id' =>'not_id')) !!}
                        {!! Form::textarea('description', null, ['placeholder' => 'Reply', 'class' => 'form-control form-control-rounded', 'id' => 'desc', 'rows' => 3, 'cols' => 30]) !!}
                    </div>      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
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
    function replyModal(notifyid) {
        $("#not_id").val(notifyid);
        $(".chat-content").html("");
        $.ajax({
        type: "GET",
        url: '/get_all_notification/'+ notifyid, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                var login_user = $("#login_user").val();
                var chatHtml = "";
                var res = [{'desc' : result[0].description, 'name' : result[0].fromusername, 'datetime': result[0].datetime}];
                $("#notify_title").text('| Title : ' + result[0].title);
                // console.log('resultresult', result.length);
                // console.log('result', result);
                if(result[0].sub_description !== null) {
                    $.each(result, function (i, item) {
                        //console.log('item', item);
                        res.push({'desc' : item.sub_description, 'name' : item.sub_username, 'datetime': item.sub_datetime});
                    });
                }
                
                //console.log('resr1', res);
                $.each(res, function (i, item) {
                    //console.log('reee', item);
                    if(item.name == login_user) {
                        chatHtml += '<div class="d-flex mb-4 user"><img src="http://127.0.0.1:8181/assets/images/faces/1.jpg" alt="" class="avatar-sm rounded-circle mr-3"><div class="message flex-grow-1"><div class="d-flex"><p class="mb-1 text-title text-16 flex-grow-1">'+ item.name +'</p><span class="text-small text-muted">24 min ago</span></div><p class="m-0">'+ item.desc +'</p></div></div>';
                    } else {
                        chatHtml += '<div class="d-flex mb-4"><div class="message flex-grow-1"><div class="d-flex"><p class="mb-1 text-title text-16 flex-grow-1">'+ item.name +'</p><span class="text-small text-muted">24 min ago</span></div><p class="m-0">'+ item.desc +'</p></div><img src="http://127.0.0.1:8181/assets/images/faces/13.jpg" alt="" class="avatar-sm rounded-circle ml-3"></div>';
                    }
                });
                $('.chat-content').append(chatHtml);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        });
    }
    $(document).ready(function() {
        $( '.notification_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addNotification") }}', 
            data: $('.notification_form').serialize(),
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
                    $("#add_notification").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 400);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });
  
        $( '.reply_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addSubNotification") }}', // This is the url we gave in the route
            data: $('.reply_form').serialize(),
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
                    replyModal(res.id);   
                    $("#desc").val('');         
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $( '.view_modal' ).on( 'click', function(e) {
            var id = this.id;
            var view_id = id.replace("view_", "");
            var not_count = $(".notification_count").text();
            $.ajax({
            type: "POST",
            url: '{{ URL::route("updateStatus") }}', // This is the url we gave in the route
            data: {view_id : view_id, status : 1},
            success: function(res){ // What to do if we succeed
                console.log(res);
                if(res.status == true) {
                    $('#'+id).html('<i class="nav-icon i-Folder-Open font-weight-bold"></i>');
                    var notificationCount = not_count - 1;
                    if(notificationCount == -1) {
                        $(".notification_count").text(0);
                    } else {
                        $(".notification_count").text(not_count-1);
                    }
                    $("#not_id_"+view_id).remove();
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
