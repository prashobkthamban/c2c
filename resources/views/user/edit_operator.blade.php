@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<?php //dd($operator_edit->accounts->username); die(); ?>
  <div class="breadcrumb">
                <h1> Operator </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit Operator</div>
                            {!! Form::model($operator_edit, ['method' => 'PATCH', 'route' => ['updateOperator', $operator_edit->id]]) !!}
                                 <div class="row">                                

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('phonenumber', 'Phone number') !!}
                                        {!! Form::text('phonenumber', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phonenumber', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('opername', 'Operator') !!}
                                        {!! Form::text('opername', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('opername', ':message') : '' !!}</p>
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Login Id</label>
                                         <input type="text" class="form-control" placeholder="Login Id" name="username" value="{{$operator_edit->accounts->username}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('username', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Password</label>
                                         <input type="text" class="form-control" placeholder="Password" name="password" value="{{$operator_edit->accounts->user_pwd}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('password', ':message') : '' !!}</p>
                                    </div> 

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Status</label>
                                        {!! Form::select('oper_status', array('Online' => 'Online', 'Offline' => 'Offline'), 'Online', array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('oper_status', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('livetrasferid', 'Live Transfer no') !!}
                                        {!! Form::text('livetrasferid', null, ['class' => 'form-control']) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('livetrasferid', ':message') : '' !!}</p>
                                    </div>

                                    <!-- <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('start_work', 'Start time (00:00:00)') !!}
                                        {!! Form::text('start_work', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('start_work', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('end_work', 'End time (23:59:59)') !!}
                                        {!! Form::text('end_work', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('end_work', ':message') : '' !!}</p>
                                    </div> -->

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Shift</label>
                                        {!! Form::select('shift_id', $opr_shift->prepend('Select Shift', ''), $operator_edit->shift_id,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('shift_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Andriod App</label>
                                        {!! Form::select('app_use', array('Yes' => 'Yes', 'No' => 'No'), null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('app_use', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">CDR Download</label>
                                        {!! Form::select('edit', array('1' => 'Yes', '0' => 'No'), null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('edit', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Rec Download</label>
                                        {!! Form::select('download', array('1' => 'Yes', '0' => 'No'), null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('download', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="picker1">Rec Play</label>
                                        {!! Form::select('play', array('1' => 'Yes', '0' => 'No'), null,array('class' => 'form-control')) !!}
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

