@extends('layouts.master')
@section('before-css')


@endsection

@section('main-content')
         <div class="breadcrumb">
                <h1>Languages</h1>
                <ul>
                    <li><a href="{{url('/languages')}}">Back</a></li>
                </ul>
            </div>
            <div class="separator-breadcrumb border-top"></div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Language Details</div>
                            <form name="language" method="post" action="">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="firstName1">Short Code</label>
                                        <input type="text" class="form-control" id="firstName1" name="shortcode" placeholder="Language Shortcode">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="lastName1">Language Name</label>
                                        <input type="text" class="form-control" id="lastName1" name="Language" placeholder="Language Name">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Default</label>
                                        <select class="form-control" name="default">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <a href="{{url('/languages')}}" class="btn btn-warning">Cancel</a>
                                         <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@section('page-js')


@endsection

@section('bottom-js')




@endsection
