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
                            <div class="card-title mb-3">Add User</div>
                            {!! Form::open(['action' => 'UserController@store', 'method' => 'post','autocomplete' => 'off']) !!} 
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Customer name</label>
                                        <input type="text" class="form-control" id="firstName1" placeholder="Customer name" name="customer_name">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('customer_name', ':message') : '' !!}</p>                                   
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                        <select class="form-control" name="coperate_id">
                                            <option value="">Select Coperate</option>
                                            <option value="test1">test 1</option>
                                            <option value="test2">test 2</option>
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('coperate_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">Start date</label>
                                        <div class="input-group">
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="start_date" >
                                           
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('start_date', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">End date</label>
                                        <div class="input-group">
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="end_date" >
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>  
                                        </div>
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('end_date', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Status</label>
                                        <select class="form-control" name="status">
                                            <option value="ACTIVE">Active</option>
                                            <option value="INACTIVE">InActive</option>   
                                        </select>
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">DID</label>
                                        <select class="form-control" name="did">
                                            <option value="ACTIVE">Active</option>
                                            <option value="INACTIVE">InActive</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Multilanguage</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="multilanguage" value="YES"> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="multilanguage" checked value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Language option</label>
                                        {!! Form::select('language', $lang, null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Record Call</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="record_call" checked value="YES"> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="record_call" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Operator Call Count</label>
                                        <input type="number" class="form-control" name="operator_call_count" placeholder="Operator Call Count">
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('operator_call_count', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        <input type="text" class="form-control" name="sms_api_user" placeholder="Sms api user">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_user', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        <input type="password" class="form-control" name="sms_api_password" placeholder="Sms api Password">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_password', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        <input type="text" class="form-control" name="sms_api_sender" placeholder="Sms api sender">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_sender', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                        <input type="text" class="form-control" name="api" placeholder="API">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('api', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                        <input type="text" class="form-control" name="cdr_api_key" placeholder="CDR API key">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('cdr_api_key', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                        <input type="text" class="form-control" name="client_ip" placeholder="Client IP">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('client_ip', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">CDR Tag</label>
                                        <select class="form-control" name="cdr_tag">
                                            <option value="ENABLED">Enabled</option>
                                            <option value="DISABLED">Disabled</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Log ChanUNavil calls</label>
                                        <select class="form-control" name="chanunavil_calls">
                                            <option value="ENABLED">Enabled</option>
                                            <option value="DISABLED">Disabled</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        <input type="number" class="form-control" name="conference_members" placeholder="Dialout conference members">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('conference_members', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Android APP</label>
                                        <select class="form-control" name="android_app">
                                            <option value="YES">Yes</option>  
                                            <option value="NO" selected="">No</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Web portal SMS</label>
                                        <select class="form-control" name="portal_sms">
                                            <option value="YES">Yes</option>  
                                            <option value="NO" selected="">No</option>  
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
                                            <label class="radio-inline"><input type="radio" name="sms_support" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="sms_support" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Push Api Service</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="push_api_service" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="push_api_service" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="pbx_extension" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="pbx_extension" value="NO"> No</label>
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

