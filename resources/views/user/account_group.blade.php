@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1> Account Groups </h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <p><center><b id="crm_error" style="display:none;color: red;">CRM Access Limit is over.Please contact to Administration!!!</b></center></p>
                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Did</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result as $row)
                                <tr id="row_{{ $row->id }}">
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->did}}</td>
                                    <td>{{$row->startdate}}</td>
                                    <td>{{$row->enddate}}</td>
                                    <td>{{$row->status}}</td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Account Name</th>
                                    <th>Did</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="pull-right">{{ $result->links() }}</div>
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

