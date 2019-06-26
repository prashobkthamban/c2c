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
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('dnid_name', 'DNID Name') !!}
                                        {!! Form::text('dnid_name', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('gatewayid', 'Incoming PRI') !!}
                                        {!! Form::select('gatewayid', array('' => 'Incoming PRI', 'test1' => 'test 1', 'test2' => 'test 2'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('outgoing_gatewayid', 'Outgoing PRI') !!}
                                        {!! Form::select('outgoing_gatewayid', array('' => 'Outgoing PRI', 'test1' => 'test 1', 'test2' => 'test 2'), null,array('class' => 'form-control')) !!}
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('outgoing_gatewayid', 'C2C PRI') !!}
                                        {!! Form::select('c2cpri', array('' => 'C2C PRI', 'test1' => 'test 1', 'test2' => 'test 2'), null,array('class' => 'form-control')) !!}             
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('c2ccallerid', 'C2C Callerid') !!}
                                        {!! Form::text('c2ccallerid', null, ['class' => 'form-control']) !!}          
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('outgoing_callerid', 'Outgoing Callerid') !!}
                                        {!! Form::text('outgoing_callerid', null, ['class' => 'form-control']) !!}                                       
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        {!! Form::label('set_did_no', 'Set PRI Callerid') !!}
                                        {!! Form::text('set_did_no', null, ['class' => 'form-control']) !!}  
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

