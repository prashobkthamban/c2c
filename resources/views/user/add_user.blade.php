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
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Customer name</label>
                                        <input type="text" class="form-control" id="firstName1" placeholder="Customer name" name="name">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                        {!! Form::select('resellerid', $coperate, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('resellerid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">Start date</label>
                                        <div class="input-group">
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="startdate" >
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('startdate', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">End date</label>
                                        <div class="input-group">
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="enddate" >
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>  
                                        </div>
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('enddate', ':message') : '' !!}</p>
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
                                        {!! Form::select('did', $did_list, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('did', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Multilanguage</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="multi_lang" value="YES"> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="multi_lang" checked value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Language option</label>
                                        {!! Form::select('lang_file', $lang, null,array('class' => 'form-control')) !!}
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
                                        <input type="number" class="form-control" name="try_count" placeholder="Operator Call Count">
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('try_count', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialtime</label>
                                        <input type="number" class="form-control" name="dial_time" placeholder="In Seconds">
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('dial_time', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Call Duration</label>
                                        <input type="number" class="form-control" name="maxcall_dur" placeholder="In Seconds">
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('maxcall_dur', ':message') : '' !!}</p>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Web Logins</label>
                                        <input type="number" class="form-control" name="operator_no_logins" placeholder="In Seconds">
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('max_call', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Number of Channels</label>
                                        <select class="form-control" name="no_channels">
                                            <option value="0">0</option>
                                            <option value="1">1</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign Email</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="emailservice_assign_cdr" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="emailservice_assign_cdr" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign SMS</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="smsservice_assign_cdr" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="smsservice_assign_cdr" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Click2Call</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="c2c" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="c2c" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Click2Call Channels</label>
                                        <input type="text" class="form-control" name="c2c_channels" placeholder="Click2Call Channels">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('c2c_channels', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Clik2CallAPI</label>
                                        <input type="text" class="form-control" name="c2cAPI" placeholder="Clik2CallAPI">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('c2cAPI', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Direct Trasfer</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="operator_dpt" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="operator_dpt" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Sms Gateway</label>
                                        {!! Form::select('sms_api_gateway_id', $sms_gateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('sms_api_gateway_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        <input type="text" class="form-control" name="sms_api_user" placeholder="Sms api user">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_user', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        <input type="password" class="form-control" name="sms_api_pass" placeholder="Sms api Password">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_pass', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        <input type="text" class="form-control" name="sender" placeholder="Sms api sender">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sender', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                        <input type="text" class="form-control" name="API" placeholder="API">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('API', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                        <input type="text" class="form-control" name="cdr_apikey" placeholder="CDR API key">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('cdr_apikey', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                        <input type="text" class="form-control" name="ip" placeholder="Client IP">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('ip', ':message') : '' !!}</p>
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
                                        <select class="form-control" name="cdr_chnunavil_log">
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>   
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        <input type="number" class="form-control" name="max_no_confrence" placeholder="Dialout conference members">
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('max_no_confrence', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Service Type</label>
                                        <select class="form-control" name="servicetype">
                                            <option value="call">Callback</option>  
                                            <option value="callback">CAll</option>  
                                            <option value="misscall">Misscall</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Android APP</label>
                                        <select class="form-control" name="andriodapp">
                                            <option value="YES">Yes</option>  
                                            <option value="NO" selected="">No</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Web portal SMS</label>
                                        <select class="form-control" name="web_sms">
                                            <option value="YES">Yes</option>  
                                            <option value="NO" selected="">No</option>  
                                        </select>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Dial stratergy Type</label>
                                        <select class="form-control" name="dial_statergy">
                                            <option value="new">New</option>  
                                            <option value="old">Old</option>  
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
                                            <label class="radio-inline"><input type="radio" name="pushapi" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="pushapi" value="NO"> No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline"><input type="radio" name="pbxexten" value="YES" checked> Yes</label>
                                            <label class="radio-inline"><input type="radio" name="pbxexten" value="NO"> No</label>
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

