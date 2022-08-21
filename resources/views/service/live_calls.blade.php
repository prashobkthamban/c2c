@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>Live Calls</h1>

</div>
<div class="separator-breadcrumb border-top"></div>


<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="live_calls_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                                <th>Customer</th>
                                @endif
                                <th>Callerid</th>
                                <th>Call time</th>
                                <th>DID Number</th>
                                <th>Department</th>
                                <th>Operator</th>
                                @if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'reseller')
                                <th>Webhook Link</th>
                                @endif
                                <th>Call status</th>
                                <th>Dial Statergy</th>
                                <th>Duration</th>
                                @if(Auth::user()->usertype == 'groupadmin')
                                <th>Listen</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($result))
                            @foreach($result as $row )
                            <?php
                            $contactName = getConatctName($row->callerid);
                            date_default_timezone_set('Asia/Kolkata');
                            $datetime1 = new DateTime();
                            $datetime2 = new DateTime($row->status_change_time);
                            $interval = $datetime1->diff($datetime2);
                            $fname = count($contactName) == null ? $row->callerid :  $contactName[0]->fname;
                            ?>
                            <tr>
                                @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                                <td>{{ $row->name }}</td>
                                @endif
                                <td>{{ $fname }}</td>
                                <td>{{ $row->time }}</td>
                                <td>{{ $row->DID }}</td>
                                <td>{{ $row->dept_name }}</td>
                                <td>{{ $row->opername }}</td>
                                @if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'reseller')
                                <td style="text-align: center;">
                                    @if(isset($row->apitype) && $row->apitype == 'webhook')
                                        <a href="{{str_replace('{CALLERID}', substr($row->callerid, -10), $row->api)}}" target="_blank">
                                            <i class="i-Link-2"></i>
                                        </a>
                                    @endif
                                </td>
                                @endif
                                <td>{{ $row->call_status }}</td>
                                <td>{{ $row->dial_statergy }}</td>
                                <td>{{ $interval->format('%H:%i:%s') }}</td>
                                @if(Auth::user()->usertype == 'groupadmin')
                                <td style="text-align: center;"><i class="i-Headphone listen-live-call" data-toggle="modal" data-target="#listen_modal" data-id="{{$row->id}}"></i></td>
                                @endif
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                                <th>Customer</th>
                                @endif
                                <th>Callerid</th>
                                <th>Call time</th>
                                <th>DID Number</th>
                                <th>Department</th>
                                <th>Operator</th>
                                @if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'reseller')
                                <th>Webhook Link</th>
                                @endif
                                <th>Call status</th>
                                <th>Dial Statergy</th>
                                <th>Duration</th>
                                @if(Auth::user()->usertype == 'groupadmin')
                                <th>Listen</th>
                                @endif
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

<!-- listen modal -->
<div class="modal fade" id="listen_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Listen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method' => 'post', 'id' => 'listen_form']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <input type="hidden" name="cur_channel_used_id" id="cur_channel_used_id" value="0">
                    <div class="col-md-8 form-group mb-3">
                        <label for="number">Listen from Number</label>
                        <input type="number" id="customer_number" onpaste="return false;" class="form-control" placeholder="Customer Number" name="number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <!-- <label for="firstName1">Option</label> -->
                        <label class="radio-inline"> {{ Form::radio('option', 'Sw') }} Whisper Mode</label>
                        <label class="radio-inline">{{ Form::radio('option', 'bs', true) }} Listen</label>
                        <label class="radio-inline">{{ Form::radio('option', 'BS') }} BargeIn</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Dialing</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- end of listen modal -->


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
    reloadPage();

    function reloadPage() {
        setTimeout(function() {
            console.log("time out");
            console.log(($("#listen_modal").data('bs.modal') || {
                _isShown: false
            })._isShown);
            if (!($("#listen_modal").data('bs.modal') || {
                    _isShown: false
                })._isShown) {
                location.reload();
            } else {
                reloadPage();
            }
        }, 5000)
    }

    $(document).on('click', '.listen-live-call', function() {
        $("#cur_channel_used_id").val($(this).data('id'));
    });

    $('#listen_form').on('submit', function(e) {
        e.preventDefault();
        var errors = '';
        $.ajax({
            type: "POST",
            url: '{{ URL::route("listenToLiveCall") }}', // This is the url we gave in the route
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            success: function(res) { // What to do if we succeed
                if (res.error) {
                    $.each(res.error, function(index, value) {
                        if (value.length != 0) {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    $("#listen_modal").modal('hide');
                    toastr.success(res.success);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
        });
    });
</script>
@endsection