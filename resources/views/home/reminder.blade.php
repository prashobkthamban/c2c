@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>My Reminder </h1>

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
                                        <th>Caller</th>
                                        <th>Reminder Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Assigned to</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->fname ? $row->fname : $row->number }}
                                                @else
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->followupdate}}</td>
                                        <td>{{$row->secondleg}}</td>
                                        <td>{{$row->appoint_status}}</td>
                                        <td><a>{{$row->deptname}}</a></td>
                                        <td>{{$row->opername}}</td>
                                        <td>{{$row->assignedname}}</td>
                                        <td></td>


                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Caller</th>
                                        <th>DNID</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>
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
