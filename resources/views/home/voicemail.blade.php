@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Operator Account </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

              <div class="row">
                  <div class="col-lg-12 col-md-12">
                      <div class="card mb-2">
                          <div class="card-body">

                            
                                
                                    {{-- <button class="btn btn-secondary m-1" id="btn_make_call">Make a call</button> --}}
                                   <button class="btn btn-secondary m-1" id="btn_refresh">Refresh</button> 
                                 {{--   <a class="btn btn-secondary m-1" id="btn_download" href="{{ url('voicemailexport') }}">Download</a> --}}
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
                                                                    <option value="{{$dept->departmentname}}">{{$dept->departmentname}}
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
                                                   
                                                   
                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="filter-col"  for="pref-perpage">Dnid Name</label>
                                                        <select  class="form-control" name="did_no">
                                                            <option value="">All</option>
                                                            @if(!empty($dnidnames))
                                                                @foreach($dnidnames as $dnid )
                                                                    <option value="{{$dnid->dnid}}">{{$dnid->dnid}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                            
                                                        </select>                                
                                                    </div> 
                                                   
                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="filter-col"  for="pref-search">By Caller Number</label>
                                                        <input type="text" class="form-control input-sm" name="caller_number">
                                                    </div>

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
                                        <th>Customer</th>
                                        <th>Dnid</th>
                                        <th>Caller</th>
                                        <th>Department</th>
                                        <th>Duration</th>
                                        <th>Date</th>
                                        <th>Actions</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                           {{ $row->name }}

                                        </td>
                                        <td>{{$row->dnid}}</td>
                                        <td>{{$row->callerid}}</td>
                                        <td>{{$row->departmentname}}</td>
                                        <td><a>{{$row->duration}}</a></td>
                                        <td>{{$row->datetime}}</td>
                                        <td>
                                            <a href="{{asset('voicefiles/'.$row->filename)}}" class="nav-icon i-Download" title="Download"></a>
                                            <a href="{{asset('voicefiles/'.$row->filename)}}" class="nav-icon i-Download" title="Play"></a>
                                        </td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    

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
        url : "{{ url('getvoicesearch') }}",
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
