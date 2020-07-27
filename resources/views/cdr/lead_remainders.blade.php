@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Lead Reminders </h1> 
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
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Reminder Task</th>
                                            <th>Date and Time</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Phoneno</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @foreach($show_all_remainder as $remainders)
                                        <tr>
                                            <td>{{$remainders->id}}</td>
                                            <td>{{$remainders->title}}</td>
                                            <td>{{$remainders->task}}</td>
                                            <td>{{$remainders->date.' '.$remainders->time}}</td>
                                            <td>{{$remainders->first_name}}</td>
                                            <td>{{$remainders->last_name}}</td>
                                            <td>{{$remainders->company_name}}</td>
                                            <td>{{$remainders->email}}</td>
                                            <?php
                                            if ($remainders->phoneno == '0') { ?>
                                               <td></td>
                                            <?php }
                                            else
                                            { ?>
                                                <td>{{$remainders->phoneno}}</td>
                                            <?php }
                                            ?>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Reminder Task</th>
                                            <th>Date and Time</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Phoneno</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <div class="pull-right">{{ $show_all_remainder->links() }}</div>
                    </div>
                </div>
            </div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>

@endsection
