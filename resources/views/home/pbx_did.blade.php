@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> PBX DIDs </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_did" class="btn btn-primary new_did"> Add New </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>DID</th>
                                            <th>OUT Dpt Name</th>
                                            <th>IN Dpt Name</th>
                                            <th>Customer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($pbx_did as $listOne)
                                        <tr>
                                            <td>{{$listOne->did}}</td>
                                            <td>{{$listOne->outdptname}}</td>
                                            <td>{{$listOne->indptname}}</td>
                                            <td>{{$listOne->name}}</td>
                                            <td><a href="#" data-toggle="modal" data-target="#add_did" class="text-success mr-2 edit_did" id="{{$listOne->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deletePbxdid', $listOne->id) }}" onclick="return confirm('Are you sure want to delete this record ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>DID</th>
                                            <th>OUT Dpt Name</th>
                                            <th>IN Dpt Name</th>
                                            <th>Customer</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $pbx_did->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- add operator modal -->
            <div class="modal fade" id="add_did" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pbx_did_title">Add Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'add_did_form', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('id', '', array('id' =>'did_id')) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Customer *</label> 
                                         {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'onChange' => 'setOptions()', 'id' => 'groupid')) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Inbound DNID *</label> 
                                         {!! Form::text('did', null, ['class' => 'form-control', 'id' => 'did']) !!}
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Incoming Destination</label> 
                                        {!! Form::select('destination', ['ringgroup' => 'RingGroup', 'extension' => 'Direct Extension'], null,array('class' => 'form-control', 'id' => 'destination')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Extension for direct Transfer</label> 
                                        {!! Form::select('dest_num', ['' => 'Select Extension'], null,array('class' => 'form-control', 'id' => 'dest_num')) !!}

                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">RingGroups</label> 
                                        {!! Form::select('rrnumber', ['' => 'Select RingGroup'], null,array('class' => 'form-control', 'id' => 'rrnumber')) !!}

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Out Department Name *</label> 
                                         {!! Form::text('outdptname', null, ['class' => 'form-control', 'id' => 'outdptname']) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Inbound Department Name *</label> 
                                         {!! Form::text('indptname', null, ['class' => 'form-control', 'id' => 'indptname']) !!}
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
    function setOptions(groupid = null, dest_num = null) {
        if(groupid != null) {
            var groupid = $("#groupid").val();
        }

        $.ajax({
            type: "GET",
            url: '/get_options/admin/'+ groupid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                $('#dest_num').empty();
                $('#rrnumber').empty();
                $('#dest_num').append('<option value="">Select Extension</option>');
                $('#rrnumber').append('<option value="">Select RingGroup</option>');
                $.each(res.extensions, function (i, item) {   
                    if(dest_num !== null && dest_num == item.extension) {
                        $('#dest_num').append('<option value="'+ item.extension +'" selected>'+ item.extension +'</option>');
                    } else {
                       $('#dest_num').append('<option value="'+ item.extension +'">'+ item.extension +'</option>'); 
                    }
                });
                $.each(res.ringgroups, function (i, item) { 
                    if(dest_num !== null && dest_num == item.ringgroup) {  
                        $('#rrnumber').append('<option value="'+ item.ringgroup +'" selected>'+ item.description +'</option>'); 
                    } else {
                        $('#rrnumber').append('<option value="'+ item.ringgroup +'">'+ item.description +'</option>'); 
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        }); 
    }

    $(document).ready(function() {
        $( '.add_did_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addPbxDid") }}', // This is the url we gave in the route
            data: $('.add_did_form').serialize(),
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
                    $("#add_did").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 2000);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.edit_did').on('click',function(e)
        {
            var id = $(this).attr("id");
            $("#pbx_did_title").text('Edit Account');
            $.ajax({
            type: "GET",
            url: '/get_pbx_did/'+ id, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                var res = result[0];
                // console.log(result)
                $("#did_id").val(res.id);
                $("#groupid").val(res.groupid);
                $("#did").val(res.did);
                setOptions(res.groupid, res.dest_num);
                $("#destination").val(res.destination);
                $("#outdptname").val(res.outdptname);
                $("#indptname").val(res.indptname);
                $("#dest_num").val(res.dest_num);
                $("#rrnumber").val(res.dest_num);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('.new_did').click(function() {
            $(".add_did_form").trigger("reset");
            $("#pbx_did_title").text('Add Account');
        });
    });
</script>
@endsection