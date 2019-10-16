@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> PBX Extensions </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_ringgroup" class="btn btn-primary"> Add New </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Ring Group</th>
                                            <th>Description</th>
                                            <th>Strategy</th>
                                            <th>Members</th>
                                            <th>Customer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($pbx_list as $listOne)
                                        <tr>
                                            <td>{{$listOne->ringgroup}}</td>
                                            <td>{{$listOne->description}}</td>
                                            <td>{{$listOne->strategy}}</td>
                                            <td>{{$listOne->members}}</td>
                                            <td>{{$listOne->name}}</td>
                                            <td><a href="#" data-toggle="modal" data-target="#add_ringgroup" class="text-success mr-2 edit_ringgroup" id="{{$listOne->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteRinggroup', $listOne->id) }}" onclick="return confirm('Are you sure want to delete this record ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Ring Group</th>
                                            <th>Description</th>
                                            <th>Strategy</th>
                                            <th>Members</th>
                                            <th>Customer</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $pbx_list->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- add operator modal -->
            <div class="modal fade" id="add_ringgroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title">Add Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'add_ringgroup_form', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('id', '', array('id' =>'ringgroup_id')) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Customer *</label> 
                                         {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'groupid')) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">RingGroup Number * (should be unique, prefix XX[DID] number where XX can be 11,22,33,44)</label> 
                                         {!! Form::text('ringgroup', null, ['class' => 'form-control', 'id' => 'ringgroup']) !!}
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Description * (No space, Use underscore)</label> 
                                        {!! Form::text('description', null, ['class' => 'form-control', 'id' => 'description']) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Ring Time (Seconds MAX 300)</label> 
                                        {!! Form::number('grptime', null, ['class' => 'form-control', 'id' => 'grptime']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">RingGroup Members (List extensions to ring, one per line, or use the Extension Quick Select insert them here. You can include an extension on a remote system, or an external number by suffixing a number with a '#'. ex: 2448089#)</label> 
                                        {!! Form::textarea('members', null, ['class' => 'form-control', 'id' => 'members', 'rows' => 8, 'cols' => 10]) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Ring Statergy</label>
                                        {!! Form::select('strategy', ['ringall' => 'Ringall', 'ringall-prim' => 'Ringall Prim', 'hunt' => 'Hunt', 'hunt-prim' => 'Hunt Prim', 'Memoryhunt' => 'Memory Hunt', 'memoryhunt-prim' => 'memoryhunt Prim', 'firstavailable' => 'First Available', 'firstnotonphone' => 'First not on phone', 'random' => 'Random'], null,array('class' => 'form-control', 'id' => 'strategy')) !!} 
                                        <p></p>
                                        <p>ringall: Ring all available channels until one answers (default)</p>
                                        <p>hunt: Take turns ringing each available extension</p>
                                        <p>memoryhunt: Ring first extension in the list, then ring the 1st and 2nd extension, then ring 1st 2nd and 3rd extension in the list.... etc.</p>
                                        <p>*-prim: These modes act as described above. However, if the primary extension (first in list) is occupied, the other extensions will not be rung. If the primary is FreePBX DND, it wont be rung. If the primary is FreePBX CF unconditional, then all will be rung 
                                        firstavailable: ring only the first available channel</p>
                                        <p>firstnotonphone: ring only the first channel which is not offhook - ignore CW</p>
                                        <p>random: Makes a call could hop between the included extensions without a predefined priority to ensure that calls in the groups are (almost) evenly spread. Simulates a Queue when a Queue can not otherwise be used.<p>
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
        $( '.add_ringgroup_form' ).on( 'submit', function(e) {
            e.preventDefault();
            //var noteHTML = "";
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addRinggroup") }}', // This is the url we gave in the route
            data: $('.add_ringgroup_form').serialize(),
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
                    $("#add_ringgroup").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 3000);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.edit_ringgroup').on('click',function(e)
        {
            $("#modal-title").text('Edit Account');
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_pbx_ringgroup/'+ id, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                var res = result[0];
                $("#ringgroup_id").val(res.id);
                $("#groupid").val(res.groupid);
                $("#ringgroup").val(res.ringgroup);
                $("#description").val(res.description);
                $("#grptime").val(res.grptime);
                $("#members").val(res.members);
                $("#strategy").val(res.strategy);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });
    });
</script>
@endsection