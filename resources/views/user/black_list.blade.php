@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Blacklist </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="{{route('addBlackList')}}" class="btn btn-primary"> Add Blacklist </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Phone</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($blacklists as $blacklist)
                                        <tr>
                                            <td>{{$blacklist->name}}</td>
                                            <td>{{$blacklist->phone_number}}</td>
                                            <td>{{$blacklist->reason}}</td>
                                           
                                            <td><a href="{{ route('deleteBlacklist', $blacklist->id) }}" onclick="return confirm('You want to delete this blacklist?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tfoot>

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

@endsection

