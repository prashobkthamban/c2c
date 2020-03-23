@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Edit User Settings </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit User Settings</div>
                            {!! Form::model($user_edit, ['method' => 'PATCH', 'route' => ['updateUserSettings', $user_edit->id]]) !!}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('name', 'Customer name') !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
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
                                                { Form::radio('pushapi', 'yes') }} Yes
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

