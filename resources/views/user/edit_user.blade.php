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
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('customer_name', 'Customer name') !!}
                                        {!! Form::text('customer_name', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('customer_name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                        {!! Form::select('coperate_id', array('' => 'Select Coperate', 'test1' => 'test 1', 'test2' => 'test 2'), null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('coperate_id', ':message') : '' !!}</p>
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
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('start_date', ':message') : '' !!}</p>
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
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('end_date', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Status</label>
                                        {!! Form::select('status', array('ACTIVE' => 'Active', 'INACTIVE' => 'InActive'), null,array('class' => 'form-control')) !!}                            
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">DID</label>
                                        {!! Form::select('did', array('ACTIVE' => 'Active', 'INACTIVE' => 'InActive'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Multilanguage</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('multilanguage', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('multilanguage', 'NO') }} No   
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Language option</label>
                                        {!! Form::select('language', $lang, null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Record Call</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('record_call', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('record_call', 'NO') }} No  
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Operator Call Count</label>
                                        {!! Form::text('operator_call_count', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('operator_call_count', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        {!! Form::text('sms_api_user', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_user', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        {!! Form::password('sms_api_password', ['class' => 'form-control']) !!} 
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        {!! Form::text('sms_api_sender', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('sms_api_sender', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                         {!! Form::text('api', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('api', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                         {!! Form::text('cdr_api_key', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('cdr_api_key', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                         {!! Form::text('client_ip', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('client_ip', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">CDR Tag</label>
                                         {!! Form::select('cdr_tag', array('ENABLED' => 'Enabled', 'DISABLED' => 'Disabled'), null,array('class' => 'form-control')) !!} 
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Log ChanUNavil calls</label>
                                        {!! Form::select('chanunavil_calls', array('ENABLED' => 'Enabled', 'DISABLED' => 'Disabled'), null,array('class' => 'form-control')) !!} 
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        {!! Form::text('conference_members', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('conference_members', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Android APP</label>
                                         {!! Form::select('android_app', array('YES' => 'Yes', 'NO' => 'No'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Web portal SMS</label>
                                        {!! Form::select('portal_sms', array('YES' => 'Yes', 'NO' => 'No'), null,array('class' => 'form-control')) !!}
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
                                            <label class="radio-inline">
                                                {{ Form::radio('sms_support', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('sms_support', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Push Api Service</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('push_api_service', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('push_api_service', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('pbx_extension', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('pbx_extension', 'NO') }} No
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

