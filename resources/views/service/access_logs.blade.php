@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
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


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    $('#access_log_table').DataTable();
</script>
@endsection
