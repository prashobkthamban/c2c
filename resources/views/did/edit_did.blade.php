@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Did </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit Did</div>
                            {!! Form::model($did, ['method' => 'PATCH', 'route' => ['updateDid', $did->id]]) !!}
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('did', 'DID Number') !!}
                                        {!! Form::text('did', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger">{!! !empty($messages) ? $messages->first('did', ':message') : '' !!}</p>
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('dnid_name', 'DNID Name') !!}
                                        {!! Form::text('dnid_name', null, ['class' => 'form-control']) !!}
                                         <p class="text-danger">{!! !empty($messages) ? $messages->first('dnid_name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('gatewayid', 'Incoming PRI') !!}
                                        {!! Form::select('gatewayid', $prigateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('gatewayid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('outgoing_gatewayid', 'Outgoing PRI') !!}
                                        {!! Form::select('outgoing_gatewayid', $prigateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('outgoing_gatewayid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('c2cpri', 'C2C PRI') !!}
                                        {!! Form::select('c2cpri', $prigateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('c2cpri', ':message') : '' !!}</p>          
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('c2ccallerid', 'C2C Callerid') !!}
                                        {!! Form::text('c2ccallerid', null, ['class' => 'form-control']) !!}  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('c2ccallerid', ':message') : '' !!}</p>        
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('outgoing_callerid', 'Outgoing Callerid') !!}
                                        {!! Form::text('outgoing_callerid', null, ['class' => 'form-control']) !!} 
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('outgoing_callerid', ':message') : '' !!}</p>                                      
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('set_did_no', 'Set PRI Callerid') !!}
                                        {!! Form::text('set_did_no', null, ['class' => 'form-control']) !!}  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('set_did_no', ':message') : '' !!}</p>
                                    </div>
                                </div>
                                
                                    <div class="card-title mb-3">Extra DID Details</div>
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">DID Number</label>
                                        <input type="text" class="form-control" placeholder="DID Number*" name="e_did">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('e_did', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">DID Name</label>
                                        <input type="text" class="form-control" placeholder="DID Name" name="didname">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('didname', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Outgoing Callerid</label>
                                        <input type="text" class="form-control" placeholder="Outgoing Callerid" name="e_outgoing_callerid">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('e_outgoing_callerid', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Outgoing PRI</label>
                                        {!! Form::select('e_outgoing_gatewayid', $prigateway, null,array('class' => 'form-control')) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('e_outgoing_gatewayid', ':message') : '' !!}</p>
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
            <!-- end of row1 -->



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection

