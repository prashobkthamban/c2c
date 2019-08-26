@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> VoiceMails </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <!-- <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#holiday_modal" class="btn btn-primary"> Add Holiday </a> -->
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Dnid</th>
                                            <th>Caller</th>
                                            <th>Department</th>
                                            <th>Duration</th>
                                            <th>Datetime</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                        @foreach($voicemails as $voicemail)
                                        <tr>
                                            <td>{{$voicemail->name}}</td>
                                            <td>{{$voicemail->dnid}}</td>
                                            <td>{{$voicemail->callerid}}</td>
                                            <td>{{$voicemail->departmentname}}</td>
                                            <td>{{$voicemail->duration}}</td>
                                            <td>{{$voicemail->datetime}}</td>
                                            <td><i class="nav-icon i-Download1 font-weight-bold"></i>
                                                <i class="nav-icon i-Play-Music font-weight-bold"></i></td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Dnid</th>
                                            <th>Caller</th>
                                            <th>Department</th>
                                            <th>Duration</th>
                                            <th>Datetime</th>
                                            <th>Actions</th>
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

