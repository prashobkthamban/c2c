@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Lead </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Lead</div>
                            {!! Form::open(['action' => 'UserController@storeLead', 'method' => 'post','autocomplete' => 'off']) !!} 
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName">Name</label>
                                        <input type="text" class="form-control" id="firstName" placeholder="Name" name="name">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('name', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="phoneNumber">Phone Number</label>
                                        <input type="text" class="form-control" id="phoneNumber" placeholder="Phone Number" name="phone_number">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phone_number', ':message') : '' !!}</p>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" placeholder="Email" name="email">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('email', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="dob">Date of birth</label>
                                        <div class="input-group">
                                            <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="DOB" >
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary"  type="button">
                                                    <i class="icon-regular i-Calendar-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                         <p class="text-danger ">{!! !empty($messages) ? $messages->first('DOB', ':message') : '' !!}</p>
                                    </div>

                                   <div class="col-md-6 form-group mb-3">
                                        <label for="address">Address</label>
                                        <textarea rows="8" cols="20" class="form-control" placeholder="Address" name="address"></textarea>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('address', ':message') : '' !!}</p>
                                    </div>
                                    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="lead_status">Lead Status</label>
                                        <select class="form-control" name="lead_status">
                                            <option value="ACTIVE">Active</option>
                                            <option value="INACTIVE">InActive</option>   
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Category name</label>
                                        {!! Form::select('category_id', $category, null,array('class' => array('form-control','lead-category'))) !!}
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('category_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Subcategory name</label>
                                        <select class="form-control lead-sub-category" name="sub_category_id">
                                            <option value="0" selected="selected">Select sub category</option>
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('category_id', ':message') : '' !!}</p>
                                    </div>
                                    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="lead_owner">Lead owner assign to</label>
                                        <input type="text" class="form-control" id="lead_owner" placeholder="Lead Owner" name="lead_owner">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('lead_owner', ':message') : '' !!}</p>
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

