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
                            {!! Form::open(['action' => 'UserController@store', 'method' => 'post']) !!} 
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Customer name</label>
                                        <input type="text" class="form-control" id="firstName1" placeholder="Customer name" name="customer_name">
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
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="start_date" >
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
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="end_date" >
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
                                        <input type="text" class="form-control" name="language" placeholder="Language option">
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
                                        <input type="text" class="form-control" name="operator_call_count" placeholder="Operator Call Count">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        <input type="text" class="form-control" name="sms_api_user" placeholder="Sms api user">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        <input type="password" class="form-control" name="sms_api_password" placeholder="Sms api Password">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        <input type="text" class="form-control" name="sms_api_sender" placeholder="Sms api sender">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                        <input type="text" class="form-control" name="api" placeholder="API">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                        <input type="text" class="form-control" name="cdr_api_key" placeholder="CDR API key">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                        <input type="text" class="form-control" name="client_ip" placeholder="Client IP">
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
                                        <input type="text" class="form-control" name="conference_members" placeholder="Dialout conference members">
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

