@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<!-- <link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}"> -->
<style>
    .table-header-bg-color {
        background-color: #6633994f;
    }
</style>
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>{{$heading}}</h1>

</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_config" class="btn btn-primary" id="add_report_config"> Add</a>
                <div class="table-responsive">
                    <table id="report_config" class="display table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-header-bg-color">Customer</th>
                                <th class="table-header-bg-color">Time</th>
                                <th class="table-header-bg-color"></th>
                                <th class="table-header-bg-color">Email</th>
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
</div>

<!-- add operator modal -->
<div class="modal fade" id="add_config" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Config</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'add_config_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <input type="hidden" value="{{$type}}" name="type">
                <input type="hidden" value="0" name="id" id="id">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label class="filter-col" for="pref-perpage">Customer *</label>
                        <select name="groupid" class="form-control" id="groupid">
                            <option value="">All</option>
                            @if(!empty($customers))
                            @foreach($customers as $customer )
                            <option value="{{$customer->id}}">{{$customer->name}}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                @if($type == 'weekly')
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label class="filter-col" for="pref-perpage">Weeks *</label>
                        <select name="day" class="form-control" id="day">
                            @foreach($weeks as $key => $val )
                            <option value="{{$key}}">{{$val}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                @if($type == 'monthly')
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label class="filter-col" for="pref-perpage">Days *</label>
                        <select name="date" class="form-control" id="date">
                            @foreach($days as $key => $val )
                            <option value="{{$key}}">{{$val}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="time">Time (24 hour format) *</label>
                        <input type="text" class="form-control" id="time" name="time">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="email">Email *</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter the To Email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="subject">Email Subject *</label>
                        <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter the Subject">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="content">Email Content *</label>
                        <textarea name="content" id="content" class="form-control" rows="5" placeholder="Enter the Content"></textarea>
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
<!-- <script src="{{asset('assets/js/moment.min.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script> -->
<script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>
<script type="text/javascript">
    const dataTable = $('#report_config').DataTable({
        "order": [
            [0, "desc"]
        ],
        "searchDelay": 1000,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("reportConfigDataAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
                data.type = "{{$type}}";
            }
        },
        "headerCallback": function(thead, data, start, end, display) {
            if (['weekly', 'monthly'].includes('{{$type}}')) {
                let title = 'Date';
                if ('{{$type}}' == 'weekly') {
                    title = 'Day';
                }
                $(thead).find('th').eq(2).html(title);
            }
        },
        "columnDefs": [{
            targets: 2,
            visible: ['weekly', 'monthly'].includes('{{$type}}')
        }, ],
        "columns": [{
                "data": "customerName"
            },
            {
                "data": "time"
            },
            {
                "data": "dayOrDate"
            },
            {
                "data": "email"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    let htmlData = '<a href="#" data-toggle="modal" data-target="#add_config" class="text-success mr-2 edit_config" id="' + data.id + '">' +
                        '<i class="nav-icon i-Pen-2 font-weight-bold"></i>' +
                        '</a>' +
                        '<a href="javascript:void(0)" onClick="deleteItem(' + data.id + ', ' + "'{{$tableName}}'" + ')" class="text-danger mr-2">' +
                        '<i class="nav-icon i-Close-Window font-weight-bold"></i>' +
                        '</a>';
                    return htmlData;
                }
            }
        ]
    });

    function reloadDataTable() {
        dataTable.ajax.reload(null, false);
    }

    $('.add_config_form').on('submit', function(e) {
        e.preventDefault();
        var errors = '';
        $("#groupid").prop("disabled", false);
        $.ajax({
            type: "POST",
            url: '{{ URL::route("addReportConfig") }}', // This is the url we gave in the route
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
                    $("#add_config").modal('hide');
                    toastr.success(res.success);
                    reloadDataTable();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
        });
    });

    $(document).on('click', '.edit_config', function(e) {
        $("#exampleModalCenterTitle-2").text('Edit Config');
        var id = $(this).attr("id");
        $.ajax({
            type: "GET",
            url: '/get_report_config/' + id + '/' + '{{$type}}', // This is the url we gave in the route
            success: function(result) { // What to do if we succeed
                console.log(result);
                for (const [key, value] of Object.entries(result)) {
                    $("#" + key).val(value);
                }
                $("#groupid").prop("disabled", true);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        });
    });

    $('#add_report_config').click(function() {
        $(".add_config_form")[0].reset();
        $("#type").val('{{$type}}');
        $("#id").val('0');
        $("#groupid").prop("disabled", false);
    });
    
    $(document).ready(function() {
        $('#time').pickatime({
            format: 'HH:i:00',
            interval: 5
        });
    });
</script>
@endsection