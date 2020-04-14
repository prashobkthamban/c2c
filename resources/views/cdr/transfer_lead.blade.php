@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
<style>
.select2-container {
    width: 100%!important;    
}

.card {
    border-radius: 0px !important; 
}
</style>
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Cdr Leads </h1> 
    </div>

            <div class="separator-breadcrumb border-top"></div>

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <p><center><b style="font-size: 20px;">Tranfering Leads</b></center></p>
                            <br><br>
                            
                            <div class="table-responsive">
                                {!! Form::open(['action' => 'TranferLeadsController@transferleads', 'method' => 'post','enctype' => 'multipart/form-data']) !!}
                                <div class="col-md-12">
                                    <div class="row">
                                       <div class="col-md-4">
                                            <label for="transfer_from">Transfer From </label>
                                            <select id="transfer_from" name="transfer_from" class="form-control">
                                                <option value="">Select Account</option>
                                                <?php
                                                foreach ($users_lists as $key => $value) { ?>
                                                   <option value="<?php echo $value->id; ?>"><?php echo $value->opername; ?></option>
                                                <?php }
                                                ?>
                                           </select>
                                        </div>
                                        <div class="col-md-4" style="text-align: center;font-size: 40px;color: red;">
                                           <p>=></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="transfer_to">Transfer To </label>
                                            <select id="transfer_to" name="transfer_to" class="form-control">
                                                <option value="">Select Account</option>
                                                <?php
                                                foreach ($users_lists as $key => $value) { ?>
                                                   <option value="<?php echo $value->id; ?>"><?php echo $value->opername; ?></option>
                                                <?php }
                                                ?>
                                           </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>


@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.table2excel.js')}}"></script>
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>
<script src="{{asset('assets/js/jquery.table2excel.min.js')}}"></script>

@endsection
