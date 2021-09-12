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
                <div class="card-title mb-3">Add Did</div>
                {!! Form::open(['action' => 'DidController@store', 'method' => 'post']) !!}
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="firstName1">Mobile/DID *</label>
                            <input type="number" class="form-control" placeholder="Mobile Number" name="rdins">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('rdins', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="firstName1">DID Number *</label>
                            <input type="text" class="form-control" placeholder="Did Number" name="did">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('did', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="dnid_name">DNID Name</label>
                            <input type="text" class="form-control" placeholder="DNID Name" name="dnid_name">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('dnid_name', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="picker1">Incoming PRI</label>
                            {!! Form::select('gatewayid', $prigateway, null,array('class' => 'form-control')) !!}
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('gatewayid', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="picker1">Outgoing PRI</label>
                            {!! Form::select('outgoing_gatewayid', $prigateway, null,array('class' => 'form-control')) !!}
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('outgoing_gatewayid', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="picker1">C2C PRI</label>
                            {!! Form::select('c2cpri', $prigateway, null,array('class' => 'form-control')) !!}
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('c2cpri', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="firstName1">C2C Callerid</label>
                            <input type="text" class="form-control" placeholder="C2C Callerid" name="c2ccallerid">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('c2ccallerid', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="firstName1">Outgoing Callerid</label>
                            <input type="text" class="form-control" placeholder="Outgoing Callerid" name="outgoing_callerid">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('outgoing_callerid', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="firstName1">Set PRI Callerid</label>
                            <input type="text" class="form-control" placeholder="Set PRI Callerid" name="set_did_no">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('set_did_no', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="firstName1">Enable Failover on PRI Congession</label>
                            {!! Form::select('enable_failover_gateway', ['no' => 'No', 'yes' => 'Yes'], null,array('class' => 'form-control', 'id' => 'enable_failover_gateway')) !!}
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('enable_failover_gateway', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="picker1">Failover PRI</label>
                            {!! Form::select('failover_outgoing_gatewayid', $prigateway, null,array('class' => 'form-control')) !!}
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('failover_outgoing_gatewayid', ':message') : '' !!}</p>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="dnid_name">Failover PRI Callerid</label>
                            <input type="text" class="form-control" placeholder="Failover PRI Callerid" name="failover_outgoing_callerid">
                            <p class="text-danger">{!! !empty($messages) ? $messages->first('failover_outgoing_callerid', ':message') : '' !!}</p>
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