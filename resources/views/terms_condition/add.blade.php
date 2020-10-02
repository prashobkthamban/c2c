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
                            <div class="card-title mb-3">Add/Edit Terms And Condition for Invoice</div>
                            {!! Form::open(['action' => 'TermsAndConditionController@store', 'method' => 'post','autocomplete' => 'off']) !!}
                                <div class="row">
                                    <input type="hidden" name="uid" id="uid" value="<?php if (!empty($list_tc_invoices)) { echo $list_tc_invoices->id; } ?>">

                                    <div class="col-md-12 form-group mb-3">
                                        <div class="mx-auto col-md-12">
                                            <div id="full-editor" style="height: 150px;"><?php if (!empty($list_tc_invoices)) {
                                                echo $list_tc_invoices->tc_inv_name;
                                            }?></div>
                                        </div>
                                    </div>
                                    <textarea name="mail_body_invoice" id="mail_body_invoice" class="form-control" hidden=""><?php if (!empty($list_tc_invoices)) {
                                                echo $list_tc_invoices->tc_inv_name;
                                            }?></textarea>

                                    <div class="card-title mb-3">Add/Edit Terms And Condition for Proposal</div>

                                    <div class="col-md-12 form-group mb-3">
                                        <div class="mx-auto_1 col-md-12">
                                            <div id="full-editor_1" style="height: 150px;"><?php if (!empty($list_tc_invoices)) {
                                                echo $list_tc_invoices->tc_pro_name;
                                            }?></div>
                                        </div>
                                    </div>
                                    <textarea name="mail_body_proposal" id="mail_body_proposal" class="form-control" hidden=""><?php if (!empty($list_tc_invoices)) {
                                                echo $list_tc_invoices->tc_pro_name;
                                            }?></textarea>

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
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script src="{{asset('assets/js/quill.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/quill.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".mx-auto .ql-editor").on("click keyup keydown change keypress", function(){
            var data = $(this).html();
            $("#mail_body_invoice").html(data);
        });

        $(".mx-auto_1 .ql-editor").on("click keyup keydown change keypress", function(){
            var data = $(this).html();
            $("#mail_body_proposal").html(data);
        });

    });
</script>

@endsection

