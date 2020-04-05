@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.bubble.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/quill.snow.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/perfect-scrollbar.css')}}">
<link href="{{asset('assets/styles/vendor/select2.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
<style type="text/css">
    .lead_stage{
        width: 120px;
        text-align: center;
        background-color: #ffc107;
        color: #ffffff;
       line-height: 24px;
    }

    .border_left{
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
    }

    .border_right{
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
    }

    .border{
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
        border-bottom-left-radius: 30px;
        border-top-left-radius: 30px;
    }

    .input_border{
        border: none;
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: transparent;
    }

    .th_width_40{
        width: 40%;
    }

    .th_width_60{
        width: 60%;
    }

    .select2-container {
        width: 100%!important;
    }

</style>
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Lead </h1>
            </div>
            <div class="separator-breadcrumb border-top"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4"><br/>
                        @if(!empty($lead))
                        @foreach($lead as $lead_data )
                        <div class="row">
                            <div class="col-md-8">
                                <h1>&nbsp;&nbsp;&nbsp;<?php echo $lead_data->first_name;?>
                                    <a href="javascript:void(0)" class="btn btn-primary fa fa-envelope" data-toggle="modal" data-target="#mail" data-id="{{$lead_data->id}}" data-email="{{$lead_data->email}}" id="mail_modal" style="padding: 10px;"> Mail</a>

                                    <a href="javascript:void(0)" class="btn btn-primary fa fa-phone" data-toggle="modal" data-target="#call_log" data-id="{{$lead_data->id}}" data-name="{{$lead_data->first_name.' '.$lead_data->last_name}}"  id="call_log_modal" style="padding: 10px;"> Add Call Log</a>

                                    <a href="javascript:void(0)" class="btn btn-primary fa fa-sms" data-toggle="modal" data-target="#send_msg" data-id="{{$lead_data->id}}" data-name="{{$lead_data->first_name.' '.$lead_data->last_name}}"  id="msg_modal" style="padding: 10px;"> Send Message</a>

                                    <a href="javascript:void(0)" class="btn btn-danger fa fa-clock"  data-toggle="modal" data-target="#add_reminder_modal" data-id="{{$lead_data->id}}" id="remainder_modal" style="padding: 10px;">Add Reminder</a>

                                    <a href="javascript:void(0)" class="btn btn-warning fa fa-tag"  data-toggle="modal" data-target="#add_proposal_modal" data-id="{{$lead_data->id}}" id="proposal_modal" style="padding: 10px;">Add Proposal</a>

                                </h1>
                            </div>
                            <div class="col-md-4" style="margin-left: -90px;">
                                <h1>Notes</h1>
                            </div>
                            <!-- <div class="col-md-8">
                                <a href="javascript:void(0)" class="btn btn-primary fa fa-envelope" data-toggle="modal" data-target="#mail" data-id="{{$lead_data->id}}" id="mail_modal" style="padding: 10px;"> Mail</a>

                                <a href="javascript:void(0)" class="btn btn-primary fa fa-phone" data-toggle="modal" data-target="#call_log" data-id="{{$lead_data->id}}" data-name="{{$lead_data->first_name.' '.$lead_data->last_name}}"  id="call_log_modal" style="padding: 10px;"> Add Call Log</a>

                                <a href="javascript:void(0)" class="btn btn-primary fa fa-sms" data-toggle="modal" data-target="#send_msg" data-id="{{$lead_data->id}}" data-name="{{$lead_data->first_name.' '.$lead_data->last_name}}"  id="msg_modal" style="padding: 10px;"> Send Message</a>

                            </div> -->
                        </div>
                        
                        <div class="row">
                            <div class="card" style="margin-left: 30px;">
                                <div class="card-body col-md-12">
                                    <div class="col-md-12 modal-title">
                                        <a href="javascript:void(0)" class="btn btn-primary edit_data" style="float: right;">Edit</a>
                                    </div>
                                    {!! Form::open(['action' => 'LeadController@update_lead', 'method' => 'Patch','enctype' => 'multipart/form-data', 'route' => ['update_lead', $lead_data->id]]) !!}
                                         {{ csrf_field() }}
                                        <div class="modal-body" style="margin-top: 29px;">
                                            <table border="0" cellspacing="0" cellpadding="3" style="width: 100%;">
                                                <tbody>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>
                                                            <input type="text" name="first_name" id="first_name" class="form-control input_border" placeholder="Enter your First Name" required="" readonly="" value="{{$lead_data->first_name}}" />
                                                        </th>
                                                        <th>Last Name</th>
                                                        <th>
                                                            <input type="text" name="last_name" id="last_name" class="form-control input_border"placeholder="Enter your Last Name" required="" readonly="" value="{{$lead_data->last_name}}" />  
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Company Name</th>
                                                        <th>
                                                            <input type="text" name="company_name" id="company_name" class="form-control input_border"placeholder="Enter Company Name" readonly="" value="{{$lead_data->company_name}}" />  
                                                        </th>
                                                        <th>Email</th>
                                                        <th>
                                                            <input type="email" name="email" id="email" class="form-control input_border"placeholder="Enter Email Address" readonly="" value="{{$lead_data->email}}" />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Phone No</th>
                                                        <th>
                                                            <input type="text" name="phoneno" id="phoneno" class="form-control input_border" readonly="" value="{{$lead_data->phoneno}}" />
                                                        </th>
                                                        <th>Another Phone No</th>
                                                        <th>
                                                            <input type="text" name="alt_phoneno" id="alt_phoneno" class="form-control input_border" readonly="" value="{{$lead_data->alt_phoneno}}" />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <th>
                                                            <input type="text" name="department" id="department" class="form-control input_border" readonly="" value="{{$lead_data->department}}" />
                                                        </th>
                                                        <th>Has authority</th>
                                                        <th>
                                                            <input type="radio" name="authority" id="auto_no" class="input_border" value="no" disabled {{ $lead_data ->authority == 'no'  ? 'checked="checked"' : '' }} /><label for="auto_no">No</label>
                                                            <input type="radio" name="authority" id="auto_yes" class="input_border" value="yes" disabled {{ $lead_data ->authority == 'yes'  ? 'checked="checked"' : '' }} /><label for="auto_yes">Yes</label>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Work</th>
                                                        <th>
                                                            <input type="text" name="work" id="work" class="form-control input_border" readonly="" value="N/A" value="{{$lead_data->work}}"/>
                                                        </th>
                                                        <th>Do not distrub</th>
                                                        <th>
                                                            <input type="radio" name="dnd" id="dnd_no" class="input_border" value="no" {{ $lead_data ->dnd == 'no'  ? 'checked="checked"' : '' }} disabled /><label for="dnd_no">No</label>
                                                            <input type="radio" name="dnd" id="dnd_yes" class="input_border" value="yes" {{ $lead_data ->dnd == 'yes'  ? 'checked="checked"' : '' }} disabled /><label for="dnd_yes">Yes</label>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                         <th>Address</th>
                                                        <th>
                                                           <textarea name="address" id="address" class="form-control input_border" readonly="">{{$lead_data->address}}</textarea>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endforeach
                                            @endif 
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" id="sub_lead" style="float: right;display: none;">Submit</button>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="card" style="margin-left: 25px;margin-right: 25px;width: 37%;">
                                <!-- <h1>Notes</h1> -->
                                <div class="card-body col-md-12">
                                    <div class="col-md-12 note_msg" id="add_note">
                                        {!! Form::open(['action' => 'LeadController@Notes', 'method' => 'post']) !!}
                                                <input type="hidden" name="lead_id" id="lead_id" value="{{$lead_data->id}}">
                                                <textarea name="note_msg" id="note_msg" style="height: 150px;width: 100%;border-radius: 2px;background: white;" placeholder="Start typing...." required=""></textarea>
                                                <button class="btn btn-primary" style="float: right;margin-bottom: 10px;">save</button>
                                            {!! Form::close() !!}
                                    </div>
                                    <div class="col-md-12 note_msg edit_note" id="edit_note" style="padding-left: 4px; display: none;">
                                        {!! Form::open(['action' => 'LeadController@EditNotes', 'method' => 'patch']) !!}
                                                <input type="hidden" name="lead_id" id="lead_id" value="{{$lead_data->id}}">
                                                <input type="hidden" name="note_id" id="note_id">
                                                <textarea name="edit_note_msg" id="edit_note_msg" style="height: 150px;width: 100%;border-radius: 2px;background: white;" placeholder="Start typing...." required=""></textarea>
                                                <button class="btn btn-primary" style="float: right;margin-bottom: 10px;">save</button>
                                            {!! Form::close() !!}
                                    </div>
                                    <div class="row" style="margin-top: 43px;">
                                        <div class="o-hidden" style="width: 95%;">
                                            <div class="col-md-12" style="height: 140px;overflow-y: auto;">
                                                <table border="0" cellspacing="0" cellpadding="3" style="width: 100%;">
                                                <tbody style="text-align: left;">
                                                    @if(!$notes->isempty())
                                                    @foreach($notes as $note)
                                                    <tr>
                                                        <th>{{$note->note}}
                                                            <!-- <p style="font-size: 10px;"> -->{{$note->user_name}}<!-- </p> -->
                                                            <h6 style="font-size: 10px;">
                                                                <?php $time_ago = strtotime($note->inserted_date);
                                                                //echo date("H:i:s",$time_ago) ;
                                                                echo to_time_ago($time_ago); ?></h6>
                                                        </th>
                                                        <th style="text-align: right;">
                                                            <!-- <a href="javascript:void(0)" class="text-success mr-2 edit_lead" id="edit_lead"><i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                            </a> -->
                                                            <a href="javascript:void(0)" _ngcontent-mfn-c9="" data-id="{{$note->id}}" id="edit_per_note" data-note="{{$note->note}}" class="btn btn-wide btn-outline-secondary mr-3 edit_per_note">Edit</a>

                                                            <a href="{{ route('NoteDelete', $note->id) }}" onclick="return confirm('Are you sure you want to delete this Note?')" _ngcontent-rck-c7="" class="btn btn-outline-danger">Delete</a>  
                                                        </th>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                            </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                        // PHP program to convert timestamp 
                        // to time ago 
                        date_default_timezone_set('Asia/Kolkata'); 
                        function to_time_ago( $time ) { 
                              
                            // Calculate difference between current 
                            // time and given timestamp in seconds 
                            $diff = time() - $time; 
                            if( $diff < 1 ) {  
                                return 'less than 1 second ago';  
                            } 
                              
                            $time_rules = array (  
                                        12 * 30 * 24 * 60 * 60 => 'year', 
                                        30 * 24 * 60 * 60       => 'month', 
                                        24 * 60 * 60           => 'day', 
                                        60 * 60                   => 'hour', 
                                        60                       => 'minute', 
                                        1                       => 'second'
                            ); 
                          
                            foreach( $time_rules as $secs => $str ) { 
                                  
                                $div = $diff / $secs; 
                          
                                if( $div >= 1 ) { 
                                      
                                    $t = round( $div ); 
                                      
                                    return $t . ' ' . $str .  
                                        ( $t > 1 ? 's' : '' ) . ' ago'; 
                                } 
                            } 
                        } 
                          
                        ?>
                        <div class="card-body">
                            <div class="card-title mb-3">Lead Stage Changed:<h6><?php 
                            if (!empty($lead_stages)) {
                                $time_ago = strtotime($lead_stages->updated_stages);
                                echo to_time_ago($time_ago);
                            }
                             ?></h6></div>
                            <!-- Form Open -->
                                <div class="row">
                                    <input type="hidden" name="cdr_lead_id" id="cdr_lead_id" value="<?php print_r($id);?>">
                                    <?php $lead_id = $id; ?>
                                	
                                    <a href="{{ route('lead_stages',[$lead_id,1]) }}" class="btn hover lead_stage" style="border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;" title="New" id="1">New</a>
                                    <a href="{{ route('lead_stages',[$lead_id,2]) }}" class="btn lead_stage" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;" title="Contacted" id="2">Contacted</a>
                                    <a href="{{ route('lead_stages',[$lead_id,3]) }}" class="btn lead_stage" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;" title="Interested" id="3">Interested</a>
                                    <a href="{{ route('lead_stages',[$lead_id,4]) }}" class="btn lead_stage" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;" title="Under review" id="4">Under review</a>
                                    <a href="{{ route('lead_stages',[$lead_id,5]) }}" class="btn lead_stage" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;" title="Demo" id="5">Demo</a>

                                    <!-- <a href="{{ route('lead_stages',[$lead_id,6]) }}" class="btn lead_stage" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;" title="Converted" id="6">Converted</a> -->

                                    <!-- <a href="{{ route('lead_stages',[$lead_id,7]) }}" class="btn lead_stage" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border: none;" title="Unqualified" id="7">Unqualified</a> -->

                                    <a href="javascript:void(0)" class="btn lead_stage lead_stage_last" data-toggle="modal" data-target="#unqualified" data-id="{{$lead_data->id}}" id="6" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;">Unqualified</a>

                                    <a href="javascript:void(0)" class="btn lead_stage lead_stage_last" data-toggle="modal" data-target="#converted" data-id="{{$lead_data->id}}" id="7" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;">Converted</a>

                                    <!-- <select class="btn lead_stage lead_stage_last" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 0;border-bottom-right-radius: 0;border: none;width: auto;">
                                        <option value="">Converted/Unqualified</option>
                                        <option value="6" id="6" >Converted</option>
                                        <option value="7" id="7" data-toggle="modal" data-target="#unqualified" data-id="{{$lead_data->id}}">Unqualified</option>
                                    </select> -->

                                    @if(!empty($lead_stages))
                                    <input type="hidden" name="level" id="level" value="<?php echo $lead_stages->levels;?>">
                                    @endif 
                                </div>
                         	<!-- Form Close -->
                        </div>

                        <div class="modal fade Mail" id="mail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="modal-header">
                                        <div class="col-md-12 modal-title">New Mail<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['action' => 'LeadController@Mail', 'method' => 'post']) !!}
                                        <form method="post">
                                             {{ csrf_field() }}
                                            <div class="modal-body">
                                                <div class="row">
                                                    <input type="hidden" name="lead_id" id="lead_id">
                                                    <div class="col-md-12 form-group mb-3">
                                                        <label for="email_template">Select Email Template</label>
                                                        <select id="email_template" name="email_template" class="form-control">
                                                            <option value="">Select Template</option>
                                                            @if(!$mail_template->isempty())
                                                            @foreach($mail_template as $mailtemp)
                                                            <option value="{{$mailtemp->id}}">{{$mailtemp->name}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">From</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="from" id="form">
                                                    </div>
                                                
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">TO</span>
                                                        </div>
                                                        <input type="text" name="to" id="to" class="form-control" />  
                                                    </div>

                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">CC</span>
                                                        </div>
                                                        <input type="text" name="cc" id="cc" class="form-control" />  
                                                    </div>

                                                    <!-- <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">BCC</span>
                                                        </div>
                                                        <input type="text" name="bcc" id="bcc" class="form-control" />  
                                                    </div> -->

                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Subject</span>
                                                        </div>
                                                        <input type="text" name="subject" id="subject" class="form-control" />  
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="mx-auto col-md-12">
                                                            <div id="full-editor" style="height: 150px;"></div>
                                                        </div>
                                                    </div>
                                                    <textarea name="mail_body" id="mail_body" class="form-control" hidden=""></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer col-md-12">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary" style="float: right;">Send Mail</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade CallLog" id="call_log" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="modal-header">
                                        <div class="col-md-12 modal-title">Add Call Log<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['action' => 'LeadController@CallLog', 'method' => 'post']) !!}
                                        <form method="post">
                                             {{ csrf_field() }}
                                            <div class="modal-body" style="background-color: #f5f5f5;">
                                                <input type="hidden" name="lead_id" id="lead_id">
                                                <table border="0" cellpadding="10" cellspacing="0" style="width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <th class="th_width_40">Call Type*</th>
                                                            <th class="th_width_60">
                                                                <select class="form-control" id="call_type" name="call_type" style="background: #ffffff;">
                                                                    <option value="Outgoing">Outgoing</option>
                                                                    <option value="Incoming">Incoming</option>
                                                                </select>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="th_width_40">Outcome</th>
                                                            <th class="th_width_60">
                                                                <select class="js-example-basic-single form-control" name="outcomes" id="outcomes">
                                                                    <option value="">outcomes</option>
                                                                    <option value="Interested">Interested</option>
                                                                    <option value="Left Message">Left Message</option>
                                                                    <option value="No response">No response</option>
                                                                    <option value="Not interested">Not interested</option>
                                                                    <option value="Not able to reach">Not able to reach</option>
                                                                </select>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="th_width_40">Associate this phone call with?*</th>
                                                            <th class="th_width_60">
                                                                <select id="associate_call" name="associate_call" class="form-control" required="" style="background: #ffffff;">
                                                                    <option value="Existing Lead">Existing Lead</option>
                                                                    <option value="New Lead">New Lead</option>
                                                                    <option value="Existing Contact">Existing Contact</option>
                                                                    <option value="New Contact">New Contact</option>
                                                                    <option value="Existing Account">Existing Account</option>
                                                                    <option value="Existing Deal">Existing Deal</option>
                                                                </select>
                                                            </th>
                                                        </tr>
                                                        <tr class="name_hidden">
                                                            <th class="th_width_40">Name*</th>
                                                            <th class="th_width_60">
                                                                <input type="text" name="call_log_name" id="call_log_name" class="form-control" readonly="" style="background: #ffffff;">
                                                            </th>
                                                        </tr>
                                                        <tr class="hidden">
                                                            <th class="th_width_40">First Name</th>
                                                            <th class="th_width_60">
                                                                <input type="text" name="first_name_calllog" id="first_name_calllog" class="form-control" placeholder="Enter first name">
                                                            </th>
                                                        </tr>
                                                        <tr class="hidden">
                                                            <th class="th_width_40">Last Name*</th>
                                                            <th class="th_width_60">
                                                                <input type="text" name="last_name_calllog" id="last_name_calllog" class="form-control" placeholder="Enter last name">
                                                            </th>
                                                        </tr>
                                                        <tr class="hidden">
                                                            <th class="th_width_40">Company Name</th>
                                                            <th class="th_width_60">
                                                                <input type="text" name="company_name_calllog" id="company_name_calllog" class="form-control" placeholder="Enter company name">
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div><br/>
                                            <label for="notes"><strong>Notes</strong></label>
                                                <textarea id="notes" name="notes" placeholder="Enter your notes" style="height: 100px;width: 100%;"></textarea><br/><br/><br/>
                                            <div class="modal-footer col-md-12">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary" style="float: right;">Save</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade Message" id="send_msg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="modal-header">
                                        <div class="col-md-12 modal-title" style="font-size: 20px;">Send Message<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['action' => 'LeadController@SendMsg', 'method' => 'post']) !!}
                                        <form method="post">
                                             {{ csrf_field() }}
                                            <div class="modal-body">
                                                <input type="hidden" name="lead_id" id="lead_id">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="sms_template">Select SMS Template</label>
                                                    <select id="sms_template" name="sms_template" class="form-control">
                                                        <option value="">Select Template</option>
                                                        @if(!$sms_template->isempty())
                                                        @foreach($sms_template as $smstemp)
                                                        <option value="{{$smstemp->id}}">{{$smstemp->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <!-- <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">From</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="msg_from" id="msg_form">
                                                </div> -->
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">To</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="msg_to" id="msg_to">
                                                </div>
                                                <label for="msg_text">Message</label>
                                                <textarea id="msg_text" name="msg_text" style="height: 100px;width: 100%;" placeholder="Send Message"></textarea>
                                            </div>
                                            <div class="modal-footer col-md-12">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary" style="float: right;">Send</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade Unqualified" id="unqualified" tabindex="-1" role="dialog" aria-lavelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto; opacity: 1">
                            
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="modal-header">
                                        <div class="col-md-12 modal-title" style="font-size: 20px;">Stage Unqualified<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['action' => 'LeadController@Unqui_reason', 'method' => 'post']) !!}
                                        <form method="post">
                                             {{ csrf_field() }}
                                            <div class="modal-body">
                                                <input type="hidden" name="lead_id" id="lead_id">
                                                <label for="stage">Stage Selected</label>
                                                <select class="form-control"id="6" name="uni_val">
                                                    <option value="6" >Unqualified</option>
                                                </select>
                                                <label for="unq_reason">Unqualified Reason*</label>
                                                
                                                <select id="unq_reason" name="unq_reason" class="js-example-basic-single" required="">
                                                    <option value="">Select Reason</option>
                                                    <option value="Junk Lead">Junk Lead</option>
                                                    <option value="Unable to reach">Unable to reach</option>
                                                    <option value="Not interested">Not interested</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer col-md-12">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary" style="float: right;">Submit</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade Converted" id="converted" tabindex="-1" role="dialog" aria-lavelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
                            
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="modal-header">
                                        <div class="col-md-12 modal-title" style="font-size: 20px;">Billing Details<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                                    </div>
                                    <div class="card-body">
                                        {!! Form::open(['action' => 'LeadController@Converted', 'method' => 'post']) !!}
                                        <form method="post">
                                             {{ csrf_field() }}
                                            <div class="modal-body">
                                                <input type="hidden" name="lead_id" id="lead_id">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="stage">Stage Selected</label>
                                                        <select class="form-control"id="7" name="con_val">
                                                            <option value="7">Converted</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="company_name_converted">Company Name*</label>
                                                        <input type="text" name="company_name_converted" id="company_name_converted" class="form-control" placeholder="Enter Company Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="first_name_c">First Name*</label>
                                                        <input type="text" name="first_name_c" id="first_name_c" class="form-control" placeholder="Enter Your First Name" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="last_name_c">Last Name*</label>
                                                        <input type="text" name="last_name_c" id="last_name_c" class="form-control" placeholder="Enter Your Last Name" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="gst_no">GST No*</label>
                                                        <input type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter GST No" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="mobile_no">Mobile No*</label>
                                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Enter Mobile No" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email_c">Email Address*</label>
                                                        <input type="text" name="email_c" id="email_c" class="form-control" placeholder="Enter Email Address" required="">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="address_c">Address*</label>
                                                        <input type="text" name="address_c" id="address_c" class="form-control" placeholder="Enter Your Address" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer col-md-12">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary" style="float: right;">Submit</button>
                                                    </div>
                                                </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- add reminder -->
                        <div class="modal fade Reminder" id="add_reminder_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Reminder</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {!! Form::open(['action' => 'LeadController@Reminder', 'method' => 'post']) !!}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-2 form-group mb-3"> 
                                                <input type="hidden" name="lead_id" id="lead_id">
                                            </div>
                                            <div class="col-md-8 form-group mb-3">
                                                <label for="firstName1">Reminder Date</label> 
                                                <input type="date" class="form-control" placeholder="yyyy-mm-dd" name="startdate">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 form-group mb-3"> </div>

                                            <div class="col-md-8 form-group mb-3">
                                                <label for="firstName1">Reminder Time</label> 
                                                <input  placeholder="Followup Time" type="text"  size="10"  data-rel="timepicker" id="timepicker1" name="starttime" data-template="dropdown" data-maxHours="24" data-show-meridian="false" data-minute-step="10" class="form-control" /> 
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 form-group mb-3"> </div>

                                            <div class="col-md-8 form-group mb-3">
                                                <label for="firstName1">Title</label> 
                                                <input  placeholder="Add Title" type="text"  size="10" id="title" name="title" class="form-control" /> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2 form-group mb-3"> </div>

                                            <div class="col-md-8 form-group mb-3">
                                                <label for="firstName1">Task</label> 
                                                <textarea id="task" name="task" class="form-control" placeholder="Add Description"></textarea>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <!-- end of add reminder -->

                        <!-- Add Proposal -->
                        <div class="modal fade Proposal" id="add_proposal_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Proposal</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {!! Form::open(['action' => 'LeadController@AddProposal', 'method' => 'post','autocomplete' => 'off']) !!} 
                                    <div class="modal-body">
                                       <div class="row">
                                        <input type="hidden" name="lead_id" id="lead_id">
                                            <div class="col-md-4 form-group mb-3">
                                                <label for="subject">Subject*</label>
                                                <input type="text" class="form-control" id="subject" placeholder="subject" name="subject">
                                                <p class="text-danger">{!! !empty($messages) ? $messages->first('subject', ':message') : '' !!}</p>
                                            </div>

                                            <div class="col-md-4 form-group mb-3">
                                                <label for="date">Date*</label>
                                                <input type="date" class="form-control" id="date" name="date">
                                                <p class="text-danger">{!! !empty($messages) ? $messages->first('date', ':message') : '' !!}</p>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="products">Products</label>
                                                    <div class="row">
                                                         <section class="container col-xs-12">
                                                            <div class="table table-responsive">
                                                            <!-- <h4>Select Details</h4> -->
                                                            <table id="ppsale" class="table table-striped table-bordered" border="0">
                                                              <thead>
                                                                  <td>Select Products</td>
                                                                  <td>Quantity</td>
                                                                  <td>Rate</td>
                                                                  <td>Tax</td>
                                                                  <td>Amount</td>
                                                              </thead>
                                                              <tbody id="TextBoxContainer">
                                                                <td style="width: 20%;">
                                                                  <select name="products[]" id="products" class="form-control js-example-basic-single products">
                                                                      <option>Select Products</option>
                                                                      @if(!empty($products))
                                                                        @foreach($products as $prod )
                                                                            <option value="{{$prod->id}}">{{$prod->name}}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif 
                                                                  </select> 
                                                                </td>
                                                                  <td>
                                                                    <input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="1" />  
                                                                  </td>
                                                                  <td>
                                                                    <input type="text" name="rate[]" id="rate" placeholder="Rate" class="rate form-control">
                                                                  </td>
                                                                  <td>
                                                                      <!-- <input type="text" name="tax[]" id="tax" class="tax form-control" placeholder="Tax"> -->
                                                                      <select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple="">
                                                                          <option value="0">No Tax</option>
                                                                          <option value="5.00">5.00%</option>
                                                                          <option value="10.00">10.00%</option>
                                                                          <option value="18.00">18.00%</option>
                                                                      </select>
                                                                      <input type="hidden" name="tax_amount[]" id="tax_amount" class="tax_amount">
                                                                  </td>
                                                                  <td>
                                                                      <input type="text" name="amount[]" id="amount" class="form-control amount" placeholder="Amount" readonly="" /> 
                                                                  </td>
                                                                </tr>
                                                              </tbody>
                                                              <tfoot>
                                                                <tr>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th>
                                                                        <label for="discount">Discount</label>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">%</span>
                                                                            </div>
                                                                           <input type="text" name="discount" id="discount" class="form-control discount">
                                                                        </div>
                                                                    </th>
                                                                    <th>
                                                                        <label for="total_amount">Sub Amount:</label>
                                                                        <!-- <div id="total_amount" name="total_amount" style="margin-top: -27px;text-align: right;">0.00</div> -->
                                                                        <input type="text" id="total_amount" name="total_amount" style="border:none;width: 82px;text-align: right;"><br>
                                                                        <label for="dis_val">Discount:</label>
                                                                        <!-- <div id="dis_val" name="dis_val" style="margin-top: -27px;text-align: right;">-₹0.00</div> -->
                                                                        <input type="text" id="dis_val" name="dis_val" style="border:none;width: 100px;text-align: right;"><br>
                                                                        <label for="total_tax">Total Tax:</label>
                                                                        <!-- <div id="total_tax" style="margin-top: -27px;text-align: right;">0%</div> -->
                                                                        <input type="text" name="total_tax" id="total_tax" style="border:none;text-align: right;width: 100px;"><br>
                                                                        <label for="grand_total">Grand Total:</label>
                                                                       <!--  <div id="grand_total" name="grand_total" style="margin-top: -27px;text-align: right;">₹0.00</div> -->
                                                                       <input type="text" id="grand_total" name="grand_total" style="border:none;width: 82px;text-align: right;">
                                                                    </th>
                                                                  <th colspan="5">
                                                                  <button id="btnAdd" type="button" class="btn btn-success" data-toggle="tooltip" data-original-title="Add more" style="float: right;">+</button></th>
                                                                </tr>
                                                              </tfoot>
                                                            </table>
                                                            </div>
                                                          </section>
                                                    </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <!-- End of Proposal -->


                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                          <div class="card-body">
                                            <div class="ul-widget__head">
                                              <div class="ul-widget__head-label">
                                                <h3 class="ul-widget__head-title">
                                                  Recent Activities
                                                </h3>
                                              </div>
                                              <!-- <div class="ul-widget__head-toolbar">
                                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold ul-widget-nav-tabs-line" role="tablist">
                                                  <li class="nav-item">
                                                    <a class="nav-link active show" data-toggle="tab" href="#__g-widget-s6-tab1-content" role="tab" aria-selected="true">
                                                      Today
                                                    </a>
                                                  </li>
                                                  <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#__g-widget-s6-tab2-content" role="tab" aria-selected="false">
                                                      Month
                                                    </a>
                                                  </li>
                                                </ul>
                                              </div> -->
                                            </div>
                                            <div class="ul-widget__body">
                                              <div class="tab-content">
                                                <div class="tab-pane active show" id="__g-widget-s6-tab1-content">
                                                  <div class="ul-widget-s6__items">
                                                    
                                                    @foreach($recent_activities as $recent_activity)
                                                    <?php
                                                    $lead_name = '';
                                                    if ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '1') {
                                                        $lead_name = 'Became New Lead';                   
                                                    }
                                                    elseif ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '2') {
                                                        $lead_name = 'Became Contacted Lead';                   
                                                    }
                                                    elseif ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '3') {
                                                        $lead_name = 'Became Interested Lead';                   
                                                    }
                                                    elseif ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '4') {
                                                        $lead_name = 'Became Under review Lead';                   
                                                    }
                                                    elseif ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '5') {
                                                        $lead_name = 'Became Demo Lead';                   
                                                    }
                                                    elseif ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '6') {
                                                        $lead_name = 'Became Unqualified Lead';                   
                                                    }
                                                    elseif ($recent_activity->activity_name == 'lead' && $recent_activity->activity_data == '7') {
                                                        $lead_name = 'Became Converted Lead';                   
                                                    }
                                                    else{
                                                        $lead_name = $recent_activity->activity_data;
                                                    }
                                                    ?>
                                                        <div class="ul-widget-s6__item">
                                                            <span class="ul-widget-s6__badge">
                                                                <p class="badge-dot-primary ul-widget6__dot"></p>
                                                            </span>

                                                            <span class="ul-widget-s6__text">{{$lead_name}}</span>
                                                            <span class="ul-widget-s6__time">
                                                                <?php $time_ago = strtotime($recent_activity->inserted_date);
                                                                    echo to_time_ago($time_ago); ?>
                                                            </span>
                                                        </div> 
                                                    @endforeach
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card" style="height: 338px;overflow-y: auto;">
                                        <div class="card-body">
                                            <div class="ul-widget__head">
                                              <div class="ul-widget__head-label">
                                                <h3 class="ul-widget__head-title">
                                                  Recent Convertions
                                                </h3>
                                              </div>
                                            </div>
                                            <div class="ul-widget__body">
                                                @if(!$lead_mails->isempty() || !$call_logs->isempty() || !$msgs->isempty() || !$proposal->isempty())
                                                <div class="tab-content">
                                                    <div class="tab-pane active show" id="__g-widget-s7-tab1-content">
                                                        <div class="ul-widget-s7n">
                                                            @foreach($lead_mails as $lead_mail )
                                                            <div class="ul-widget-s7__items mb-4">
                                                                <span class="ul-widget-s7__item-time" style="width: 20%;font-size: .813rem;">
                                                                    <!-- <h6>{{$lead_mail->from}}<br></h6> -->
                                                                    <?php $time_ago = strtotime($lead_mail->inserted_date);
                                                                    echo to_time_ago($time_ago); ?></span>
                                                                <div class="ul-widget-s7__item-circle">
                                                                    <p class="ul-vertical-line bg-success "></p>
                                                                </div>
                                                                <div class="ul-widget-s7__item-text">
                                                                    Subject: {{$lead_mail->subject ? $lead_mail->subject : 'No Subject Found'}}
                                                                    {!!$lead_mail->body!!}
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @foreach($call_logs as $call_log)
                                                            <div class="ul-widget-s7__items mb-4">
                                                                <span class="ul-widget-s7__item-time ul-middle"  style="width: 20%;font-size: .813rem;"><?php $time_ago = strtotime($call_log->inserted_date);
                                                                    echo to_time_ago($time_ago); ?></span>
                                                                <div class="ul-widget-s7__item-circle">
                                                                    <p class="ul-vertical-line bg-danger "></p>
                                                                </div>
                                                                <div class="ul-widget-s7__item-text">
                                                                    Call Type: {{$call_log->call_type}} &nbsp;&nbsp;
                                                                    Outcomes: {{$call_log->outcomes}} <br>
                                                                    Associate Phone call: <br>
                                                                    {{$call_log->associate_call}} 
                                                                    Call Log Name: {{$call_log->call_log_name}} <br>
                                                                    Note:
                                                                    {{$call_log->notes}}
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @foreach($msgs as $msg)
                                                            <div class="ul-widget-s7__items">
                                                                <span class="ul-widget-s7__item-time ul-middle"  style="width: 20%;font-size: .813rem;"><?php $time_ago = strtotime($msg->inserted_date);
                                                                    echo to_time_ago($time_ago); ?></span>
                                                                <div class="ul-widget-s7__item-circle">
                                                                    <p class="ul-vertical-line bg-warning "></p>
                                                                </div>
                                                                <div class="ul-widget-s7__item-text">
                                                                    {{$msg->message}}
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @foreach($proposal as $pro)
                                                            <div class="ul-widget-s7__items">
                                                                <span class="ul-widget-s7__item-time ul-middle"  style="width: 20%;font-size: .813rem;"><?php $time_ago = strtotime($pro->inserted_date);
                                                                    echo to_time_ago($time_ago); ?></span>
                                                                <div class="ul-widget-s7__item-circle">
                                                                    <p class="ul-vertical-line bg-warning "></p>
                                                                </div>
                                                                <div class="ul-widget-s7__item-text">
                                                                    First Name: {{$pro->first_name}} &nbsp;&nbsp;
                                                                    Last Name: {{$pro->last_name}} <br>
                                                                    Subject: {{$pro->subject}} &nbsp;&nbsp;
                                                                    Grand Total: {{$pro->grand_total}} <br>
                                                                    Email: {{$pro->email}} &nbsp;&nbsp;
                                                                    Phone No: {{$pro->mobile_no}} <br>
                                                                    <a href="{{ route('editProposal', $pro->id) }}">Click Here to see full detail of proposal</a>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @else
                                                            <center style="margin-top: 108px;">
                                                                <h5>No Convertions found.</h5>
                                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#mail" data-id="{{$lead_data->id}}" id="sec_mail_link" style="color: blue;">Send an email</a> or <a href="javascript:void(0)" data-toggle="modal" data-target="#call_log" data-id="{{$lead_data->id}}" data-name="{{$lead_data->first_name.' '.$lead_data->last_name}}" id="sec_calllog_link" style="color: blue;">Add call log</a> or <a href="javascript:void(0)" data-toggle="modal" data-target="#send_msg" data-id="{{$lead_data->id}}" data-name="{{$lead_data->first_name.' '.$lead_data->last_name}}" id="sec_msg_link" style="color: blue;">Send message</a>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>  
                                    </div>
                                </div>
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
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>

<script type="text/javascript">
    $('#timepicker1').timepicker();
    $(document).ready(function() {
        //alert($('#level').val());
        var level = $('#level').val();
        for (var i = 1; i <= level; i++) {
            $('#'+i).css('background-color','green');
        }
    });
</script>

<script type="text/javascript">
    $('.edit_data').click(function(){
        $('#sub_lead').css('display','block');
        $(this).css({"border-style":"1px solid;"});
        $('#first_name').attr("readonly", false); 
        $('#last_name').attr("readonly", false); 
        $('#company_name').attr("readonly", false); 
        $('#email').attr("readonly", false); 
        $('#department').attr("readonly", false); 
        $(':radio:not(:checked)').attr('disabled', false);
        $('#address').attr("readonly", false); 
        $('#work').attr("readonly", false); 
    })
</script>

<script> 
        $(document).ready(function(){ 

            $("#mail_modal").click(function(){
                myid = $(this).data('id');
                email = $(this).data('email');
                //alert(myid);
                $(".Mail").animate({width: 'toggle'}, "slow");
                $(".Mail #lead_id").val(myid);
                $(".Mail #to").val(email);
            });
            
            $(".ql-editor").on("keyup", function(){ 
                /*alert($(this).html());*/
                var data = $(this).html();
                $("#mail_body").html(data);
            }); 

            $("#sec_mail_link").click(function(){
                myid = $(this).data('id');
                //alert(myid);
                $(".Mail").animate({width: 'toggle'}, "slow");
                $(".Mail #lead_id").val(myid);
            });

            $('#call_log_modal').click(function(){
                $('.js-example-basic-single').select2({dropdownParent: $("#call_log")});
                myid = $(this).data('id');
                myname = $(this).data('name');
                //alert(myname);
                $(".CallLog").animate({width: 'toggle'}, "slow");
                $(".CallLog #lead_id").val(myid);
                $(".CallLog #call_log_name").val(myname);
                $('.hidden').hide();
                $('.name_hidden').show();
            });

            $("#sec_calllog_link").click(function(){
                $('.js-example-basic-single').select2({dropdownParent: $("#call_log")});
                myid = $(this).data('id');
                myname = $(this).data('name');
                //alert(myname);
                $(".CallLog").animate({width: 'toggle'}, "slow");
                $(".CallLog #lead_id").val(myid);
                $(".CallLog #call_log_name").val(myname);
                $('.hidden').hide();
                $('.name_hidden').show();
            });

            $("#associate_call").change(function(){
                $('#call_log_name').attr("readonly", false);
                $('#call_log_name').val('');

                if ($(this).val() == 'Existing Contact') {
                    $('#call_log_name').attr("placeholder", "Enter last Name");    
                    $('.hidden').hide();
                    $('.name_hidden').show();
                }else if ($(this).val() == 'New Contact') {
                    $('#call_log_name').attr("placeholder", "Enter a contact Name"); 
                    $('.hidden').hide();
                    $('.name_hidden').show();   
                }else if ($(this).val() == 'Existing Account') {
                    $('.hidden').hide();
                    $('.name_hidden').show();
                    $('#call_log_name').attr("placeholder", "Enter an account Name");  
                }else if ($(this).val() == 'Existing Deal'){
                    $('.hidden').hide();
                    $('.name_hidden').show();
                    $('#call_log_name').attr("placeholder", "Enter a deal Name"); 
                }else if($(this).val() == 'New Lead') {
                    $('.hidden').show();
                    $('.name_hidden').hide();
                    $('#last_name_calllog').attr('required',true);
                }else{
                    $('.name_hidden').show();
                    $('#call_log_name').attr("readonly", true); 
                    $('#call_log_name').attr("placeholder", "Enter last Name"); 
                    $('.hidden').hide();
                }
                
            });

            $('#sec_msg_link').click(function(){
                myid = $(this).data('id');
                $(".Message #lead_id").val(myid);
                $(".Message").animate({width: 'toggle'}, "slow");
            });

            $('#msg_modal').click(function(){
                myid = $(this).data('id');
                $(".Message #lead_id").val(myid);
                $(".Message").animate({width: 'toggle'}, "slow");
            });

            $('#remainder_modal').click(function(){
                myid = $(this).data('id');
                $(".Reminder #lead_id").val(myid);
            });

            $('#proposal_modal').click(function(){
                myid = $(this).data('id');
                $(".Proposal #lead_id").val(myid);
            });

            /*$('#notes').click(function(){
                //alert('notes');
                myid = $(this).data('id');
                $(".note_msg #lead_id").val(myid);
                $('.note_msg').show();
            });*/

            /*$('.lead_stage_last').change(function(e){
                e.preventDefault();
                if ($(this).val() == 6) {
                    alert('c');
                }else if ($(this).val() == 7) {
                    alert('u');
                    $(".Unqualified").animate({width: 'toggle'}, "slow");
                    //$('#unqualified').animate('slow');

                }
            });*/

            $('.edit_per_note').click(function(){
                $('#add_note').css('display','none');
                $('.edit_note').css('display','block');
                myid = $(this).data('id');
                $(".edit_note #note_id").val(myid);
                note = $(this).data('note');
                $(".edit_note #edit_note_msg").val(note);
            });

           /* $('.lead_stage_last').on('change', function (e) {
                if ($(this).val() == 7) {
                    $("#unqualified").modal("show");
                }
            });*/

            $('#6').click(function(){
                $('.js-example-basic-single').select2({dropdownParent: $("#unqualified")});
                myid = $(this).data('id');
                $(".Unqualified #lead_id").val(myid);
                $(".Unqualified").animate({width: 'toggle'}, "slow");
            });

            $('#7').click(function(){
                myid = $(this).data('id');
                $(".Converted #lead_id").val(myid);
                $(".Converted").animate({width: 'toggle'}, "slow");
            });
        }); 
</script>
<script type="text/javascript">
    $('#email_template').change(function(){
        //alert($(this).val());
        myid = $(this).val();
        jQuery.ajax({
            type: "POST",
            url: '{{ URL::route("SelectMailTemp") }}',
            dataType: 'text',
            data: {myid:myid},
            success: function(mail_template_data) 
            {
                //console.log(mail_template_data);
                var obj = jQuery.parseJSON(mail_template_data);
                //console.log(obj);
                $(".Mail #subject").val(obj[0].subject);
                $(".Mail .ql-editor").html(obj[0].body);
                $(".Mail #mail_body").val(obj[0].body);
            }
        });
    });
</script>
<script type="text/javascript">
    $('#sms_template').change(function(){
        //alert($(this).val());
        myid = $(this).val();
        jQuery.ajax({
            type: "POST",
            url: '{{ URL::route("SelectSMSTemp") }}',
            dataType: 'text',
            data: {myid:myid},
            success: function(sms_template_data) 
            {
                //console.log(mail_template_data);
                var obj = jQuery.parseJSON(sms_template_data);
                //console.log(obj);
                $(".Message #msg_text").html(obj[0].body);
            }
        });
    });
</script> 

<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
        $('.js-example-basic-multiple').select2();
        
        $("#btnAdd").bind("click", function () {
            var div = $("<tr />");
            div.html(GetDynamicTextBox(""));
            $("#TextBoxContainer").append(div);
            $('.js-example-basic-single').select2();
            $('.js-example-basic-multiple').select2();
            
        });
        $("body").on("click", ".remove", function () {

            var sub = $(this).closest("tr").find("input.amount").val();
            if (sub != '') {
                $(this).closest("tr").remove();
                total_amount();
                discount();
               total_tax();
            }else{
                $(this).closest("tr").remove();
                total_amount();
                discount();
                total_tax();
            }
          
        });

        $("body").on("change", ".products", function () {
            //alert($(this).val());
            var pro_id = $(this).val();
            var thiss = $(this);
            //alert(thiss);
            jQuery.ajax({
                type: "POST",
                url: '{{ URL::route("ProductAmount") }}',
                dataType: 'text',
                data: {pro_id:pro_id},
                success: function(data) 
                {
                    //console.log(data);
                    var obj = jQuery.parseJSON(data);
                    console.log(obj[0].selling_cost);
                   
                    thiss.closest("tr").find("input.rate").val(parseFloat(obj[0].selling_cost).toFixed(2));
                    
                    thiss.closest("tr").find("input.amount").val(parseFloat(obj[0].selling_cost).toFixed(2));

                    total_amount();
                    discount();
                    total_tax();
                    /*$('.pro_amount').val(obj[0].selling_cost);*/
                }
            });
        });

        $("body").on("change", ".quantity", function () {
            //alert($(this).val());
            var quantity = $(this).val();
            var am = $(this).closest("tr").find("input.rate").val();
            var sub_amount = quantity * am;
            //alert(quantity*am);
            $(this).closest("tr").find("input.amount").val(parseFloat(sub_amount).toFixed(2));
            total_amount();
            discount();
        });

        $("body").on("change",".tax",function(){
            var thisss = $(this);
            var tax = $(this).val();
            var am = 0.0;
            var amount_tax = 0.0;
            var total_tax = 0.0;
            //alert(tax);
            $.each(tax,function(index,data){
                //alert(data);
                total_tax += Number(data);
            });
            am = thisss.closest("tr").find("input.amount").val();
            amount_tax = parseFloat((am*total_tax)/100).toFixed(2);
            //alert(total_tax);
            $(this).closest("td").find("input.tax_amount").val(parseFloat(amount_tax).toFixed(2));
            $('#total_tax').val(parseFloat(amount_tax).toFixed(2));
            //$('#total_tax').html(tax+"%");
        });

        $("body").on("keyup",".rate",function(){
            var rate = $(this).closest("tr").find("input.rate").val();
            $(this).closest("tr").find("input.amount").val(parseFloat(rate).toFixed(2));
            //alert(rate);
        });

      });
      function GetDynamicTextBox(value) 
      {
          return '<td><select name="products[]" id="products" class="form-control js-example-basic-single products"><option>Select Products</option>@if(!empty($products)) @foreach($products as $prod )<option value="{{$prod->id}}">{{$prod->name}}</option>@endforeach @endif</select></td><td><input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" value="1" /></td><td><input type="text" name="rate[]" id="rate" placeholder="Rate" class="form-control rate" readonly=""></td><td><select name="tax[]" id="tax" class="tax form-control js-example-basic-multiple" multiple><option value="0">No Tax</option><option value="5.00">5.00%</option><option value="10.00">10.00%</option><option value="18.00">18.00%</option></select><input type="hidden" name="tax_amount[]" id="tax_amount" class="tax_amount"></td><td><input type="text" name="amount[]" id="amount" class="form-control amount" placeholder="Amount" readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td>';
      }

      function total_amount(){
                var sum = 0.0;
                $('.amount').each(function(){
                    //alert($(this).val());
                    sum += Number($(this).val()); 
                });
                $('#total_amount').val(parseFloat(sum).toFixed(2));
            }

    function discount(){
        $('#discount').keyup(function(){
            var discount = 0.0;
            var sub_total = $('#total_amount').val();
            //alert(sub_total);
            var dis_val = $(this).val();
            discount = parseFloat((sub_total*dis_val)/100).toFixed(2);
            var tax = $('#total_tax').val();
            //alert(tax);
            //alert(discount);
            $('#dis_val').val(discount);
            var grand_total = sub_total - discount + Number(tax);
            //alert(grand_total);
            $('#grand_total').val(parseFloat(grand_total).toFixed(2));
        });
    }

    function total_tax(){
        var tax = 0;
        $('.tax_amount').each(function(){
            tax += Number($(this).val());
        });
        //alert(tax);
        $('#total_tax').val(tax);
    };
</script>

@endsection

