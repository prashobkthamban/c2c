@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .dropdown-menu>li>a {
        margin: 4px;
        padding-bottom: 7px;
        padding-top: 7px;
        border-radius: 3px;
        line-height: 18px;
    }

    .customizer {
        z-index: 0;
    }

    .select2-container {
        width: 100% !important;
    }

    audio {
        margin-left: 82px;
    }
    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>CDR Report</h1>
</div>

<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div id="filter-panel" class="col-lg-12 col-md-12 filter-panel collapse {{count($requests) > 0 ? 'show' : ''}}">
        <div class="card mb-2">
            <div class="card-body">
                <div>
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="cdr_filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                            <div class="col-md-4" id="customer_div">
                                <label class="filter-col" for="pref-perpage">Customers</label>
                                <select name="customer" class="form-control" id="customer_id">
                                    <option value="">All</option>
                                    @if(!empty($customers))
                                    @foreach($customers as $customer )
                                    <option value="{{$customer->id}}" @if(isset($requests['customer']) && $customer->id == $requests['customer']) selected @endif>{{$customer->name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="customer" id="customer_id" value="{{Auth::user()->groupid}}" />
                            @endif
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Departments</label>
                                <select name="department" class="form-control" id="department_id">
                                    <option value="">All</option>
                                    @if(!empty($departments))
                                    @foreach($departments as $dept )
                                    <option value="{{$dept->dept_name}}" @if(isset($requests['department']) && $dept->dept_name == $requests['department']) selected @endif>{{$dept->dept_name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'reseller')
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Operators</label>
                                <select name="operator" class="form-control" id="operator_id">
                                    <option value="">All</option>
                                    @if(!empty($operators))
                                    @foreach($operators as $opr )
                                    <option value="{{$opr->id}}" @if(isset($requests['operator']) && $opr->id == $requests['operator']) selected @endif>{{$opr->opername}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @elseif(Auth::user()->usertype == 'operator')
                                <input type="hidden" name="operator" id="operator_id" value="{{Auth::user()->operator_id}}" />
                            @endif
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Cdr Tag</label>
                                {!! Form::select('tag', $tags->prepend('Select Tag', ''), isset($requests['tag']) ? $requests['tag'] : '' ,array('class' => 'form-control', 'id' => 'tag')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Status</label>
                                {!! Form::select('status', array('' => 'All', 'MISSED' => 'Missed', 'ANSWERED' => 'Answered', 'DIALING' => 'Dialing', 'LIVECALL' => 'Live call', 'AFTEROFFICE' => 'After Office'), isset($requests['status']) ? $requests['status'] : '',array('class' => 'form-control', 'id' => 'status_id')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Assigned To</label>
                                <select class="form-control" name="assigned_to" id="assigned_to_id">
                                    <option value="">All</option>
                                    @if(!empty($operators))
                                    @foreach($operators as $opr )
                                    <option value="{{$opr->id}}" @if(isset($requests['assigned_to']) && $opr->id == $requests['assigned_to']) selected @endif>{{$opr->opername}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Dnid Name</label>
                                <select class="form-control" name="did_no" id="did_no">
                                    <option value="">All</option>
                                    @if(!empty($dnidnames))
                                    @foreach($dnidnames as $dnid )
                                    <option value="{{$dnid->did_no}}" @if(isset($requests['did_no']) && $dnid->did_no == $requests['did_no']) selected @endif>{{$dnid->did_no}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-search">By Caller Number</label>
                                <input type="text" class="form-control input-sm" id="caller_number" name="caller_number" value="@if(isset($requests['caller_number'])) {{$requests['caller_number']}} @endif">
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Date</label>
                                <select class="form-control" name="date" id="date_select">
                                    @foreach($dateOptions as $key => $val )
                                    <option value="{{$key}}" @if(isset($requests['date']) && $key == $requests['date']) selected @endif>{{$val}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 custom_date_div" @if((isset($requests['date']) && $requests['date'] != 'custom') || !isset($requests['date'])) style="display: none;" @endif>
                                <label>Start Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" value="@if(isset($requests['start_date'])) {{$requests['start_date']}} @endif" name="start_date" id="start_date">
                                    <div class="input-group-append">
                                        <label class="btn btn-secondary" for="start_date">
                                            <i class="icon-regular i-Calendar-4"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 custom_date_div" @if((isset($requests['date']) && $requests['date'] != 'custom') || !isset($requests['date'])) style="display: none;" @endif>
                                <label>End Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" value="@if(isset($requests['end_date'])) {{$requests['end_date']}} @endif" name="end_date" id="end_date">
                                    <div class="input-group-append">
                                        <label class="btn btn-secondary" for="end_date">
                                            <i class="icon-regular i-Calendar-4"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 24px;">
                                <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                <a href="{{url('cdrreport')}}" class="btn btn-outline-secondary" name="btn">Clear</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@if(Auth::user()->usertype == 'groupadmin')
<!--
        -->
@endif
<div class="row mb-4" id="div_table">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cdr_table" class="display table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                @if(Auth::user()->usertype == 'groupadmin')
                                <th class="noExport"><input type="checkbox" name="allselect" id="allselect" value="yes" onclick="selectAll();"></th>
                                @elseif(Auth::user()->usertype == 'admin')
                                <th>Customer</th>
                                @endif
                                @if(Auth::user()->usertype == 'reseller')
                                <th>Account Name</th>
                                @endif
                                <th>Caller Id</th>
                                <th>Date & Time</th>
                                @if(Auth::user()->usertype == 'admin')
                                <th>Duration</th>
                                <th>Coin</th>
                                @endif
                                <th>Status</th>
                                <th>Department</th>
                                <th>Agent</th>
                                <th class="noExport">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($result))
                            @foreach($result as $row )
                            <tr data-toggle="collapse" data-target="#accordion_{{$row->cdrid}}" class="clickable" id="row_{{ $row->cdrid }}" data-cdr-id="{{$row->cdrid}}">
                                @if(Auth::user()->usertype == 'groupadmin')
                                <td><input type="checkbox" name="cdr_checkbox" id="{{$row->cdrid}}" value="{{$row->cdrid}}" class="allselect"></td>
                                @elseif(Auth::user()->usertype == 'admin')
                                <td>{{$row->name}}</td>
                                @endif
                                @if(Auth::user()->usertype == 'reseller')
                                <td>{{ $row->accountGroup ? $row->accountGroup->name : '' }}</td>
                                @endif
                                <td id="caller_{{$row->cdrid}}">
                                    @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator')
                                    <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#dial_modal" title="{{ $row->number }}" onClick="cdrDial({{$row->number}});return false;"><i class="i-Telephone"></i>{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                    @elseif(Auth::user()->usertype=='admin' || Auth::user()->usertype=='reseller')
                                    {{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}
                                    @else
                                    <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#dial_modal" title="{{ $row->number }}" onClick="cdrDial({{$row->number}});return false;"><i class="i-Telephone"></i>{{ $row->contacts != null && $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                    @endif
                                </td>
                                <td>{{$row->datetime}}</td>
                                @if(Auth::user()->usertype == 'admin')
                                <td>{{$row->firstleg."(".$row->secondleg.")"}}</td>
                                <td>{{$row->creditused}}</td>
                                @endif
                                <td class="show-cdr-sub"><a href="javascript:void(0)" data-toggle="modal" data-target="#call_details_modal">{{$row->status}}</a></td>
                                <td>{{$row->deptname}}</td>
                                <td>{{ $row->operatorAccount ? $row->operatorAccount->opername : '' }}</td>
                                <td>
                                    @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator')
                                    <a class="btn bg-gray-100 more-details" title="More Details" data-tag="{{$row->tag}}" data-operatorname="{{$row->operatorAssigned ? $row->operatorAssigned->opername : ''}}" onClick="moreOption({{$row->cdrid}},'{{$row->did_no ? $row->did_no : 0}}','{{$row->firstleg."(".$row->secondleg.")"}}','{{$row->creditused ? $row->creditused : 0}}');return false;"><i class="i-Arrow-Down-2" aria-hidden="true"></i></a>
                                    @endif
                                    @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator' || Auth::user()->usertype=='reseller')
                                    @if(!empty($row->recordedfilename))
                                        @if(empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->play == '1'))
                                        <a href="#" class="btn bg-gray-100 play_audio" title="Play Audio" data-toggle="modal" data-target="#play_modal" data-file="{{$row->recordedfilename}}" id="play_{{$row->groupid}}"><i class="i-Play-Music"></i></a>
                                        @endif
                                        @if(empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->download == '1'))
                                            <a href="{{ url('download_file/' .$row->recordedfilename) }}" class="btn bg-gray-100" title="Download File">
                                                <i class="i-Download1"></i>
                                            </a>
                                        @endif
                                    @endif
                                    @endif
                                    @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator' || Auth::user()->usertype=='reseller')
                                    @if(sizeof($row['cdrNotes']) > 0)
                                    <a href="#" class="btn bg-gray-100 notes_list" title="Notes" data-toggle="modal" data-target="#notes_modal" id="notes_{{$row->uniqueid}}"><i class="i-Notepad"></i></a>
                                    @endif
                                    @if(Auth::user()->usertype=='groupadmin')
                                    <a href="" class="btn bg-gray-100" title="Assign To" data-toggle="dropdown" id="history_{{$row->number}}"><i class="  i-Add-User"></i></a>
                                    @endif
                                    @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator')
                                    <a href="" class="btn bg-gray-100 history_list" title="Call History" data-toggle="modal" data-target="#history_modal" id="history_{{$row->number}}"><i class="i-Notepad-2"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        @foreach($operators as $operator)
                                        <li>
                                            <label>{{$operator->opername}}</label>
                                            @if( $account_service['smsservice_assign_cdr'] =='Yes' || $account_service['emailservice_assign_cdr'] =='Yes')
                                                <ul>
                                                    @if($account_service['smsservice_assign_cdr'] =='Yes')
                                                    <li>
                                                        <a href="javascript:assignoper({{$row->cdrid}},{{$operator->id}},'{{$operator->opername}}','S');">Notify By SMS</a>
                                                    </li>
                                                    @endif
                                                    @if($account_service['emailservice_assign_cdr'] =='Yes')
                                                    <li>
                                                        <a href="javascript:assignoper({{$row->cdrid}},{{$operator->id}},'{{$operator->opername}}','E');">Notify By Email</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                        <?php echo '<li><a href="javascript:assignoper(' . $row->cdrid . ',0);">Unassign</a></li>'; ?>
                                    </ul>
                                    @endif

                                    <span>
                                        @if(Auth::user()->usertype=='groupadmin' || Auth::user()->usertype=='operator')
                                        <button class="btn bg-gray-100" type="button" id="action_{{$row->cdrid}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="nav-icon i-Arrow-Down-in-Circle"></i>
                                        </button>
                                        @endif
                                        <div class="dropdown-menu" aria-labelledby="action_{{$row->cdrid}}">
                                            <a class="dropdown-item edit_contact" href="#" data-toggle="modal" data-target="#contact_modal" id="contact_{{ $row->contacts && $row->contacts->id ? $row->contacts->id : ''}}" data-email="{{ $row->contacts && $row->contacts->email ? $row->contacts->email : ''}}" data-fname="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : ''}}" data-lname="{{ $row->contacts && $row->contacts->lname ? $row->contacts->lname : ''}}" data-phone="{{$row->number}}">{{isset($row->contacts->fname) ? 'Update Contact': 'Add Contact'}}</a>
                                            <a class="dropdown-item edit_tag" href="#" data-toggle="modal" data-target="#tag_modal" id="tag_{{$row->cdrid}}" data-tag="{{$row->tag}}">{{$row->tag ? 'Update Tag': 'Add Tag'}}</a>
                                            <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#add_note_modal" id="add_note_{{$row->uniqueid}}">Add Notes</a>
                                            @if(!isset($row->reminder->id))
                                            <a class="dropdown-item edit_reminder" href="#" data-toggle="modal" data-target="#add_reminder_modal" id="add_reminder_{{$row->cdrid}}">Add Reminder</a>
                                            @endif
                                        </div>
                                    </span>
                                @elseif(Auth::user()->usertype=='admin')
                                <a href="javascript:void(0)" onClick="deleteItem({{$row->cdrid}}, 'cdr')" class="text-danger mr-2">
                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                </a>
                                @endif
                                </td>
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
<!-- end of row -->

<!-- play modal -->
<div class="modal fade" id="play_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title">Dial A Number</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="play_src">
            </div>
        </div>
    </div>
</div>

<!-- dial modal -->
<div class="modal fade" id="dial_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dial A Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method' => 'post', 'id' => 'dial_form']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="number">Customer Number</label>
                        <input type="number" id="customer_number" onpaste="return false;" class="form-control" placeholder="Customer Number" name="number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Operator Number</label>
                        <input type="number" id="operator_number" onpaste="return false;" class="form-control" value="{{Auth::user()->phone_number}}" placeholder="Operator Number" name="phone">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Call First</label>
                        <label class="radio-inline"> {{ Form::radio('callf', 'cust') }} Customer</label>
                        <label class="radio-inline">{{ Form::radio('callf', 'oper', true) }} Operator</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" id="dial_submit" class="btn btn-primary" value="Dial Now" />
                <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Dial Now" />
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- end of dial modal -->

<!-- msg modal -->
<div class="modal fade" id="msg_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Send Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method' => 'post', 'id' => 'msg_form']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="number">Customer Number</label>
                        <input type="number" id="cust_m_number" onpaste="return false;" class="form-control" placeholder="Customer Number" name="number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Message</label>
                        <textarea rows="8" cols="20" class="form-control" placeholder="Message" name="message"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="msg_submit" class="btn btn-primary">Send Now</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- end of msg modal -->

<!-- graph modal -->
<div class="modal fade" id="graph_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Graph Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card mb-6">
                            <div class="card-body">
                                <div class="card-title"></div>
                                <div id="echartBar" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of graph modal -->

<!-- graph search modal -->
<div class="modal fade" id="graph_search_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Graph</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'graph_report', 'autocomplete' => 'off']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Start Date</label>
                        {!! Form::text('startdate', null, ['class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"> </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">End Date</label>
                        {!! Form::text('enddate', null, ['class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Status</label>
                        {!! Form::select('status', array('
                        ' => 'Status', 'ANSWERED' => 'Answered', 'DIALING' => 'Dialing'), null,array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"></div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Departments</label>
                        <select name="department" class="form-control">
                            <option value="">All</option>
                            @if(!empty($departments))
                            @foreach($departments as $dept )
                            <option value="{{$dept->dept_name}}">{{$dept->dept_name}}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary collapsed m-1" data-toggle="modal" data-target="#graph_modal">Graph</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- end of graph form modal -->

<!-- tag modal -->
<div class="modal fade" id="tag_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tag_title">Add Tag</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'tag_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                        {!! Form::hidden('cdrid', null, ['class' => 'form-control', 'id' => 'cdrid']) !!}
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Tag</label>
                        {!! Form::select('tag', $tags->prepend('Select Tag', ''), null,array('class' => 'form-control', 'id' => 'cdr_tag')) !!}
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
<!--end of tag modal -->

<!-- notes modal -->
<div class="modal fade" id="notes_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="notes_list_table" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Operator</th>
                            <th>Date</th>
                            <th>Comments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--end of notes modal -->

<!-- notes modal -->
<div class="modal fade" id="history_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="history_list_table" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Caller Id</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--end of notes modal -->

<!-- add note modal -->
<div class="modal fade" id="add_note_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'notes_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="uniqueid" id="uniqueid" />
                        <textarea class="form-control" rows="5" name="note" placeholder="Comment"></textarea>
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
<!-- end of add note modal -->

<!-- add reminder -->
<div class="modal fade" id="add_reminder_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Reminder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'reminder_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                        {!! Form::hidden('cdr_id', null, ['id' => 'cdr_id']) !!}
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Reminder Date</label>
                        <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="startdate">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"> </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Reminder Time</label>
                        <input placeholder="Followup Time" type="text" size="10" data-rel="timepicker" id="timepicker1" name="starttime" data-template="dropdown" data-maxHours="24" data-show-meridian="false" data-minute-step="10" class="form-control" />
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

<!-- add contact -->
<div class="modal fade" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contact_title">Add Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'contact_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3"></div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">First Name</label>
                        <input type="hidden" name="contact_id" id="contact_id">
                        <input type="hidden" name="phone" id="phone">
                        <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"> </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3"> </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Email</label>
                        <input type="email" name="email" id="emailaddress" class="form-control" placeholder="Email">
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
<!-- end of add contact -->

<!-- call details modal -->
<div class="modal fade" id="call_details_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Call Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end of dial modal -->

<!-- customize sidebar -->
@if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype=='operator')
<div class="customizer" style="top: 73px;">
    <div class="handle" title="Dial Now">
        <i data-toggle="modal" data-target="#dial_modal" class="i-Telephone"></i>
    </div>
</div>
<div class="customizer" title="Send Message" style="top: 115px;">
    <div class="handle">
        <i class="i-Email" data-toggle="modal" data-target="#msg_modal"></i>
    </div>
</div>
<div class="customizer" title="Cdr Chart" style="top: 156px;">
    <div class="handle">
        <i class="i-Bar-Chart-2
            " data-toggle="modal" data-target="#graph_search_modal"></i>
    </div>
</div>
@if(Auth::user()->usertype == 'groupadmin')
<div class="customizer" title="Assign To" style="top: 280px;">
    <div>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <div class="handle collapsed">
                <i class="i-Add-User"></i>
            </div>
        </a>
        <ul class="dropdown-menu" role="menu">
            @foreach($operators as $operator)
            <li>
                <label>{{$operator->opername}}</label>
                @if( $account_service['smsservice_assign_cdr'] =='Yes' || $account_service['emailservice_assign_cdr'] =='Yes')
                <ul>
                    @if($account_service['smsservice_assign_cdr'] =='Yes')
                    <li>
                        <a href="javascript:assignoper(0,{{$operator->id}},'{{$operator->opername}}','S');">Notify By SMS</a>
                    </li>
                    @endif
                    @if($account_service['emailservice_assign_cdr'] =='Yes')
                    <li>
                        <a href="javascript:assignoper(0,{{$operator->id}},'{{$operator->opername}}','E');">Notify By Email</a>
                    </li>
                    @endif
                </ul>
                @endif
            </li>
            @endforeach
            <?php echo '<li><a href="javascript:assignoper(0);">Unassign</a></li>'; ?>
        </ul>
    </div>
</div>
@endif
@endif
<div class="customizer" title="Search" style="{{(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype=='operator') ?  ( (!empty($operatorAccount) && $operatorAccount->edit == '0') ? 'top:198px' : 'top:239px' ) : 'top:114px'}}">
    <a href="#" data-toggle="collapse" data-target="#filter-panel">
        <div class="handle collapsed">
            <i class="i-Search-People"></i>
        </div>
    </a>
</div>
@if(empty($operatorAccount) || (!empty($operatorAccount) && $operatorAccount->edit == '1'))
<div class="customizer" title="Export Data" style="{{(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype=='operator') ? 'top:198px' : 'top:73px'}}">>
    <a href="#" onclick="exportCdr()">
        <div class="handle">
            <i class="i-Download1"></i>
        </div>
    </a>
</div>
@endif

@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
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
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">
    // $('#zero_configuration_table').DataTable( {
    //     "order": [[0, "desc" ]]
    // } );
    $('#cdr_table').DataTable({
        dom: 'Bfrtip',
        order: [[2, "desc" ]],
        buttons: []
    });
    // var table = $('#cdr_table').DataTable();

    // var data = table.buttons.exportData( {
    //     columns: ':visible'
    // } );
    //     $('#example').DataTable( {
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ]
    // } );
    $('#timepicker1').timepicker();
</script>
<script type="text/javascript">
    function moreOption(id, did_no, firstLeg, creditUsed) {
        var className = $("#second_row").attr('class');
        if (className == 'show') {
            $("#second_row").remove();
        } else {
            var tag = $('#row_' + id + ' .more-details').data('tag');
            var operName = $('#row_' + id + ' .more-details').data('operatorname');
            $('#row_' + id).after('<tr id="second_row" class="show"><td></td><td colspan="7"><span style="margin-right:100px;"><b>DNID :</b>' + did_no + '</span><span style="margin-right:100px;"><b>Duration :</b>' + firstLeg + '</span><span style="margin-right:100px;"><b>Coin :</b>' + creditUsed + '</span><span style="margin-right:100px;"><b>Assigned To :</b> <span id="assigned_' + id + '">' + operName + '</span></span><span style="margin-right:100px;"><b>Tag :</b> <span id="cdrTag_' + id + '">' + tag + '</span></span></td></tr>');
        }
    }

    function selectAll() {
        if ($('#allselect').is(":checked")) {
            $(".allselect").prop("checked", true);
        } else {
            $(".allselect").prop("checked", false);
        }
    }

    function xajax_show(id) {
        $(".cdr_form").addClass('d-none');
        $("#" + id).removeClass('d-none');
    }

    function xajax_hide() {
        $(".cdr_form").addClass('d-none');
    }

    function xajax_play(id) {
        $("#" + id).removeClass('d-none');
    }

    function cdrDial(phone) {
        $("#customer_number").val(phone);
    }

    $(document).ready(function() {
        $('.more_data').hide();
        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy'
        });
        $('.timepicker').pickatime();

        $('#dial_form').on('submit', function(e) {
            e.preventDefault();
            $('#dial_submit').val('Dialing...');
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("AddCdr") }}', // This is the url we gave in the route
                data: $('#dial_form').serialize(),
                success: function(res) { // What to do if we succeed
                    if (res.error) {
                        $.each(res.error, function(index, value) {
                            if (value.length != 0) {
                                errors += value[0];
                                errors += "</br>";
                            }
                        });
                        toastr.error(errors);
                    } else {
                        $("#dial_modal").modal('hide');
                        toastr.success(res.success);
                        setTimeout(function() {
                            location.reload(true);
                        }, 300);

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        $('#msg_form').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("SendMessage") }}', // This is the url we gave in the route
                data: $('#msg_form').serialize(),
                success: function(res) { // What to do if we succeed
                    if (res.error) {
                        $.each(res.error, function(index, value) {
                            if (value.length != 0) {
                                errors += value[0];
                                errors += "</br>";
                            }
                        });
                        toastr.error(errors);
                    } else {
                        $("#msg_modal").modal('hide');
                        toastr.success(res.success);
                        setTimeout(function() {
                            location.reload(true);
                        }, 300);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        //add reminder
        $('.reminder_form').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("addReminder") }}', // This is the url we gave in the route
                data: $('.reminder_form').serialize(),
                success: function(res) { // What to do if we succeed
                    if (res.error) {
                        $.each(res.error, function(index, value) {
                            if (value.length != 0) {
                                errors += value[0];
                                errors += "</br>";
                            }
                        });
                        toastr.error(errors);
                    } else {
                        $("#add_reminder_modal").modal('hide');
                        toastr.success(res.success);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        //show graph
        $('.graph_report').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("graphReport") }}', // This is the url we gave in the route
                data: $('.graph_report').serialize(),
                success: function(res) { // What to do if we succeed
                    console.log('res', res)
                    if (res.error) {
                        $.each(res.error, function(index, value) {
                            if (value.length != 0) {
                                errors += value[0];
                                errors += "</br>";
                            }
                        });
                        toastr.error(errors);
                    } else {
                        var answrd = Object.values(res.answered);
                        var dialed = Object.values(res.dialed);
                        var dates = Object.values(res.dates);
                        const max = res.max;
                        $("#graph_search_modal").modal('hide');
                        $("#graph_modal").modal('show');
                        var echartElemBar = document.getElementById('echartBar');
                        if (echartElemBar) {
                            var echartBar = echarts.init(echartElemBar);
                            echartBar.setOption({
                                legend: {
                                    borderRadius: 0,
                                    orient: 'horizontal',
                                    x: 'right',
                                    data: ['Dialed', 'Answered']
                                },
                                grid: {
                                    left: '8px',
                                    right: '8px',
                                    bottom: '0',
                                    containLabel: true
                                },
                                tooltip: {
                                    show: true,
                                    backgroundColor: 'rgba(0, 0, 0, .8)'
                                },
                                xAxis: [{
                                    type: 'category',
                                    data: dates,
                                    axisTick: {
                                        alignWithLabel: true
                                    },
                                    splitLine: {
                                        show: false
                                    },
                                    axisLine: {
                                        show: true
                                    }
                                }],
                                yAxis: [{
                                    type: 'value',
                                    axisLabel: {
                                        formatter: '{value}'
                                    },
                                    min: 0,
                                    max: 10,
                                    interval: 2,
                                    axisLine: {
                                        show: false
                                    },
                                    splitLine: {
                                        show: true,
                                        interval: 'auto'
                                    }
                                }],
                                series: [{
                                    name: 'Dialed',
                                    data: dialed,
                                    label: {
                                        show: false,
                                        color: '#0168c1'
                                    },
                                    type: 'bar',
                                    barGap: 0,
                                    color: '#bcbbdd',
                                    smooth: true,
                                    itemStyle: {
                                        emphasis: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowOffsetY: -2,
                                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                                        }
                                    }
                                }, {
                                    name: 'Answered',
                                    data: answrd,
                                    label: {
                                        show: false,
                                        color: '#639'
                                    },
                                    type: 'bar',
                                    color: '#7569b3',
                                    smooth: true,
                                    itemStyle: {
                                        emphasis: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowOffsetY: -2,
                                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                                        }
                                    }
                                }]
                            });
                            $(window).on('resize', function() {
                                setTimeout(function() {
                                    echartBar.resize();
                                }, 500);
                            });
                        }
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        //filter operation
        $('#graph').on('change', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "GET",
                url: '/search_cdr/' + $(this).val(), // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    $("#graph_modal").modal('show');
                }
            });
        });

        $('.close').on('click', function(e) {
            $(".graph_report")[0].reset();
        });

        $('.edit_tag').on('click', function(e) {
            var id = $(this).attr("id");
            var tag = $(this).attr("data-tag");
            var cdrid = id.replace("tag_", "");
            console.log('tag : '+tag);
            $("#cdrid").val(cdrid);
            if (tag != '') {
                $("#tag_title").text("Update Tag");
                $("#cdr_tag").val(tag);
            } else {
                $("#tag_title").text("Add Tag");
                $("#cdr_tag").val('');
            }
        });

        $('.edit_contact').on('click', function(e) {
            var id = $(this).attr("id");
            var email = $(this).attr("data-email");
            var fname = $(this).attr("data-fname");
            var lname = $(this).attr("data-lname");
            var phone = $(this).attr("data-phone");
            var contactid = id.replace("contact_", "");
            $("#contact_id").val(contactid);
            $("#emailaddress").val(email);
            $("#fname").val(fname);
            $("#lname").val(lname);
            $("#phone").val(phone);
            if (contactid != '') {
                $("#contact_title").text("Update Contact");
            } else {
                $("#contact_title").text("Add Contact");
            }

        });

        $('.play_audio').on('click', function(e) {
            var file = $(this).attr("data-file");
            file = 'voicefiles/' + file;
            $("#play_src").html('<audio controls id="audioSource"><source src="' + file + '" type="video/mp4"></source></audio>');
        });

        $(document).on('hide.bs.modal', '#play_modal', function() {
            $("#audioSource").remove();
        });

        $('.edit_reminder').on('click', function(e) {
            var id = $(this).attr("id");
            var cdrid = id.replace("add_reminder_", "");
            $("#cdr_id").val(cdrid);
        });

        $('.add_note').on('click', function(e) {
            var id = $(this).attr("id");
            var uniqueid = id.replace("add_note_", "");
            $("#uniqueid").val(uniqueid);
        });

        $('.notes_list').on('click', function(e) {
            var id = $(this).attr("id");
            var uniqueid = id.replace("notes_", "");
            $.ajax({
                url: '/notes/' + uniqueid, // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    var response = JSON.stringify(res);
                    var noteHTML = "";
                    if (res.length > 0) {
                        $.each(res, function(idx, obj) {
                            noteHTML += "<tr class='cmnt_row_" + obj.id + "'>";
                            noteHTML += "<td>" + obj.operator + "</td>";
                            noteHTML += "<td>" + obj.datetime + "</td>";
                            noteHTML += "<td>" + obj.note + "</td>";
                            noteHTML += "<td><a href='#' class='text-danger mr-2 delete_comment' id='" + obj.id + "'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                            noteHTML += "</tr>";

                        });
                    } else {
                        noteHTML += "<tr><td colspan='4'><center>No Data Found</center></td></tr>";
                    }
                    $("#notes_list_table tbody").html(noteHTML);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });

        });

        $('.history_list').on('click', function(e) {
            var id = $(this).attr("id");
            var number = id.replace("history_", "");
            $.ajax({
                url: '/call_history/' + number, // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    var response = JSON.stringify(res);
                    var historyHTML = "";
                    if (res.length > 0) {
                        $.each(res, function(idx, obj) {
                            historyHTML += "<tr>";
                            historyHTML += "<td>" + ++idx + "</td>";
                            historyHTML += "<td>" + obj.number + "</td>";
                            historyHTML += "<td>" + obj.datetime + "</td>";
                            historyHTML += "<td>" + obj.status + "</td>";
                            historyHTML += "</tr>";

                        });
                    } else {
                        historyHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
                    }
                    $("#history_list_table tbody").html(historyHTML);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });

        });

    });

    $('.add_contact').click(function() {
        $("#contact_form").removeClass('hide');
    });

    $(document).on("change", "#date_select", function() {
        var date_val = $(this).val();
        $(".custom_date_div").hide();
        if (date_val == 'custom') {
            $(".custom_date_div").show();
        }
    });

    $(document).on("click", "#report_search_button", function() {
        get_report_search(1);
    });

    $(document).on("click", "#btn_refresh", function() {
        $("#cdr_filter_form")[0].reset();
        get_report_search(1);
    });

    function get_report_search(page) {
        $.ajax({
            type: 'POST',
            url: "{{ url('getreportsearch') }}",
            data: $("#cdr_filter_form").serialize() + '&page=' + page,
            success: function(data) {
                if (data.success == 1) {
                    $("#div_table").replaceWith(data.view);
                    $('#cdr_table').DataTable();
                } else if (data.error == 1) {
                    alert(data.errormsg);
                }
            }

        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", ".remove", function() {

            var sub = $(this).closest("tr").find("input.amount").val();
            if (sub != '') {
                $(this).closest("tr").remove();
                total_amount();
            } else {
                $(this).closest("tr").remove();
                total_amount();
            }

        });
        $("body").on("change", ".quantity", function() {
            //alert($(this).val());
            var quantity = $(this).val();
            var am = $(this).closest("tr").find("input.pro_amount").val();
            var sub_amount = quantity * am;
            //alert(quantity*am);
            $(this).closest("tr").find("input.sub_amount").val(parseFloat(sub_amount).toFixed(2));
            total_amount();
        });
    });

    function GetDynamicTextBox(value) {
        return '<td><select name="products[]" id="products" class="form-control js-example-basic-single products"><option>Select Products</option>@if(!empty($products)) @foreach($products as $prod )<option value="{{$prod->id}}">{{$prod->name}}</option>@endforeach @endif</select><input type="hidden" name="pro_amount[]" id="pro_amount" class="form-control pro_amount"> </td><td><input type="number" name="quantity[]" id="quantity" class="form-control quantity"placeholder="Enter Quantity" min="1" /></td><td><input type="text" name="sub_amount[]" id="sub_amount" class="form-control sub_amount" placeholder="Sub Amount" readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td>';
    }

    function total_amount() {
        var sum = 0.0;
        $('.sub_amount').each(function() {
            //alert($(this).val());
            sum += Number($(this).val());
        });
        $('#total_amount').val(parseFloat(sum).toFixed(2));
    }

    function assignoper(cdr_id, operator_id, opername,type) {
        var cdrIds = [];
        if(cdr_id == '0') {
            $(".allselect:checked").each(function() {
                cdrIds.push($(this).attr('id'));
            })
        } else {
            cdrIds.push(cdr_id);
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('assign_cdr') }}",
            data: {
                'cdr_id': cdrIds,
                'opr_id': operator_id,
                'type': type,
            },
            success: function(data) {
                if (data.status) {
                    cdrIds.forEach(function (cdrId) {
                        $('#row_' + cdrId + ' .more-details').data('operatorname', opername);
                        $("#assigned_"+cdrId).text(opername);
                    });
                    toastr.success(data.message);   
                } else {
                    toastr.error('Some errors are occured');
                }
            }

        });
    }
    $(".show-cdr-sub").on("click", function() {
        var data = {'cdrId': $(this).parent('tr').data('cdr-id')};
        var url = "{{ url('cdrreport/call_details') }}";
        ajaxCall(url, data)
        .then(function(result) {
            if(result.status) {
                $('#call_details_modal .modal-body').html(result.content);
            } else {
                toastr.error(result.message);
            }
        });
    });
    
    if ($("#customer_div").length) {
        if ($("#customer_id").val() == "") {
            resetData();
        }
    }
    $("#customer_id").on("change", function() {
        if ($("#customer_id").val() == "") {
            resetData();
        } else {
            fetchDepartments();
            fetchOperators();
            fetchTags();
            fetchDidNumbers();
        }
    })

    function resetData() {
        $("#department_id").find('option').not(':first').remove();
        $("#department_id").val("");
        $("#operator_id").find('option').not(':first').remove();
        $("#operator_id").val("");
        $("#tag").find('option').not(':first').remove();
        $("#tag").val("");
        $("#assigned_to_id").find('option').not(':first').remove();
        $("#assigned_to_id").val("");
        $("#did_no").find('option').not(':first').remove();
        $("#did_no").val("");
    }

    function fetchDepartments() {
        var data = {'groupId': $("#customer_id").val()};
        var url = "{{ url('fetch_departments') }}";
        ajaxCall(url, data)
        .then(function(result) {
            if(result.status) {
                var html = '<option value="">All</option>';
                result.data.forEach(function(data) {
                    html += '<option value="'+data.dept_name+'" >'+data.dept_name+'</option>';
                });
                $("#department_id").html(html);
            } else {
                toastr.error(result.message);
            }
        });
    }

    function fetchOperators() {
        var data = {'groupId': $("#customer_id").val()};
        var url = "{{ url('fetch_operators') }}";
        ajaxCall(url, data)
        .then(function(result) {
            if(result.status) {
                var html = '<option value="">All</option>';
                result.data.forEach(function(data) {
                    html += '<option value="'+data.id+'" >'+data.opername+'</option>';
                });
                $("#operator_id").html(html);
                $("#assigned_to_id").html(html);
            } else {
                toastr.error(result.message);
            }
        });
    }

    function fetchTags() {
        var data = {'groupId': $("#customer_id").val()};
        var url = "{{ url('fetch_tags') }}";
        ajaxCall(url, data)
        .then(function(result) {
            if(result.status) {
                var html = '<option value="">All</option>';
                $.each(result.data, function(key, value) {
                    html += '<option value="'+key+'" >'+value+'</option>';
                });
                $("#tag").html(html);
            } else {
                toastr.error(result.message);
            }
        });
    }

    function fetchDidNumbers() {
        var data = {'groupId': $("#customer_id").val()};
        var url = "{{ url('fetch_did_numbers') }}";
        ajaxCall(url, data)
        .then(function(result) {
            if(result.status) {
                var html = '<option value="">All</option>';
                result.data.forEach(function(data) {
                    html += '<option value="'+data.did_no+'" >'+data.did_no+'</option>';
                });
                $("#did_no").html(html);
            } else {
                toastr.error(result.message);
            }
        });
    }

function exportCdr() {
    var url = "{{ url('cdrexport') }}";
    url += "?customer=" + $("#customer_id").val();
    url += "&department=" + $("#department_id").val();
    url += "&operator=" + $("#operator_id").val();
    url += "&tag=" + $("#tag").val();
    url += "&status=" + $("#status_id").val();
    url += "&assigned_to=" + $("#assigned_to_id").val();
    url += "&did_no=" + $("#did_no").val();
    url += "&caller_number=" + $("#caller_number").val();
    url += "&date=" + $("#date_select").val();
    url += "&start_date=" + $("#start_date").val();
    url += "&end_date=" + $("#end_date").val();
    console.log(url);
    window.location = url;
}
</script>

@endsection