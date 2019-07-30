@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Blacklist </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Blacklist</div>
                            {!! Form::open(['action' => 'UserController@storeBlacklist', 'method' => 'post','autocomplete' => 'off']) !!} 
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Customer</label>
                                         {!! Form::select('groupid', $customer, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('groupid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Phone number</label>
                                         <input type="text" class="form-control" placeholder="Phone number" name="phone_number">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phone_number', ':message') : '' !!}</p>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Reason</label>
                                        <textarea rows="8" cols="20" class="form-control" placeholder="Reason" name="reason"></textarea>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('reason', ':message') : '' !!}</p>
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
            <!-- end of row -->



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection

