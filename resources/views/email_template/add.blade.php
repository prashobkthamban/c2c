@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.bubble.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.snow.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
<link href="{{asset('assets/styles/vendor/select2.min.css')}}" rel="stylesheet" />
<style type="text/css">
        .select2-container {
        width: 100%!important;
    }
</style>
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Proposal </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Email Template</div>
                            {!! Form::open(['action' => 'EmailTemplateController@store', 'method' => 'post','autocomplete' => 'off','enctype' => 'multipart/form-data']) !!} 
                                <div class="row">

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="subject">Name*</label>
                                        <input type="text" class="form-control" id="name" placeholder="subject" name="name">
                                    </div>

                                    <div class="col-md-4"></div>
                                    
                                    <div class="col-md-4"></div>

                                    <div class="col-md-12 form-group mb-3">
                                        <label for="subject">Subject*</label>
                                        <input type="text" class="form-control" id="subject" placeholder="subject" name="subject" required="">
                                    </div>

                                    <div class="col-md-4 form-group mb-3"></div>

                                    <div class="col-md-12 form-group mb-3">
                                        <div class="mx-auto col-md-12">
                                            <div id="full-editor" style="height: 150px;"></div>
                                        </div>
                                    </div>
                                    <textarea name="mail_body" id="mail_body" class="form-control" hidden=""></textarea>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="attachment">Attachment</label>
                                        <input type="file" name="attachment" id="attachment" class="form-control">
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

<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/es5/script.min.js')}}"></script>
<script src="{{asset('assets/js/es5/sidebar.large.script.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/quill.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/quill.min.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".ql-editor").on("keyup", function(){ 
            //alert($(this).html());
            var data = $(this).html();
            $("#mail_body").html(data);
        }); 

    });
</script>
@endsection

