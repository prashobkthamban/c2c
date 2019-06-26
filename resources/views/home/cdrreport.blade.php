@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>CDR Report </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

              <div class="row">
                  <div class="col-lg-12 col-md-12">
                      <div class="card mb-2">
                          <div class="card-body">
                             <div class="col-md-2 mt-3 mt-md-0">
                                <!--
                                    <button class="btn btn-default mt-3" id="btn_make_call">Make a call</button>
                                   <button class="btn btn-default mt-3" id="btn_refresh">Refresh</button> -->
                                   
                                    <button class="btn btn-primary btn-block collapsed pull-right mt-3" data-toggle="collapse" data-target="#filter-panel">Filter</button>
                              </div>
                                    {{-- <button class="btn btn-secondary m-1" id="btn_make_call">Make a call</button> --}}
                                   <button class="btn btn-secondary m-1" id="btn_refresh">Refresh</button> 
                                   <a class="btn btn-secondary m-1" id="btn_download" href="{{ url('cdrexport') }}">Download</a> 
                                    <button class="btn btn-primary collapsed m-1" data-toggle="collapse" data-target="#filter-panel">Filter</button>
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

                                                    <div class="form-group ">
                                                    <div class="col-md-6 form-group mb-3 ">
                                                        <label class="filter-col"  for="pref-perpage">Tags</label>
                                                        <select  class="form-control" name="tags">
                                                            <option value="">All</option>
                                                            @if(!empty($tags))
                                                                @foreach($tags as $tg )
                                                                    <option value="{{$tg->tag}}">{{$tg->tag}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                            
                                                        </select>                                
                                                    </div> 
                                                    
                                                    <div class="form-group">    
                                                    {{--
                                                    <div class="col-md-6 form-group mb-3">  
                                                        <div class="">
                                                          <label><input type="checkbox" value="1" name="unique_call"> Unique Calls</label>
                                                        </div>
                                                    </div>
                                                    --}}
                                                    <div class="col-md-6 form-group mb-3">    
                                                        
                                                        <button type="button" id="report_search_button" class="btn btn-default filter-col">
                                                            <span class="glyphicon glyphicon-record"></span> Search
                                                        </button>  
                                                    </div>
                                                </div>
                                                </form>
                                            
                                    </div>


                                 
                              </div>
                              
                          </div>
                      </div>
                  </div>
              </div>
            <div class="row mb-4"  id="div_table">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Caller</th>
                                        <th>DNID</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr data-toggle="collapse" data-target="#accordion_{{$row->cdrid}}" class="clickable">
                                        <td>
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->fname ? $row->fname : $row->number }}
                                                @else
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->did_no}}</td>
                                        <td>{{$row->datetime}}</td>
                                        <td>{{$row->firstleg .'('. $row->secondleg.')'}}</td>
                                        <td><a>{{$row->status}}</a></td>
                                        <td>{{$row->creditused}}</td>
                                        <td>{{$row->deptname}}</td>
                                        <td>{{$row->opername}}</td>

                                    </tr>
                                    <tr id="accordion_{{$row->cdrid}}" class="collapse">
                                        <td colspan="7">
                                            <div >
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.form',{{$row->number}})">Form</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.tag')">Tag</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.reminder')">Add Reminder</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.assign')">Assign</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.contact')">Add Contact</button>
                                                <button type="button" class="btn btn-info m-1">Play</button>
                                                <button type="button" class="btn btn-info m-1">Download</button>

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



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
 <script src="{{asset('assets/js/datatables.script.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>
 <script type="text/javascript">
//$(document).ready(functon(){

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
//});

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
