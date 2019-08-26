@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Holiday </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Holiday</div>
                            {!! Form::open(['action' => 'ManagementController@holidayStore', 'method' => 'post']) !!} 
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Mobile/DID</label>
                                        <input type="text" class="form-control" placeholder="Mobile Number" name="rdins">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('rdins', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">DID Number</label>
                                        <input type="text" class="form-control" placeholder="Did Number" name="did">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('did', ':message') : '' !!}</p>
                                    </div>

                                   
                                    <div class="col-md-12">
                                         <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of row1 -->



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection

