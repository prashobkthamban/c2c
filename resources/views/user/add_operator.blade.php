@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Operator </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Operator</div>
                            {!! Form::open(['action' => 'UserController@storeOperator', 'method' => 'post','autocomplete' => 'off']) !!} 
                                <div class="row">                                

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Phone number</label>
                                         <input type="text" class="form-control" placeholder="Phone number" name="phonenumber">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phonenumber', ':message') : '' !!}</p>
                                    </div>

                                    <!--<div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Extension Number</label>
                                         <input type="text" class="form-control" placeholder="Extension Number" name="extension_number">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phone_number', ':message') : '' !!}</p>
                                    </div> -->

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Operator</label>
                                         <input type="text" class="form-control" placeholder="Operator" name="opername">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('opername', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Login Id</label>
                                         <input type="text" class="form-control" placeholder="Login Id" name="username">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('username', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Password</label>
                                         <input type="password" class="form-control" placeholder="Password" name="password">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('password', ':message') : '' !!}</p>
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Status</label>
                                        {!! Form::select('oper_status', array('Online' => 'Online', 'Offline' => 'Offline'), 'Online', array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('oper_status', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Live Transfer no</label>
                                         <input type="text" class="form-control" placeholder="Live Transfer no" name="livetrasferid">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('livetrasferid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-3 col-form-label">Working Days</label>
                                            <div class="col-sm-9">
											<?php if($acGrp[0]->working_days) { $days = json_decode($acGrp[0]->working_days);} ?>
                                                 <select class="form-control" id="working_days" name="working_days[]" multiple>
														<option value="Mon" <?php  if(in_array('Mon',$days)){ echo 'SELECTED';} ?> > Monday </option>
														<option value="Tue" <?php  if(in_array('Tue',$days)){ echo 'SELECTED';} ?>> Tuesday </option>
														<option value="Wed" <?php  if(in_array('Wed',$days)){ echo 'SELECTED';} ?>> Wednesday </option>
														<option value="Thu" <?php  if(in_array('Thu',$days)){ echo 'SELECTED';} ?>> Thursday </option>
														<option value="Fri" <?php  if(in_array('Fri',$days)){ echo 'SELECTED';} ?>> Friday </option>
														<option value="Sat" <?php  if(in_array('Sat',$days)){ echo 'SELECTED';} ?>> Saturday </option>
														<option value="Sun" <?php  if(in_array('Sun',$days)){ echo 'SELECTED';} ?>> Sunday </option>
														</select>
													</div>
                                                </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Shift</label>
                                        {!! Form::select('shift_id', $opr_shift->prepend('Select Shift', ''), 0,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('shift_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Andriod App</label>
                                        {!! Form::select('app_use', array('Yes' => 'Yes', 'No' => 'No'), 0,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('app_use', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR Download</label>
                                        {!! Form::select('edit', array('1' => 'Yes', '0' => 'No'), 0,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('edit', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Rec Download</label>
                                        {!! Form::select('download', array('1' => 'Yes', '0' => 'No'), 0,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('download', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Rec Play</label>
                                        {!! Form::select('play', array('1' => 'Yes', '0' => 'No'), 0,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('play', ':message') : '' !!}</p>
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

