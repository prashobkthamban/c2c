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
                            {!! Form::model($user_edit, ['method' => 'PATCH', 'route' => ['updateUser', $user_edit->id]]) !!}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('name', 'Customer name') !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                         {!! Form::select('resellerid', $coperate, null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">Start date</label>
                                        <div class="input-group">
                                             {!! Form::text('startdate', null, ['class' => 'form-control datepicker']) !!}
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
                                            {!! Form::text('enddate', null, ['class' => 'form-control datepicker']) !!} 
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
                                        {!! Form::select('status', array('ACTIVE' => 'Active', 'INACTIVE' => 'InActive'), null,array('class' => 'form-control')) !!}                            
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">DID</label>
                                        {!! Form::select('did', $did_list, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('did', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Multilanguage</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('multi_lang', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('multi_lang', 'No') }} No   
                                            </label>
                                        </div>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('name', 'Language option') !!}
                                        {!! Form::text('lang_file', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('lang_file', ':message') : '' !!}</p>
                                        <span><b>separeted by :</b> eg:->gu:en:hi:te:ma</span>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Record Call</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('record_call', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('record_call', 'No') }} No  
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Operator Call Count</label>
                                        {!! Form::text('try_count', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('try_count', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialtime</label>
                                        {!! Form::text('dial_time', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('dial_time', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Call Duration</label>
                                        {!! Form::text('maxcall_dur', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('maxcall_dur', ':message') : '' !!}</p>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Web Logins</label>
                                         {!! Form::text('operator_no_logins', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('operator_no_logins', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Number of Channels</label>
                                        {!! Form::select('no_channels', array('0' => '0', '1' => '1'), null,array('class' => 'form-control')) !!}          
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign Email</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('emailservice_assign_cdr', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('emailservice_assign_cdr', 'No') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign SMS</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('smsservice_assign_cdr', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('smsservice_assign_cdr', 'No') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Click2Call</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('c2c', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('c2c', 'No') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Click2Call Channels</label>
                                        {!! Form::text('c2c_channels', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('c2c_channels', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Clik2CallAPI</label>
                                        {!! Form::text('c2cAPI', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('c2cAPI', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Direct Trasfer</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('operator_dpt', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('operator_dpt', 'No') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Sms Gateway</label>
                                        {!! Form::select('sms_api_gateway_id', $sms_gateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('sms_api_gateway_id', ':message') : '' !!}</p>
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        {!! Form::text('sms_api_user', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_user', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        {!! Form::password('sms_api_pass', ['class' => 'form-control']) !!} 
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_pass', ':message') : '' !!}</p>
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        {!! Form::text('sms_api_senderid', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_senderid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                         {!! Form::text('API', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('API', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                         {!! Form::text('cdr_apikey', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('cdr_apikey', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                         {!! Form::text('ip', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('ip', ':message') : '' !!}</p>

                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        {!! Form::text('max_no_confrence', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('max_no_confrence', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Service Type</label>
                                        {!! Form::select('servicetype', array('call' => 'Call', 'callback' => 'Callback', 'misscall' => 'Misscall'), null,array('class' => 'form-control')) !!}    
                                    </div>

                                     <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR Tag</label>
                                         {!! Form::select('cdr_tag', array('1' => 'Enabled', '0' => 'Disabled'), null,array('class' => 'form-control')) !!} 
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Log ChanUNavil calls</label>
                                        {!! Form::select('cdr_chnunavil_log', array('1' => 'Enabled', '0' => 'Disabled'), null,array('class' => 'form-control')) !!} 
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Inbuilt CRM</label>
                                        {!! Form::select('crm', array('1' => 'Enabled', '0' => 'Disabled'), null,array('class' => 'form-control')) !!}  
                                    </div>

                                    <div class="col-md-12 form-group mb-3">
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Android APP</label>
                                         {!! Form::select('andriodapp', array('YES' => 'Yes', 'NO' => 'No'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Web portal SMS</label>
                                        {!! Form::select('web_sms', array('YES' => 'Yes', 'NO' => 'No'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Dial stratergy Type</label>
                                         {!! Form::select('dial_statergy', array('1' => 'New', '0' => 'Old'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">SMS Support</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('sms_support', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('sms_support', 'No') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Push Api Service</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('pushapi', 'yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('pushapi', 'No') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('pbxexten', 'Yes') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('pbxexten', 'No') }} No
                                            </label>
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

