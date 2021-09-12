@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1> User </h1>

</div>
<div class="separator-breadcrumb border-top"></div>


<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <a title="Compact Sidebar" href="{{route('addUser')}}" class="btn btn-primary"> Add User </a>
                <div class="table-responsive">
                    <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                @if(Auth::user()->usertype == 'admin')
                                <th>ID</th>
                                @endif
                                <th>Customer name</th>
                                <th>Coperate name</th>
                                <th>Start Date</th>
                                <th>End date</th>
                                @if(Auth::user()->usertype == 'admin')
                                <th>Channels</th>
                                <th>SMS</th>
                                <th>DT</th>
                                <th>Multi-Lang</th>
                                @endif
                                <th>Did</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($users as $user)
                            <tr id="row_{{ $user->id }}">
                                @if(Auth::user()->usertype == 'admin')
                                <td>{{$user->id}}</td>
                                @endif
                                <td>{{$user->name}}</td>
                                <td>{{$user->resellername}}</td>
                                <td>{{ date('d-m-Y', strtotime($user->startdate)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($user->enddate)) }}</td>
                                @if(Auth::user()->usertype == 'admin')
                                <td>{{$user->c2c_channels}}</td>
                                <td>{{$user->sms_support}}</td>
                                <td>{{$user->operator_dpt}}</td>
                                <td>{{$user->multi_lang}}</td>
                                @endif
                                <td>{{$user->did}}</td>
                                <td>{{ date('d-m-Y', strtotime($user->created_at)) }}</td>
                                <td>{{$user->status}}</td>
                                <td>
                                    <!-- <a href="{{ route('editUserSettings', $user->id) }}" class="text-success mr-2" title="IVR User Permisions">
							                    <i class="nav-icon i-Pen-2 "></i></a> -->
                                    <a href="{{ route('editUser', $user->id) }}" class="text-success mr-2" title=" Edit User">
                                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    </a><a href="javascript:void(0)" onClick="deleteItem({{$user->id}}, 'accountgroup')" class="text-danger mr-2">
                                        <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                @if(Auth::user()->usertype == 'admin')
                                <th>ID</th>
                                @endif
                                <th>Customer name</th>
                                <th>Coperate name</th>
                                <th>Start Date</th>
                                <th>End date</th>
                                @if(Auth::user()->usertype == 'admin')
                                <th>Channels</th>
                                <th>SMS</th>
                                <th>DT</th>
                                <th>Multi-Lang</th>
                                @endif
                                <th>Did</th>
                                <th>created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
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