@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>IVR Menu Manager</h1>

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
                                        <th>CoperateAcc</th>
                                        <th>IVR Level Name</th>
                                        <th>IVR Level</th>
                                        <th>IVR Options</th>
                                        <th>OperatorDept.</th>
                                        <th>Add Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$row->operator_dpt}}</td>
                                        <td>{{$row->adddate}}</td>
                                        <td>
                                            <button class="btn btn-warning">Edit</button>
                                            <button class="btn btn-danger">Remove</button>
                                        </td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Customer</th>
                                        <th>CoperateAcc</th>
                                        <th>IVR Level Name</th>
                                        <th>IVR Level</th>
                                        <th>IVR Options</th>
                                        <th>OperatorDept.</th>
                                        <th>Add Date</th>
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