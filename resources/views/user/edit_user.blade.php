@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> User </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit User</div>
                            {!! Form::model($user, ['method' => 'PATCH', 'route' => ['updateUser', $user->id]]) !!}
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('customer_name', 'Customer name') !!}
                                        {!! Form::text('customer_name', null, ['class' => 'form-control']) !!}
                                       <!--  <input type="text" class="form-control" id="firstName1" placeholder="Customer name" name="customer_name"> -->
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                        <select class="form-control" name="coperate_id">
                                            <option value="0">Select Coperate</option>
                                            <option value="tst1">Option 1</option>
                                            <option value="tst2">Option 2</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">Start date</label>
                                        <div class="input-group">
                                             {!! Form::text('start_date', null, ['class' => 'form-control datepicker']) !!}
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">End date</label>
                                        <div class="input-group">
                                            {!! Form::text('end_date', null, ['class' => 'form-control datepicker']) !!} 
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>   
                                        </select>
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">DID</label>
                                        <select class="form-control" name="did">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Multilanguage</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="multilanguage" value="1"> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="multilanguage" checked value="0"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Language option</label>
                                         {!! Form::text('language', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Record Call</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="record_call" checked value="1"> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="record_call" value="0"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Operator Call Count</label>
                                        {!! Form::text('operator_call_count', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        {!! Form::text('sms_api_user', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        {!! Form::password('sms_api_user', ['class' => 'form-control']) !!} 
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        {!! Form::text('sms_api_sender', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                         {!! Form::text('api', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                         {!! Form::text('cdr_api_key', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                         {!! Form::text('client_ip', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">CDR Tag</label>
                                        <select class="form-control" name="cdr_tag">
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Log ChanUNavil calls</label>
                                        <select class="form-control" name="chanunavil_calls">
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        {!! Form::text('conference_members', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Android APP</label>
                                        <select class="form-control" name="android_app">
                                            <option value="1">Yes</option>  
                                            <option value="0" selected="">No</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Web portal SMS</label>
                                        <select class="form-control" name="portal_sms">
                                            <option value="1">Yes</option>  
                                            <option value="0" selected="">No</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Dial stratergy Type</label>
                                        <select class="form-control" name="dial_stratergy">
                                            <option value="1">New</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">SMS Support</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="sms_support" value="1" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="sms_support" value="0"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Push Api Service</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="push_api_service" value="1" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="push_api_service" value="0"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="pbx_extension" value="1" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="pbx_extension" value="0"> No</label>
                                        </div>
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

