@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}"><style>
    .table-header-bg-color {
        background-color: #6633994f;
    }
    .align-center {
        text-align: center;
    }
</style>
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
                                <th class="table-header-bg-color">Customer</th>
                                <th>Caller ID</th>
                                <th class="table-header-bg-color">Call Time</th>
                                <th class="table-header-bg-color">DID Number</th>
                                <th class="table-header-bg-color">Department</th>
                                <th class="table-header-bg-color">Operator</th>
                                <th>Webhook Link</th>
                                <th class="table-header-bg-color">Call status</th>
                                <th class="table-header-bg-color">Dial Statergy</th>
                                <th>Duration</th>
                                <th>Listen</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
    const dataTable = $('#live_calls_table').DataTable({
        "order": [[0, "desc" ]],
        "searchDelay": 1000,
        "pageLength": 50,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("liveCallDataAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
            }
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).attr('id', data.id);
            $(row).find('td [target="_blank"]').parent('td').addClass('align-center');
            $(row).find('.listen-live-call').parent('td').addClass('align-center');
        },
        "columnDefs": [
            { targets: 0, visible: ['admin', 'reseller'].includes('{{Auth::user()->usertype}}') },
            { targets: 6, visible: ['groupadmin', 'reseller'].includes('{{Auth::user()->usertype}}') },
            { targets: 10, visible: ['groupadmin'].includes('{{Auth::user()->usertype}}') }
        ],
        "columns": [
            {
                "data": "customerName"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    return data.firstName;
                }
            },
            {
                "data": "callTime"
            },
            {
                "data": "didNumber"
            },
            {
                "data": "departmentName"
            },
            {
                "data": "operatorName"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    let htmlData = '';
                    if (data.webHookLink) {
                        htmlData = '<a href="' + data.webHookLink + '" target="_blank">' +
                                    '<i class="i-Link-2"></i>' +
                                    '</a>';
                    }
                    return htmlData;
                }
            },
            {
                "data": "callStatus"
            },
            {
                "data": "dialStatergy"
            },
            {
                "data": "duration"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    let htmlData = '<i class="i-Headphone listen-live-call" data-toggle="modal" data-target="#listen_modal" data-id="' + data.id + '"></i>';
                    return htmlData;
                }
            }
        ]
    });

    reloadPage();

    function reloadPage() {
        setTimeout(function() {
            if (!($("#listen_modal").data('bs.modal') || {
                    _isShown: false
                })._isShown) {
                dataTable.ajax.reload(null, false);
            }
            reloadPage();
        }, 10000)
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