@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>CDR Report </h1>   
            </div>
            <div class="separator-breadcrumb border-top"></div>
            @if(Auth::user()->usertype == 'groupadmin')
            <div class="row">
              <div class="col-lg-12 col-md-12">
                  <div class="card mb-2">
                      <div class="card-body">
                         <div class="col-md-2 mt-3 mt-md-0">
                               
                                <button class="btn btn-primary btn-block collapsed pull-right mt-3" data-toggle="collapse" data-target="#filter-panel">Filter</button>

                          </div>
                                {{-- <button class="btn btn-secondary m-1" id="btn_make_call">Make a call</button> --}}
                               <button class="btn btn-secondary m-1" id="btn_refresh">Refresh</button> 
                               <a class="btn btn-secondary m-1" id="btn_download" href="{{ url('cdrexport') }}">Download</a> 
                               <button class="btn btn-primary collapsed m-1" data-toggle="collapse" data-target="#filter-panel">Filter</button>
                               <a href="#" class="btn btn-primary m-1 dropdown-toggle" data-toggle="dropdown">Assign To</a>
                               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#dial_modal"><i class="i-Telephone"></i></a>
                               <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#msg_modal"><i class="i-Email"></i></a>
                               
                               <ul class="dropdown-menu" role="menu">
                      <li> <a href="javascript:assignoper(5136,'Teena');">Teena</a><ul><li><a href="javascript:assignoper(5136,'Teena','S');">Notify By SMS</a></li><li><a href="javascript:assignoper(5136,'Teena','E');">Notify By Email</a></li></ul></li><li> <a href="javascript:assignoper(11496,'aab');">aab</a><ul><li><a href="javascript:assignoper(11496,'aab','S');">Notify By SMS</a></li><li><a href="javascript:assignoper(11496,'aab','E');">Notify By Email</a></li></ul></li><li><a href="javascript:assignoper(0);">Unassign</a></li>    
                    </ul>

                          <div class="row row-xs">



                              <div id="filter-panel" class="filter-panel collapse">
                                    
                                            <form class="form" role="form" id="cdr_filter_form">
                                                <div class="row"> 
                                                @if( Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator')
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="filter-col"  for="pref-perpage">Departments</label>
                                                    <select name="department" class="form-control">
                                                        <option value="">All</option>
                                                        @if(!empty($departments))
                                                            @foreach($departments as $dept )
                                                                <option value="{{$dept->dept_name}}">{{$dept->dept_name}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                        
                                                    </select>                                
                                                </div> <!-- form group [rows] -->
                                                @endif
                                                @if( Auth::user()->usertype == 'groupadmin' )
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="filter-col"  for="pref-perpage">Operators</label>
                                                    <select name="operator" class="form-control">
                                                         <option value="">All</option>
                                                        @if(!empty($operators))
                                                            @foreach($operators as $opr )
                                                                <option value="{{$opr->id}}">{{$opr->opername}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                        
                                                    </select>                                
                                                </div> <!-- form group [rows] -->
                                                 @endif
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="filter-col"  for="pref-perpage">Date</label>
                                                    <select class="form-control" name="date" id="date_select">
                                                        <option value="">All</option>
                                                        <option value="today">Today</option>
                                                        <option value="yesterday">Yesterday</option>
                                                        <option value="week">1 Week</option>
                                                        <option value="month">1 Month</option>
                                                        <option value="custom">Custom</option>
                                                    </select>                                
                                                </div> 
                                                <div class="col-md-6 form-group mb-3" style="display: none;" id="custom_date_div">
                                                    <label class="filter-col"  for="pref-search">Stardate </label>
                                                    <input type="text" name="startdate" class="form-control input-sm datepicker" >
                                                    <label class="filter-col"  for="pref-search">Enddate</label>
                                                    <input type="text" class="form-control input-sm datepicker" name="enddate">
                                                </div>
                                                @if( Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator')
                                                 <div class="col-md-6 form-group mb-3">
                                                    <label class="filter-col"  for="pref-perpage">Assigned To</label>
                                                    <select  class="form-control" name="assigned_to">
                                                        <option value="">All</option>
                                                        @if(!empty($operators))
                                                            @foreach($operators as $opr )
                                                                <option value="{{$opr->id}}">{{$opr->opername}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                        
                                                    </select>                                
                                                </div> 
                                                @endif
                                                <div class="col-md-6 form-group mb-3 ">
                                                    <label class="filter-col"  for="pref-perpage">Status</label>
                                                    <select  class="form-control" name="status">
                                                        <option value="">All</option>
                                                        @if(!empty($statuses))
                                                            @foreach($statuses as $stat )
                                                                <option value="{{$stat->status}}">{{$stat->status}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                        
                                                    </select>                                
                                                </div> 
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="filter-col"  for="pref-perpage">Dnid Name</label>
                                                    <select  class="form-control" name="did_no">
                                                        <option value="">All</option>
                                                        @if(!empty($dnidnames))
                                                            @foreach($dnidnames as $dnid )
                                                                <option value="{{$dnid->did_no}}">{{$dnid->did_no}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                        
                                                    </select>                                
                                                </div> 
                                               
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="filter-col"  for="pref-search">By Caller Number</label>
                                                    <input type="text" class="form-control input-sm" name="caller_number">
                                                </div>

                                                <!-- <div class="form-group "> -->
                                                <div class="col-md-6 form-group mb-3 ">
                                                    <label class="filter-col"  for="pref-perpage">Tags</label>
                                                    {!! Form::select('tags', $tags->prepend('All', ''), null,array('class' => 'form-control')) !!}                        
                                                </div> 
                                                
                                               <div class="form-group">    
                                               <!-- {{--
                                                <div class="col-md-6 form-group mb-3">  
                                                    <div class="">
                                                      <label><input type="checkbox" value="1" name="unique_call"> Unique Calls</label>
                                                    </div>
                                                </div>
                                                --}} -->
                                                <div class="col-md-6 form-group mb-3">    
                                                    
                                                    <button type="button" id="report_search_button" class="btn btn-default filter-col">
                                                        <span class="glyphicon glyphicon-record"></span> Search
                                                    </button>  
                                                </div>
                                            <!-- </div> -->
                                            </form>
                                        
                                </div>


                             
                          </div>
                          
                      </div>
                  </div>
              </div>
            </div>
            @endif
            <div class="row mb-4"  id="div_table">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Caller</th>
                                        <th>DNID</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Tag</th>
                                        <th>Operator</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )

                                    <tr data-toggle="collapse" data-target="#accordion_{{$row->cdrid}}" class="clickable">
                                        <td id="caller_{{$row->cdrid}}">
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}
                                                @else
                                                <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->did_no}}</td>
                                        <td>{{$row->datetime}}</td>
                                        <td>{{$row->firstleg .'('. $row->secondleg.')'}}</td>
                                        <td><a>{{$row->status}}</a></td>
                                        <td>{{$row->creditused}}</td>
                                        <td>{{$row->deptname}}</td>
                                        <td id="tag_{{$row->cdrid}}">{{$row->tag}}</td>
                                        <td>{{$row->opername}}</td>

                                    </tr>
                                    <tr id="accordion_{{$row->cdrid}}" class="collapse">
                                        <td colspan="8">
                                            <div >
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.form',{{$row->number}})">Form</button>
                                                <button type="button" onClick="xajax_show('add_tag_{{$row->cdrid}}')" class="btn btn-info m-1">Tag</button>
                                                <button type="button" onClick="xajax_show('notes_{{$row->cdrid}}')" class="btn btn-info m-1">Notes</button>
                                                <button type="button" class="btn btn-info m-1" onClick="xajax_show('add_reminder_{{$row->cdrid}}')">Add Reminder</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.assign')">Assign</button>
                                                <!-- <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.contact')">Add Contact</button> -->
                                                @if(!empty($row->contacts->fname))
                                                <button type="button" onClick="xajax_show('contact_form_{{$row->cdrid}}')" class="btn btn-info m-1 clickable" >View Contact</button>
                                                @else
                                                <button type="button" onClick="xajax_show('contact_form_{{$row->cdrid}}')" class="btn btn-info m-1 clickable">Add Contact</button>
                                                @endif
                                                <a href="#" data-toggle="modal" id="{{$row->cdrid}}" data-target="#play_modal" class="btn btn-info m-1">
                                                    Play
                                                </a>
                                                <a href="{{ route('downloadFile', ['1_3409081_09886080500.mp3', $row->groupid]) }} " class="btn btn-info m-1">Download</a>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info">More</button>
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Call Duration : {{$row->firstleg}}({{$row->secondleg}})</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Coin : {{$row->creditused}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Assign To : {{$row->assignedname}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Reminder : {{$row->creditused}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Tag : {{$row->tag}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Form Owner : {{$row->opername}}</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="#" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.callhistory')">For Call History</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">
                                            <div class="row d-none cdr_form" id="contact_form_{{$row->cdrid}}">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Add Contact</h5>
                                                            <form class="contact_form" id="add_contact{{$row->cdrid}}">
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input type="hidden" name="contact_id" value="{{ $row->contacts && $row->contacts->id ? $row->contacts->id : ''}}">
                                                                        <input type="hidden" name="phone" value="{{$row->number}}">
                                                                        <input type="hidden" name="groupid" value="{{$row->groupid}}">
                                                                        <input type="text" class="form-control" name="fname" value="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : ''}}" placeholder="First Name">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input type="text" name="lname" value="{{$row->contacts && $row->contacts->lname ? $row->contacts->lname : ''}}" class="form-control" placeholder="Last Name">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input type="email" name="email" value="{{ $row->contacts && $row->contacts->email ? $row->contacts->email : ''}}" class="form-control" placeholder="Email">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="add_tag_{{$row->cdrid}}">
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Tag Call</h5>
                                                            <form class="tag_form" id="tag_form{{$row->cdrid}}">
                                                                <input type="hidden" name="cdrid" value="{{$row->cdrid}}" />

                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                
                                                                        {!! Form::select('tag', $tags->prepend('Select Tag', ''), null,array('class' => 'form-control')) !!}
                                                                    
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="notes_{{$row->cdrid}}">
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                            <h5>Notes for this Call</h5>
                                                            <button type="button" onClick="xajax_show('add_note_{{$row->cdrid}}')" style="margin: 0px 0px 12px 288px !important;"class="btn btn-info">Add Note</button>
                                                            </div>
                                                            <table id="comments_table" class="display table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Operator</th>
                                                                        <th>Date</th>
                                                                        <th>Comments</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if(!$row->cdrNotes->isEmpty())
                                                                    @foreach($row->cdrNotes as $note )
                                                                        <tr class="cmnt_row_{{$note->id}}">
                                                                            <td>{{$note->operator}}</td>
                                                                            <td>{{$note->datetime}}</td>
                                                                            <td>{{$note->note}}</td>
                                                                            <td><a href="javascript:void(0)" class="text-danger mr-2 delete_comment" id="{{$note->id}}"><i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                                            </a></td>
                                                                        </tr>
                                                                    @endforeach
                                                                    @else 
                                                                        <tr>
                                                                            <td colspan="3">No Comments !!!</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="add_note_{{$row->cdrid}}">
                                                <div class="col-md-5">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Add Note</h5>
                                                            <form class="notes_form" id="cdr_note_{{$row->cdrid}}" autocomplete="off">
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                      <input type="hidden" name="cdrid" value="{{$row->cdrid}}" />
                                                                      <input type="hidden" name="uniqueid" value="{{$row->uniqueid}}"/>
                                                                      <textarea class="form-control" rows="5" name="note" placeholder="Comment"></textarea>
                                                                    </div>
                                                                </div>                          
                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </form> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none cdr_form" id="add_reminder_{{$row->cdrid}}">
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Add Reminder</h5>
                                                            <form class="reminder_form" id="reminder_{{$row->cdrid}}" autocomplete="off">
                                                                <input type="hidden" name="cdr_id" value="{{$row->cdrid}}">
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input class="form-control datepicker" placeholder="dd-mm-yyyy" name="startdate">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-sm-12">
                                                                        <input  placeholder="Followup Time" type="text"  size="10"  data-rel="timepicker" id="timepicker1" name="starttime" data-template="dropdown" data-maxHours="24" data-show-meridian="false" data-minute-step="10" class="form-control" />
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <div class="col-sm-10">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                        <button type="button" class="btn btn-primary" onClick="xajax_hide()">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                    @endforeach
                                        @endif

                                    </tbody>
                                  {{--  <tfoot>
                                    <tr>
                                        <th>Caller</th>
                                        <th>DNID</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th></th>
                                    </tr>

                                    </tfoot> --}}

                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->

            <!-- play modal -->
            <div class="modal fade" id="play_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                               <audio controls id="play_file" class="">
                                                  <source src="{{asset('voicefiles/1/3409081_09886080500.ogg')}}" type="audio/ogg">
                                                  <source src="{{asset('voicefiles/1/3409081_09886080500.ogg')}}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                                </audio>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

             <!-- dial modal -->
            <div class="modal fade" id="dial_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Dial A Number</h5>
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
                                        <input type="text" class="form-control" placeholder="Customer Number" name="number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Operator Number</label> 
                                        <input type="text" class="form-control" value="{{Auth::user()->phone_number}}" placeholder="Operator Number" name="phone">
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
                            <button type="submit" id="dial_submit" class="btn btn-primary">Dial Now</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                                        <input type="text" class="form-control" placeholder="Customer Number" name="number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Operator Number</label> 
                                        <input type="text" class="form-control" value="{{Auth::user()->phone_number}}" placeholder="Operator Number" name="phone">
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



@endsection

@section('page-js')
 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
 <script src="{{asset('assets/js/datatables.script.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>
 <script src="{{asset('assets/js/moment.min.js')}}"></script>
 <script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
 <script type="text/javascript">
    $('#timepicker1').timepicker();
 </script>
 <script type="text/javascript">
     function xajax_show(id) {
        $(".cdr_form").addClass('d-none');
        $("#"+id).removeClass('d-none');
     } 

     function xajax_hide() {
        $(".cdr_form").addClass('d-none');
     }

     function xajax_play(id) {
       $("#"+id).removeClass('d-none');
     }
     $(document).ready(function() {
       
            $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });        
            $('.timepicker').pickatime();

        $( '#dial_form' ).on( 'submit', function(e) {
            e.preventDefault();
            // $("#dial_submit").text('Dialing...');
            // $("#dial_submit").attr('disabled', 'disabled');
            //return false;

            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("AddCdr") }}', // This is the url we gave in the route
            data: $('#dial_form').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    // $("#coperate_form").trigger("reset");
                    // $("#reseller_form").modal('hide');
                    toastr.success(res.success);
                    setTimeout( function() { 
                        location.reload(true); 
                    }, 1000);
                    
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });
        
        $( '#msg_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("AddCdr") }}', // This is the url we gave in the route
            data: $('#msg_form').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    // $("#coperate_form").trigger("reset");
                    // $("#reseller_form").modal('hide');
                    toastr.success(res.success);
                    setTimeout( function() { 
                        location.reload(true); 
                    }, 1000);
                    
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        //add reminder
        $( '.reminder_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addReminder") }}', // This is the url we gave in the route
            data: $('#'+this.id).serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    xajax_hide();
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

     });

    $('.add_contact').click(function() {
        $("#contact_form").removeClass('hide');
    });

    $(document).on("change","#date_select",function(){
        var date_val = $(this).val();
        $("#custom_date_div").hide();
        if(date_val == 'custom')
        {
            $("#custom_date_div").show();
            $('.datepicker').pickadate({format: 'yyyy-mm-dd'})


        }
    });

    $(document).on("click","#report_search_button",function(){
        get_report_search(1);
    });
    $(document).on("click",".page-link",function(event)
    {
        event.preventDefault();
        var page = $(this).text();       
        get_report_search(page);
    });
    $(document).on("click","#btn_refresh",function(){
        $("#cdr_filter_form")[0].reset();
        get_report_search(1);
    });


    function get_report_search(page)
    {
        $.ajax({
            type: 'POST',
            url : "{{ url('getreportsearch') }}",
           // url: '/ivrmanager/getreportsearch',
            data: $("#cdr_filter_form").serialize()+'&page='+page,
            success: function (data) {
                if (data.success == 1) {
                    $("#div_table").replaceWith(data.view);
                     $('#zero_configuration_table').DataTable();
                } else if (data.error == 1) {
                    alert(data.errormsg);
                }
            }

        });
    }
 </script>


@endsection
