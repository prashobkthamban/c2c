@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> SMS Template </h1> 
    </div>
            <div class="separator-breadcrumb border-top"></div>

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" id="add_converted" href="{{route('TermsAndConditionAdd')}}" class="btn btn-primary"> Add T&C</a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Invoice Body</th>
                                            <th>Proposal Body</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @foreach($list_tc_invoices as $list_tc_invoice)
                                        <tr>
                                            <td>{{$list_tc_invoice->id}}</td>
                                            <td>{!!$list_tc_invoice->tc_inv_name!!}</td>
                                            <td>{!!$list_tc_invoice->tc_pro_name!!}</td>
                                            <td>
                                                <a href="{{ route('TermsAndConditionEdit', $list_tc_invoice->id) }}" class="text-success mr-2">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('TermsAndConditionDelete', $list_tc_invoice->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>  
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Invoice Body</th>
                                            <th>Proposal Body</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
            </div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>

@endsection
