@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<style>
    .table-header-bg-color {
        background-color: #6633994f;
    }
</style>
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>Access Logs</h1>

</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="access_log_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-header-bg-color">User Name</th>
                                <th class="table-header-bg-color">Password</th>
                                <th class="table-header-bg-color">User Type</th>
                                <th class="table-header-bg-color">Customer</th>
                                <th class="table-header-bg-color">Ip address</th>
                                <th class="table-header-bg-color">Status</th>
                                <th class="table-header-bg-color">Access Time</th>
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
@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    const dataTable = $('#access_log_table').DataTable({
        "order": [
            [6, "desc"]
        ],
        "searchDelay": 1000,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("accessLogsAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
            }
        },
        "columns": [{
                "data": "userName"
            },
            {
                "data": "password"
            },
            {
                "data": "userType"
            },
            {
                "data": "customerName"
            },
            {
                "data": "ipAddress"
            },
            {
                "data": "status"
            },
            {
                "data": "loginTime"
            }
        ]
    });

    function reloadDataTable() {
        dataTable.ajax.reload(null, false);
    }
</script>
@endsection