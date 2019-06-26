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
                                   <!--  <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Mobile/DID</label>
                                        <input type="text" class="form-control" placeholder="Mobile Number" name="customer_name">
                                    </div>
 -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">DID Number</label>
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
                                        <select class="form-control" name="gatewayid">
                                            <option value="">Incoming PRI</option>
                                            <option value="test1">test 1</option>
                                            <option value="test2">test 2</option>
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('gatewayid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Outgoing PRI</label>
                                        <select class="form-control" name="outgoing_gatewayid">
                                            <option value="">Outgoing PRI</option>
                                            <option value="test1">test 1</option>
                                            <option value="test2">test 2</option>
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('outgoing_gatewayid', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">C2C PRI</label>
                                        <select class="form-control" name="c2cpri">
                                            <option value="">C2C PRI</option>
                                            <option value="test1">test 1</option>
                                            <option value="test1">test 2</option>
                                        </select>
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

