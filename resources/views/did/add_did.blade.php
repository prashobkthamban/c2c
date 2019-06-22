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
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">DNID Name</label>
                                        <input type="text" class="form-control" placeholder="Customer name" name="dnid_name">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Incoming PRI</label>
                                        <select class="form-control" name="gatewayid">
                                            <option value="0">Select Coperate</option>
                                            <option value="tst1">Option 1</option>
                                            <option value="tst2">Option 2</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Outgoing PRI</label>
                                        <select class="form-control" name="outgoing_gatewayid">
                                            <option value="0">Select Coperate</option>
                                            <option value="tst1">Option 1</option>
                                            <option value="tst2">Option 2</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">C2C PRI</label>
                                        <select class="form-control" name="c2cpri">
                                            <option value="0">Select Coperate</option>
                                            <option value="tst1">Option 1</option>
                                            <option value="tst2">Option 2</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">C2C Callerid</label>
                                        <input type="text" class="form-control" placeholder="C2C Callerid" name="c2ccallerid">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Outgoing Callerid</label>
                                        <input type="text" class="form-control" placeholder="Outgoing Callerid" name="outgoing_callerid">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Set PRI Callerid</label>
                                        <input type="text" class="form-control" placeholder="Set PRI Callerid" name="set_did_no">
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

