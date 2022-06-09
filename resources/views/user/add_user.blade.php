@extends('layouts.master')
@section('page-css')
<style>
.datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Add User </h1>

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
                                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Customer name']) !!}
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                        {!! Form::select('resellerid', $coperate, null,array('class' => 'form-control')) !!}                            
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">Start date</label>
                                        <div class="input-group">
                                             {!! Form::text('startdate', null, ['class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy']) !!}
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                         <p class="text-danger ">{{ $errors->first('startdate') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">End date</label>
                                        <div class="input-group">
                                            {!! Form::text('enddate', null, ['class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy']) !!}
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>  
                                        </div>
                                        <p class="text-danger ">{{ $errors->first('enddate') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Status</label>
                                         {!! Form::select('status', array('ACTIVE' => 'Active', 'INACTIVE' => 'InActive'), null,array('class' => 'form-control')) !!}   
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">DID</label>
                                        {!! Form::select('did', $did_list, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger ">{{ $errors->first('did') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Multilanguage</label>
                                        <div>
                                            <label class="radio-inline"> {{ Form::radio('multi_lang', 'Yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('multi_lang', 'No', true) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Language option</label>
                                        {!! Form::text('lang_file', null, ['class' => 'form-control', 'placeholder' => 'Language']) !!}
                                        <span><b>separeted by :</b> eg:->gu:en:hi:te:ma</span>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Record Call</label>
                                        <div>
                                            <label class="radio-inline">{{ Form::radio('record_call', 'Yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('record_call', 'No', true) }} No  </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Operator Call Count</label>
                                         {!! Form::number('try_count', 3, ['class' => 'form-control', 'placeholder' => 'Operator Call Count']) !!}
                                         <p class="text-danger ">{{ $errors->first('try_count') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialtime</label>
                                         {!! Form::number('dial_time', 30, ['class' => 'form-control', 'placeholder' => 'In Seconds']) !!}
                                         <p class="text-danger ">{{ $errors->first('dial_time') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Call Duration</label>
                                        {!! Form::number('maxcall_dur', 300, ['class' => 'form-control', 'placeholder' => 'In Seconds']) !!}
                                         <p class="text-danger ">{{ $errors->first('maxcall_dur') }}</p>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Web Logins</label>
                                         {!! Form::number('operator_no_logins', 10, ['class' => 'form-control', 'placeholder' => 'In Seconds']) !!}
                                         <p class="text-danger ">{{ $errors->first('operator_no_logins') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Number of Channels</label>
                                         {!! Form::select('no_channels', array('0' => '0', '1' => '1','2' => '2', '3' => '3','4' => '4', '5' => '5','6' => '6', '7' => '7','8' => '8', '9' => '9'), null,array('class' => 'form-control')) !!}  
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign Email</label>
                                        <div>
                                            <label class="radio-inline">{{ Form::radio('emailservice_assign_cdr', 'Yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('emailservice_assign_cdr', 'No', true) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign SMS</label>
                                        <div>
                                            <label class="radio-inline">{{ Form::radio('smsservice_assign_cdr', 'Yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('smsservice_assign_cdr', 'No', true) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Click2Call</label>
                                        <div>
                                            <label class="radio-inline"> {{ Form::radio('c2c', 'Yes', null, ['class' => 'click2call']) }} Yes</label>
                                            <label class="radio-inline"> {{ Form::radio('c2c', 'No', true, ['class' => 'click2call']) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Click2Call Channels</label>
                                        {!! Form::number('c2c_channels', 1, ['class' => 'form-control', 'placeholder' => 'Click2Call Channels', 'readonly']) !!} 
                                        <p class="text-danger ">{{ $errors->first('c2c_channels') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Clik2CallAPI</label>
                                        {!! Form::text('c2cAPI', $c2capi, ['class' => 'form-control', 'placeholder' => 'Clik2CallAPI']) !!} 
                                        <p class="text-danger ">{{ $errors->first('c2cAPI') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Direct Trasfer</label>
                                        <div>
                                            <label class="radio-inline"> {{ Form::radio('operator_dpt', 'Yes') }} Yes</label>
                                            <label class="radio-inline"> {{ Form::radio('operator_dpt', 'No', true) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Sms Gateway</label>
                                        {!! Form::select('sms_api_gateway_id', $sms_gateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{{ $errors->first('sms_api_gateway_id') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                         {!! Form::text('sms_api_user', null, ['class' => 'form-control', 'placeholder' => 'Sms api user']) !!}
                                        <p class="text-danger ">{{ $errors->first('sms_api_user') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        {!! Form::password('sms_api_pass', ['class' => 'form-control', 'placeholder' => 'Sms api Password']) !!} 
                                        <p class="text-danger ">{{ $errors->first('sms_api_pass') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        {!! Form::text('sms_api_senderid', null, ['class' => 'form-control', 'placeholder' => 'Sms api sender']) !!}
                                        <p class="text-danger ">{{ $errors->first('sms_api_senderid') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                        {!! Form::text('API', null, ['class' => 'form-control', 'placeholder' => 'API']) !!}
                                        <p class="text-danger ">{{ $errors->first('API') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                        {!! Form::text('cdr_apikey', $cdr_api_key, ['class' => 'form-control', 'placeholder' => 'CDR API key']) !!}
                                        <p class="text-danger ">{{ $errors->first('cdr_apikey') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                        {!! Form::text('ip', null, ['class' => 'form-control', 'placeholder' => 'Client IP']) !!}
                                        <p class="text-danger ">{{ $errors->first('ip') }}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        {!! Form::number('max_no_confrence', 5, ['class' => 'form-control', 'placeholder' => 'Dialout conference members']) !!}
                                        <p class="text-danger ">{{ $errors->first('max_no_confrence') }}</p>
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
                                            <label class="radio-inline">{{ Form::radio('sms_support', 'Yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('sms_support', 'No', true) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Push Api Service</label>
                                        <div>
                                            <label class="radio-inline">{{ Form::radio('pushapi', 'yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('pushapi', 'No', true) }} No</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline">{{ Form::radio('pbxexten', 'Yes') }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('pbxexten', 'No', true) }} No</label>
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".click2call").click(function()
        {
            var call_value = $(this).val();
            if(call_value == 'Yes') {
               $('input[name=c2c_channels]').attr('readonly', false);
            } else {
               $('input[name=c2c_channels]').attr('readonly', true); 
               $('input[name=c2c_channels]').val(1); 
            }
        });
    });
</script>

@endsection

