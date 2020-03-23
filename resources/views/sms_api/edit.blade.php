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
                            <div class="card-title mb-3">Edit SMS Api</div>

                            {!! Form::model([$sms,$email], ['method' => 'PATCH', 'route' => ['SMSApiUpdate', $sms->id]]) !!}
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="link">Link*</label>
                                        <input type="text" class="form-control" id="link" placeholder="link" name="link" value="{{$sms->link}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('link', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="sender_id">Sender ID*</label>
                                        <input type="text" class="form-control" id="sender_id" placeholder="Sender ID" name="sender_id" value="{{$sms->sender_id}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('sender_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="username">Username*</label>
                                        <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="{{$sms->username}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('username', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="password">Password*</label>
                                        <input type="text" class="form-control" id="password" placeholder="Password" name="password" value="{{$sms->password}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('password', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="type">SMS Type</label>
                                        <select id="type" name="type" class="form-control">
                                            <option value="">Select Type</option>
                                            <?php
                                            if ($sms->type == 'Transactional') { ?>
                                               <option value="Transactional" selected="">Transactional</option>
                                               <option value="Promotional">Promotional</option>
                                            <?php }else{ ?>
                                                <option value="Transactional">Transactional</option>
                                                <option value="Promotional" selected="">Promotional</option>
                                            <?php } ?>
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('type', ':message') : '' !!}</p>
                                    </div>
                                </div>
                                <div class="card-title mb-3">Edit Email Api</div>
                                <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="username_email">Username*</label>
                                        <input type="text" class="form-control" id="username_email" placeholder="Username" name="username_email" value="{{$email->username}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('username_email', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="password_email">Password*</label>
                                        <input type="text" class="form-control" id="password_email" placeholder="Password" name="password_email" value="{{$email->password}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('password_email', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="smtp_host">SMTP Host*</label>
                                        <input type="text" class="form-control" id="smtp_host" placeholder="SMTP Host" name="smtp_host" value="{{$email->smtp_host}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('smtp_host', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="port">Port*</label>
                                        <input type="text" class="form-control" id="port" placeholder="Port" name="port" value="{{$email->port}}">
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('port', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="type_email">Email Type</label>
                                        <select id="type_email" name="type_email" class="form-control">
                                            <?php if ($email->type == 'SSL') { ?>
                                                <option value="">Select Type</option>
                                                <option value="SSL" selected="">SSL</option>
                                                <option value="TLS">TLS</option>
                                            <?php }else if ($email->type == 'TLS') { ?>
                                                <option value="">Select Type</option>
                                                <option value="SSL">SSL</option>
                                                <option value="TLS" selected="">TLS</option>
                                            <?php }else{ ?>
                                                <option value="">Select Type</option>
                                                <option value="SSL">SSL</option>
                                                <option value="TLS">TLS</option>
                                            <?php }?>
                                            
                                        </select>
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('type_email', ':message') : '' !!}</p>
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
@endsection

