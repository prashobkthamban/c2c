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
                                        {!! Form::label('groupname', 'Customer name') !!}
                                        {!! Form::text('groupname', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('groupname', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Coperate name</label>
                                         {!! Form::select('resellerid', $coperate, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('resellerid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">Start date</label>
                                        <div class="input-group">
                                             {!! Form::text('sdate', null, ['class' => 'form-control datepicker']) !!}
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('sdate', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker3">End date</label>
                                        <div class="input-group">
                                            {!! Form::text('edate', null, ['class' => 'form-control datepicker']) !!} 
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('edate', ':message') : '' !!}</p>
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
                                                {{ Form::radio('lang', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('lang', 'NO') }} No   
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Language option</label>
                                        {!! Form::select('lango', $lang, null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Record Call</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('recordcall', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('recordcall', 'NO') }} No  
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Operator Call Count</label>
                                        {!! Form::text('trycount', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('trycount', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialtime</label>
                                        {!! Form::text('dialtime', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('dialtime', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Call Duration</label>
                                        {!! Form::text('max_call', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('max_call', ':message') : '' !!}</p>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Max Web Logins</label>
                                         {!! Form::text('operator_no_logins', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('operator_no_logins', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Number of Channels</label>
                                        {!! Form::select('chan', array('0' => '0', '1' => '1'), null,array('class' => 'form-control')) !!}          
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign Email</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('emailservice_assign_cdr', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('emailservice_assign_cdr', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR assign SMS</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('c2cchan', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('c2cchan', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Click2Call</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('c2c', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('c2c', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Click2Call Channels</label>
                                        {!! Form::text('c2cchan', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('c2cchan', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Clik2CallAPI</label>
                                        {!! Form::text('c2capi', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('c2capi', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Direct Trasfer</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('dt', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('dt', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Sms Gateway</label>
                                        {!! Form::select('resellerid1', $coperate, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('resellerid1', ':message') : '' !!}</p>
                                    </div>


                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api user</label>
                                        {!! Form::text('user', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('user', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api Password</label>
                                        {!! Form::password('pass', ['class' => 'form-control']) !!} 
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Sms api sender</label>
                                        {!! Form::text('sender', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('sender', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">API</label>
                                         {!! Form::text('api', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('api', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">CDR API key</label>
                                         {!! Form::text('cdrapi', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('cdrapi', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Client IP</label>
                                         {!! Form::text('ip', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('ip', ':message') : '' !!}</p>

                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">CDR Tag</label>
                                         {!! Form::select('cdr_tag', array('ENABLED' => 'Enabled', 'DISABLED' => 'Disabled'), null,array('class' => 'form-control')) !!} 
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Log ChanUNavil calls</label>
                                        {!! Form::select('cdr_chnunavil_log', array('ENABLED' => 'Enabled', 'DISABLED' => 'Disabled'), null,array('class' => 'form-control')) !!} 
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Dialout conference members</label>
                                        {!! Form::text('max_no_confrence', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger ">{!! !empty($messages) ? $messages->first('max_no_confrence', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
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
                                        <select class="form-control" name="dial_statergy">
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
                                                {{ Form::radio('pushapi', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('pushapi', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">PBX Extension</label>
                                        <div>
                                            <label class="radio-inline">
                                                {{ Form::radio('pbxexten', 'YES') }} Yes
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('pbxexten', 'NO') }} No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Service Type</label>
                                        {!! Form::select('servicetype', array('call' => 'Call', 'callback' => 'Callback', 'misscall' => 'Misscall'), null,array('class' => 'form-control')) !!}    
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

