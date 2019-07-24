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
                            <div class="card-title mb-3">Add Category</div>
                            {!! Form::open(['action' => 'CrmController@categoryadd', 'method' => 'post']) !!} 
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Category Name</label>
                                        <input type="text" class="form-control" placeholder="Category Name" name="crm_category_name">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('crm_category_name', ':message') : '' !!}</p>
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

