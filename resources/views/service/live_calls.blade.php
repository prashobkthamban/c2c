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
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>Customer</th>
                                        @endif
                                        <th>Callerid</th>
                                        <th>Call time</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Call status</th>
                                        <th>Dial Statergy</th>
                                        <th>Priority</th>
                                        <th>Duration</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                        $contactName = getConatctName($row->callerid);
                                        $fname = count($contactName) == null ? $row->callerid :  contactName[0]->fname;
                                    <tr>
                                        @if(Auth::user()->usertype == 'admin')
                                        <td>{{ $row->name }}</td>
                                        @endif
                                        <td>{{ $fname }}</td>
                                        <td>{{ $row->time }}</td>
                                        <td>{{ $row->dept_name }}</td>
                                        <td>{{ $row->opername }}</td>
                                        <td>{{ $row->call_status }}</td>
                                        <td>{{ $row->dial_statergy }}</td>
                                        <td>{{ $row->priority }}</td>
                                        <td>hjgh</td>
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>Customer</th>
                                        @endif
                                        <th>Callerid</th>
                                        <th>Call time</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Call status</th>
                                        <th>Dial Statergy</th>
                                        <th>Priority</th>
                                        <th>Duration</th>
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
