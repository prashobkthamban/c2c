@extends('layouts.master')
@section('page-css')
<style>
.table-header-bg-color {
    background-color: #6633994f;
}
</style>
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1> User </h1>

</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div id="filter-panel" class="col-lg-12 col-md-12 filter-panel collapse">
        <div class="card mb-2">
            <div class="card-body">
                <div>
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="user_filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">SMS</label>
                                {!! Form::select('sms_support', array('' => 'All', 'Yes' => 'Yes', 'No' => 'No'), isset($requests['sms_support']) ? $requests['sms_support'] : '',array('class' => 'form-control', 'id' => 'sms_support')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">DT</label>
                                {!! Form::select('operator_dpt', array('' => 'All', 'Yes' => 'Yes', 'No' => 'No'), isset($requests['operator_dpt']) ? $requests['operator_dpt'] : '',array('class' => 'form-control', 'id' => 'operator_dpt')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Status</label>
                                {!! Form::select('status', array('' => 'All', 'ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'), isset($requests['status']) ? $requests['status'] : '',array('class' => 'form-control', 'id' => 'status')) !!}
                            </div>
                            <div class="col-md-6" style="margin-top: 24px;">
                                <button type="button" id="search_btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                <button type="button" id="clear_btn" class="btn btn-outline-secondary" name="clear_btn">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <a title="Compact Sidebar" href="{{route('addUser')}}" class="btn btn-primary"> Add User </a>
                <div class="table-responsive">
                    <table class="display table table-striped table-bordered" id="user_list_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="table-header-bg-color">Customer name</th>
                                <th class="table-header-bg-color">Coperate name</th>
                                <th>Start Date</th>
                                <th>End date</th>
                                <th>Channels</th>
                                <th>SMS</th>
                                <th>DT</th>
                                <th>Multi-Lang</th>
                                <th class="table-header-bg-color">Did</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th class="table-header-bg-color">Customer name</th>
                                <th class="table-header-bg-color">Coperate name</th>
                                <th>Start Date</th>
                                <th>End date</th>
                                <th>Channels</th>
                                <th>SMS</th>
                                <th>DT</th>
                                <th>Multi-Lang</th>
                                <th class="table-header-bg-color">Did</th>
                                <th>created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="customizer" title="Search" style="top:75px">
    <a href="#" data-toggle="collapse" data-target="#filter-panel">
        <div class="handle collapsed">
            <i class="i-Search-People"></i>
        </div>
    </a>
</div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>
    const dataTable = $('#user_list_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        "searchDelay": 1000,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("userDataAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
                data.sms_support = $("#sms_support").val();
                data.operator_dpt = $("#operator_dpt").val();
                data.status = $("#status").val();
            }
        },
        "columns": [
            {
                "data": "id"
            },
            {
                "data": "name"
            },
            {
                "data": "resellername"
            },
            {
                "data": "startdate"
            },
            {
                "data": "enddate"
            },
            {
                "data": "c2c_channels"
            },
            {
                "data": "sms_support"
            },
            {
                "data": "operator_dpt"
            },
            {
                "data": "multi_lang"
            },
            {
                "data": "did"
            },
            {
                "data": "created_at"
            },
            {
                "data": "status"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    let htmlData = '<a href="edit/' + data.id + '" class="text-success mr-2" title=" Edit User">' +
                        '<i class="nav-icon i-Pen-2 font-weight-bold"></i>' +
                        '</a>' +
                        '<a href="javascript:void(0)" onClick="deleteItem(' + data.id + ', ' + "'accountgroup'" + ')" class="text-danger mr-2">' +
                        '<i class="nav-icon i-Close-Window font-weight-bold"></i>' +
                        '</a>';
                    return htmlData;
                }
            }
        ]
    });

    $("#search_btn").on("click", function() {
        dataTable.draw();
    })

    $("#clear_btn").on("click", function() {
        $("#user_filter_form")[0].reset();
        dataTable.draw();
    });
</script>

@endsection