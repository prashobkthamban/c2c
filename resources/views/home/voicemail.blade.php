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
                                        <th>Customer</th>
                                        <th>Dnid</th>
                                        <th>Caller</th>
                                        <th>Department</th>
                                        <th>Duration</th>
                                        <th>Date</th>
                                        <th>Actions</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                           {{ $row->name }}

                                        </td>
                                        <td>{{$row->dnid}}</td>
                                        <td>{{$row->callerid}}</td>
                                        <td>{{$row->departmentname}}</td>
                                        <td><a>{{$row->duration}}</a></td>
                                        <td>{{$row->datetime}}</td>
                                        <td>
                                            <a href="{{asset('voicefiles/'.$row->filename)}}" class="nav-icon i-Download" title="Download"></a>
                                            <a href="{{asset('voicefiles/'.$row->filename)}}" class="nav-icon i-Download" title="Play"></a>
                                        </td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Dnid</th>
                                        <th>Caller</th>
                                        <th>Department</th>
                                        <th>Duration</th>
                                        <th>Date</th>
                                        <th>Actions</th>
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
