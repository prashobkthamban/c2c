@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> NonOperator Department </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="{{route('addUser')}}" class="btn btn-primary"> Add New </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>CoperateName</th>
                                            <th>Depatment Name</th>
                                            <th>Sms to caller</th>
                                            <th>Sms to operator</th>
                                            <th>Operator no</th>
                                            <th>Datetime</th>
                                            <th>Action</th>
                                            <th>Upload File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($nonoperatordept as $listOne)
                                        <tr>
                                            <td>{{$listOne->name}}</td>
                                            <td>{{$listOne->resellername}}</td>
                                            <td>@if($listOne->dept_name == '') 
                                                {{'MissCall'}}
                                                @else
                                                {{$listOne->dept_name}}
                                                @endif
                                            </td>
                                            <td>{{$listOne->sms_to_caller}}</td>
                                            <td>{{$listOne->sms_to_operator}}</td>
                                            <td>{{$listOne->operator_no}}</td>
                                            <td>{{$listOne->adddate}}</td>
                                            <td><a href="{{ route('editUser', $listOne->id) }}" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteUser', $listOne->id) }}" onclick="return confirm('You want to delete this user?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                            <td><i class="nav-icon i-Upload1 font-weight-bold"></i></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Customer</th>
                                            <th>CoperateName</th>
                                            <th>Depatment Name</th>
                                            <th>Sms to caller</th>
                                            <th>Sms to operator</th>
                                            <th>Operator no</th>
                                            <th>Datetime</th>
                                            <th>Action</th>
                                            <th>Upload File</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $nonoperatordept->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection

