@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>Access Logs</h1>

</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div id="filter-panel" class="col-lg-12 col-md-12 filter-panel collapse {{count($requests) > 0 ? 'show' : ''}}">
        <div class="card mb-2">
            <div class="card-body">
                <div>
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="cdr_filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            <div class="col-md-4" id="customer_div">
                                <label class="filter-col" for="pref-perpage">Customers</label>
                                <select name="customer" class="form-control" id="customer_id">
                                    <option value="">All</option>
                                    @if(!empty($customers))
                                    @foreach($customers as $customer )
                                    <option value="{{$customer->id}}" @if(isset($requests['customer']) && $customer->id == $requests['customer']) selected @endif>{{$customer->name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top: 24px;">
                            <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                            <a href="{{url('access_logs')}}" class="btn btn-outline-secondary" name="btn">Clear</a>
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
                <div class="table-responsive">
                    <table id="access_log_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Usertype</th>
                                <th>Customer</th>
                                <th>Ip address</th>
                                <th>Status</th>
                                <th>Access Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($result))
                            @foreach($result as $row )
                            <tr>
                                <td>{{ $row->username }}</td>
                                <td>{{ $row->password }}</td>
                                <td>{{ ($row->usertype == 'reseller') ? 'Coperate Admin' : (($row->usertype =='admin') ? 'Super Admin' : (!empty($row->usertype) ? $row->usertype : "")) }}</td>
                                <td>{{ ($row->usertype == 'reseller') ? 'Coperate Admin' : (($row->usertype =='admin') ? 'Super Admin' : (!empty($row->usertype) ? $row->name : "")) }}</td>
                                <td>{{ $row->ipaddress }}</td>
                                <td>{{ $row->status }}</td>
                                <td>{{ $row->login_time }}</td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Usertype</th>
                                <th>Customer</th>
                                <th>Ip address</th>
                                <th>Status</th>
                                <th>Access Time</th>
                            </tr>

                        </tfoot>

                    </table>
                </div>

            </div>
            <div class="pull-right"></div>
        </div>
    </div>
    <!-- end of col -->

</div>
<!-- end of row -->

<div class="customizer" title="Search" style="top:73px">
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
<script type="text/javascript">
    $('#access_log_table').DataTable({
        searching: false
    });
</script>
@endsection