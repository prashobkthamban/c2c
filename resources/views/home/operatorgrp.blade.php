@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Operator Account </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Operator</th>
                                        <th>Phone</th>
                                        <th>Loginid</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Exten</th>
                                        <th>Start time</th>
                                        <th>End time</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                           {{ $row->opername }}

                                        </td>
                                        <td>{{$row->phonenumber}}</td>
                                        <td>{{$row->login_username}}</td>
                                        <td>{{$row->password}}</td>
                                        <td><a>{{$row->oper_status}}</a></td>
                                        <td>{{$row->livetrasferid}}</td>
                                        <td>{{$row->start_work}}</td>
                                        <td>{{$row->end_work}}</td>


                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Operator</th>
                                        <th>Phone</th>
                                        <th>Loginid</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Exten</th>
                                        <th>Start time</th>
                                        <th>End time</th>
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



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection
