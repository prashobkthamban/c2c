@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> CRM Status </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" class="btn btn-primary"> Add CRM Status </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>CRM Status Name</th>
                                            <th>Staus</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($crmStatus as $status)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$status->crm_status_name}}</td>
                                            <td>{{ ($status->crm_status_active == 1) ? 'Active' : 'Inactive' }}</td>
                                            <td><a href="{{ route('editDid', $status->id) }}" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteDid', $status->id) }}" onclick="return confirm('Are you sure you want to delete this Did?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
             <!-- end of row -->


@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection

