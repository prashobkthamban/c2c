@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
<style>
    .dropdown-menu>li>a {
    margin: 4px;
    padding-bottom: 7px;
    padding-top: 7px;
    border-radius: 3px;
    line-height: 18px;
}
</style>
@endsection

@section('main-content')
  <div class="breadcrumb">+


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
                               <!-- <a class="btn btn-secondary m-1" id="btn_download" href="{{ url('cdrexport') }}">Download</a>  -->
                               <button class="btn btn-primary collapsed m-1" data-toggle="collapse" data-target="#filter-panel">Filter</button>
                               <div class="btn-group" id="assign" name="assign" >
                                <a href="#" class="btn btn-primary m-1 dropdown-toggle" data-toggle="dropdown">Assign To</a>
                                <ul class="dropdown-menu" role="menu">
                                  @foreach($operators as $operator)
                                  @if( $account_service['smsservice_assign_cdr'] =='Yes' ||  $account_service['emailservice_assign_cdr'] =='Yes')
                                    <li> 
                                        <a href="javascript:assignoper({{$operator->id}},{{$operator->opername}});">{{$operator->opername}}</a><ul>
                                  @else 
                                    <li> 
                                        <a href="javascript:assignoper({{$operator->id}},{{$operator->opername}});">{{$operator->opername}}</a>
                                  @endif
                                  @if($account_service['smsservice_assign_cdr'] =='Yes')
                                    <li>
                                        <a href="javascript:assignoper({{$operator->id}},'{{$operator->opername}}','S');">Notify By SMS</a>
                                    </li>
                                  @endif
                                  @if($account_service['emailservice_assign_cdr'] =='Yes')
                                    <li>
                                        <a href="javascript:assignoper({{$operator->id}},{{$operator->opername}},'E');">Notify By Email</a>
                                    </li>
                                  @endif 
                                  @if( $account_service['smsservice_assign_cdr'] =='Yes' ||  $account_service['emailservice_assign_cdr'] =='Yes')
                                    </ul>
                                  @else 
                                    </li>
                                  @endif
                                  @endforeach
                                  <?php echo '<li><a href="javascript:assignoper(0);">Unassign</a></li>'; ?>
                                </ul> 
                               </div>
                        </div>
                               
                    

                      <div class="row row-xs">
                        <!-- <div id="filter-panel" class="filter-panel collapse">
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
                                </div> 
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
                                </div> 
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

                                
                                <div class="col-md-6 form-group mb-3 ">
                                    <label class="filter-col"  for="pref-perpage">Tags</label>
                                    {!! Form::select('tags', $tags->prepend('All', ''), null,array('class' => 'form-control')) !!}                        
                                </div> 
                                
                               <div class="form-group">    
                              
                                <div class="col-md-6 form-group mb-3">    
                                    
                                    <button type="button" id="report_search_button" class="btn btn-default filter-col">
                                        <span class="glyphicon glyphicon-record"></span> Search
                                    </button> 
                                </div>
                           
                            </form>      
                        </div>  -->
                      </div>
                          
                   </div>
                  </div>
              </div>
            </div>
            @endif
            @if(Auth::user()->usertype == 'groupadmin')
            <!-- 
             -->
            @endif
            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" name="allselect" id="allselect" value="yes" onclick="selectAll();"></th>
                                        <th>Caller Id</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Department</th>
                                        <th>Agent</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )

                                    <tr data-toggle="collapse" data-target="#accordion_{{$row->cdrid}}" class="clickable">
                                        <td><input type="checkbox" name="cdr_checkbox" id="{{$row->cdrid}}" value="{{$row->cdrid}}" class="allselect"></td>
                                        <td id="caller_{{$row->cdrid}}">
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}
                                                @else
                                                <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->datetime}}</td>
                                        <td><a>{{$row->status}}</a></td>
                                        <td>{{$row->deptname}}</td>
                                        <td id="assigned_{{$row->cdrid}}">{{$row->opername}}</td>
                                        <td>
                                        <a class="btn bg-gray-100" data-toggle="collapse" data-target="#more{{$row->cdrid}}" aria-expanded="false" aria-controls="collapseExample"><i class="i-Arrow-Down-2" aria-hidden="true"></i>
                                            </a>
                                        <div class="dropdown dropleft text-right w-50 float-right">
                                        <button class="btn bg-gray-100" type="button" id="action_{{$row->cdrid}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="nav-icon i-Gear-2"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="action_{{$row->cdrid}}">
                                            <a class="dropdown-item edit_contact" href="#" data-toggle="modal" data-target="#contact_modal" id="contact_{{ $row->contacts && $row->contacts->id ? $row->contacts->id : ''}}" data-email="{{ $row->contacts && $row->contacts->email ? $row->contacts->email : ''}}" data-fname="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : ''}}" data-lname="{{ $row->contacts && $row->contacts->lname ? $row->contacts->lname : ''}}" data-groupid="{{$row->groupid}}" data-phone="{{$row->number}}">{{isset($row->contacts->fname) ? 'Update Contact': 'Add Contact'}}</a>
                                            <a class="dropdown-item edit_tag" href="#" data-toggle="modal" data-target="#tag_modal" id="tag_{{$row->cdrid}}" data-tag="{{$row->tag}}">{{$row->tag ? 'Update Tag': 'Add Tag'}}</a>
                                            <a class="dropdown-item notes_list" href="#" data-toggle="modal" data-target="#notes_modal" id="notes_{{$row->uniqueid}}">Notes</a>
                                            <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#add_note_modal" id="add_note_{{$row->uniqueid}}">Add Notes
                                            </a>
                                            @if(!isset($row->reminder->id))
                                            <a class="dropdown-item edit_reminder" href="#" data-toggle="modal" data-target="#add_reminder_modal" id="add_reminder_{{$row->cdrid}}">Add Reminder</a>
                                            @endif
                                        </div>
                                        </div>
                                        </td>
                                    </tr>
                                    <tr id="more{{$row->cdrid}}" class="collapse">
                                        
                                    </tr>
                                    
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

            <!-- msg modal -->
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
            <!-- end of msg modal -->

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
                         ' => 'Status', 'answrd' => 'Answered', 'DIALING' => 'Dialing'), null,array('class' => 'form-control')) !!}   
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
                                         {!! Form::select('tag', $tags->prepend('Select Tag', ''), null,array('class' => 'form-control', 'id' => 'tag')) !!}
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
    
            <!-- add note modal -->
                <div class="modal fade" id="add_note_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
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
                                      <input type="hidden" name="uniqueid" id="uniqueid"/>
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
                                        <input  placeholder="Followup Time" type="text"  size="10"  data-rel="timepicker" id="timepicker1" name="starttime" data-template="dropdown" data-maxHours="24" data-show-meridian="false" data-minute-step="10" class="form-control" /> 
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
                                        <input type="hidden" name="groupid" id="groupid">
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
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
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

            <!-- customize sidebar -->
            <div class="customizer" style="top: 73px;">
                <div class="handle">
                  <i data-toggle="modal" data-target="#dial_modal" class="i-Telephone"></i>
                </div>
            </div>
            <div class="customizer" style="top: 115px;">
                <div class="handle">
                  <i class="i-Email" data-toggle="modal" data-target="#msg_modal"></i>
                </div>
            </div>
            <div class="customizer" style="top: 156px;">
                <div class="handle">
                  <i class="i-Bar-Chart-2
                  " data-toggle="modal" data-target="#graph_search_modal"></i>
                </div>
            </div>
            <div class="customizer" style="top: 197px;">
                <a href="{{ url('cdrexport') }}"><div class="handle">
                  <i class="i-Download1"></i>
                </div></a>
            </div>
@endsection

@section('page-js')
 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
 <script src="{{asset('assets/js/datatables.script.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>
 <script src="{{asset('assets/js/moment.min.js')}}"></script>
 <script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>
 <script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
 <script type="text/javascript">
    $('#timepicker1').timepicker();
 </script>
 <script type="text/javascript">
    function assignoper(id, name, type) {
        var cdr_id = new Array();
        $('input[name="cdr_checkbox"]:checked').each(function() {
             cdr_id.push($(this).val());
        });

        if(cdr_id.length > 0) {
            $.ajax({
                type: "POST",
                url: '{{ URL::route("AssignCdr") }}', 
                data: {opr_id:id, type:type, cdr_id:cdr_id},
                success: function(res){ 
                  $.each(cdr_id, function( index, value ) {
                    $("#assigned_"+value).text(name);
                  });  
                  $( ".allselect" ).prop( "checked", false );
                  $( "#allselect" ).prop( "checked", false );
                  toastr.success(res.success);
                },
                error: function(jqXHR, textStatus, errorThrown) { 
                    toastr.error('Some errors are occured');
                }
            });
        } else {
            toastr.error('Please select the checkbox.');
        }
    }

     function selectAll() {
        if ($('#allselect').is(":checked"))
        {
          $( ".allselect" ).prop( "checked", true );
        } else {
          $( ".allselect" ).prop( "checked", false );
        }
        
     } 

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
            data: $('.reminder_form').serialize(),
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
        $( '.graph_report' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("graphReport") }}', // This is the url we gave in the route
            data: $('.graph_report').serialize(),
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
                    var dates;
                    if(res.label == 'both') {
                        var answrd = Object.values(res)[1];
                        var dialing = Object.values(res)[2];
                        var ans_dates = Object.keys(answrd);
                        var dial_dates = Object.keys(dialing);
                        var dates = ans_dates.concat(dial_dates);
                    } else {
                        var answrd = Object.values(res)[1];
                        var dates = Object.keys(answrd);
                    }
        
                    const max = res.max;
                    $("#graph_modal").modal('show');
                    $("#graph_search_modal").modal('hide');
                     var echartElemBar = document.getElementById('echartBar');
                    if (echartElemBar) {
                        var echartBar = echarts.init(echartElemBar);
                        if(res.label == 'both') {
                          echartBar.setOption({
                            xAxis: {
                                type: 'category',
                                data: dates
                            },
                            yAxis: {
                                type: 'value',
                                min: 0,
                                max: max
                            },
                            series: [{
                                data: Object.values(answrd),
                                type: 'bar',
                                color: '#bcbbdd'
                            },
                            {
                                data: Object.values(dialing),
                                type: 'bar',
                                color: '#7569b3'
                            }]
                          });
                        } else {
                          echartBar.setOption({
                            xAxis: {
                                type: 'category',
                                data: dates
                            },
                            yAxis: {
                                type: 'value',
                                min: 0,
                                max: max
                            },
                            series: [{
                                data: Object.values(answrd),
                                type: 'bar',
                                color: '#7569b3'
                            }]
                          });  
                        }
                        
                        $(window).on('resize', function () {
                            setTimeout(function () {
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
        $('#graph').on('change',function(e) {
            e.preventDefault();
            var errors = ''; 
            console.log('gfdfgc', $(this).val());
            $.ajax({
            type: "GET",
            url: '/search_cdr/' + $(this).val(), // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                console.log(res);
                $("#graph_modal").modal('show');
            }
            });
        });

        $('.edit_tag').on('click',function(e)
        {
            var id = $(this).attr("id");
            var tag = $(this).attr("data-tag");
            var cdrid = id.replace("tag_", "");
            $("#cdrid").val(cdrid);
            if(tag != '') {
                $("#tag_title").text("Update Tag");
                $("#tag").val(tag);
            } else {
                $("#tag_title").text("Add Tag");
            }
            
        });
        
        $('.edit_contact').on('click',function(e)
        {
            var id = $(this).attr("id");
            var email = $(this).attr("data-email");
            var fname = $(this).attr("data-fname");
            var lname = $(this).attr("data-lname");
            var groupid = $(this).attr("data-groupid");
            var phone = $(this).attr("data-phone");
            var contactid = id.replace("contact_", "");
            $("#contact_id").val(contactid);
            $("#email").val(email);
            $("#fname").val(fname);
            $("#lname").val(lname);
            $("#groupid").val(groupid);
            $("#phone").val(phone);
            if(contactid != '') {
                $("#contact_title").text("Update Contact");
            } else {
                $("#contact_title").text("Add Contact");
            }
            
        });

        $('.edit_reminder').on('click',function(e)
        {
            var id = $(this).attr("id");
            var cdrid = id.replace("add_reminder_", "");
            $("#cdr_id").val(cdrid);
        });

        $('.add_note').on('click',function(e)
        {
            var id = $(this).attr("id");
            var uniqueid = id.replace("add_note_", "");
            $("#uniqueid").val(uniqueid);
        });

        $('.notes_list').on('click',function(e)
        {
            var id = $(this).attr("id");
            var uniqueid = id.replace("notes_", "");
            $.ajax({
            url: '/notes/'+uniqueid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var response = JSON.stringify(res);
                var noteHTML = "";
                if(res.length > 0) {
                    $.each(res, function(idx, obj) {
                        noteHTML += "<tr class='cmnt_row_" + obj.id + "'>";
                        noteHTML += "<td>" + obj.operator  + "</td>";
                        noteHTML += "<td>" + obj.datetime  + "</td>";
                        noteHTML += "<td>" + obj.note  + "</td>";
                        noteHTML += "<td><a href='#' class='text-danger mr-2 delete_comment' id='" + obj.id + "'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                        noteHTML += "</tr>";

                    }); 
                } else {
                    noteHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
                } 
                $("#notes_list_table tbody").html(noteHTML);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
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
