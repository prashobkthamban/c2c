@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Did </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="{{route('addDid')}}" class="btn btn-primary"> Add Did </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Mobile No</th>
                                            <th>Did No</th>
                                            <th>PRI gateway</th>
                                            <th>Assigned to</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($dids as $did)
                                        <tr>
                                            <td>{{$did->outgoing_callerid}}</td>
                                            <td>{{$did->did}}</td>
                                            <td>{{$did->gatewayid}}</td>
                                            <td>{{$did->assignedto}}</td>
                                            <td><a href="{{ route('editDid', $did->id) }}" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteDid', $did->id) }}" onclick="return confirm('Are you sure you want to delete this Did?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Mobile No</th>
                                            <th>Did No</th>
                                            <th>PRI gateway</th>
                                            <th>Assigned to</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

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

