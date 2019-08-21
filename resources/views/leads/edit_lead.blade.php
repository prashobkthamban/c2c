@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Lead </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit User</div>
                            {!! Form::model($lead_edit, ['method' => 'PATCH', 'route' => ['updateLead', $lead_edit->lead_id]]) !!}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName">Name</label>
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('name', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="phoneNumber">Phone Number</label>
                                        {!! Form::text('phone_number', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phone_number', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="email">Email</label>
                                        {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('email', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="dob">Date of birth</label>
                                        <div class="input-group">
                                            {!! Form::text('dob', null, ['class' => 'form-control datepicker']) !!}
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('dob', ':message') : '' !!}</p>
                                    </div>

                                   <div class="col-md-6 form-group mb-3">
                                        <label for="address">Address</label>
                                        {!! Form::textarea('address', null, ['class' => 'form-control']) !!}    
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('address', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="lead_status">Lead Status</label>
                                        {!! Form::select('lead_status', array('ACTIVE' => 'Active', 'INACTIVE' => 'InActive'), null,array('class' => 'form-control')) !!}    
                                    </div>
                                    
                                    
                                    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="lead_owner">Lead owner assign to</label>
                                        {!! Form::text('lead_owner', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('lead_owner', ':message') : '' !!}</p>
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

