@extends('layouts.master')
@section('main-content')
           <div class="breadcrumb">
                <h1>Version 1</h1>
                <ul>
                    <li><a href="">Dashboard</a></li>
                    <li>Version 1</li>
                </ul>
            </div>

            <div class="separator-breadcrumb border-top"></div>
            @if(count($announcements))
            <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        @foreach($announcements as $listOne)
                            <div class="card-title">{{$listOne->msg}}</div>    
                        @endforeach                      
                    </div>
                </div>
            </div>
            </div>
            @endif
            <div class="row">
                <?php if(Auth::user()->usertype == 'admin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Administrator"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">No of Users</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $nousers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Administrator"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Inactive Users</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $inusers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Administrator"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Active Operators</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $activeoperator }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator' || Auth::user()->usertype == 'admin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Telephone"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Today's Total Calls</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ (Auth::user()->usertype == 'groupadmin' ? $g_callstoday : (Auth::user()->usertype == 'operator' ? $o_callstoday : $callstoday))}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Headphone"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-3">Live Calls</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ (Auth::user()->usertype == 'groupadmin' ? $g_activecalls  : $activecalls) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Thumbs-Up-Smiley"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">IVR Answered</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $ivranswer }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Thumbs-Down-Smiley"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-3">IVR Missed</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $ivrmissed }}</p>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php endif; ?> 

               
            </div>

            <div class="row" style="margin-bottom: 34px;">
                <div class="col-md-6">
                    <h5>Select Date Range</h5>
                    <input type="text" id="config-demo" class="form-control" value="">
                </div>
                <div class="col-md-6"></div>
            </div>

            <!-- <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Pie Chart Based on Call Status</div>
                            <div id="echartPie" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Bar Stacked Chart- Hourly call Trafic</div>
                            <div id="echartBar" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>       
            </div> -->
            
            <?php if (Auth::user()->usertype == 'groupadmin') { ?>
                <div class="row">
                <div class="col-lg-6 col-xl-6 mt-4">
                    <div class="col-md-12">
                        <div class="card o-hidden mb-4">
                            <div class="card-header d-flex align-items-center border-0">
                                <h3 class="w-50 float-left card-title m-0">Incoming Call</h3>
                            </div>
                            <?php //print_r($lead_count);?>
                            <div class="">
                                <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                    <table id="user_table" class="table  text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Calls</th>
                                                <th scope="col">Forms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(sizeof($incoming_calls) > 0)    
                                            @foreach($incoming_calls as $row)
                                            <tr>
                                                <td>{{$row->status}}</td>
                                                <td>{{$row->count}}</td>
                                                <td>{{$row->leadCdr->count()}}</td>
                                            </tr>
                                            @endforeach 
                                        @else 
                                            <tr>
                                                <td  colspan="3">No Data Found</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6 mt-4">
                    <div class="col-md-12">
                        <div class="card o-hidden mb-4">
                            <div class="card-header d-flex align-items-center border-0">
                                <h3 class="w-50 float-left card-title m-0">Forms</h3>
                            </div>
                            <div class="">
                                <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                    <table id="user_table" class="table  text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(sizeof($operator_leads) > 0)
                                            @foreach($operator_leads as $row)
                                            <tr>
                                                <td>{{$row->lead_stage}}</td>
                                                <td>{{$row->total}}</td>
                                            </tr>
                                            @endforeach
                                            @else 
                                            <tr>
                                                <td  colspan="2">No Data Found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-2 text-muted">Insight Ivr</h6>
                            <p class="text-22 font-weight-light mb-1"><i class="i-Up text-success"></i> By Department</p>
                            <!-- <div class="text-white purple-500 rounded-circle p-2 mr-3" >52</div> -->
                            <div class="row mt-4">
                            @foreach($insight_ivr as $row)
                                <div class="col-md-3">
                                <button class="btn btn-sm rounded-circle btn-icon btn-outline-primary text-white purple-500">{{$row->count}}</button>
                                </br></br>
                                <span style="margin:7px;">{{$row->deptname}}</span>
                                </div>
                            @endforeach
                            <div id="echart9" style="height: 60px; -webkit-tap-highlight-color: transparent; user-select: none; position: relative;" _echarts_instance_="ec_1586745275835"><div style="position: relative; overflow: hidden; width: 528px; height: 60px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas data-zr-dom-id="zr_0" width="528" height="60" style="position: absolute; left: 0px; top: 0px; width: 528px; height: 60px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div></div></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-xl-12 mt-4">
                        <div class="col-md-12">
                            <div class="card o-hidden mb-4">
                                <div class="card-header d-flex align-items-center border-0">
                                    <h3 class="w-50 float-left card-title m-0">Incoming Calls</h3>
                                </div>
                                <div class="">
                                    <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                        <table id="user_table" class="table  text-center">
                                            <thead>
                                                <tr>
                                                    <th scope="col">User</th>
                                                    <th scope="col">Total</th>
                                                    <th scope="col">Answered</th>
                                                    <th scope="col">Missed</th>
                                                    <th scope="col">Forms</th>
                                                    <th scope="col">Completed</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if(sizeof($opcallList) > 0)
                                                @foreach($opcallList as $key => $row)
                                                <?php $total = (isset($row['ANSWERED']) ? $row['ANSWERED'] : 0)+ $row['MISSED'] + $row['lead_count'] + $row['completed']; ?>
                                                <tr>
                                                    <td>{{$key}}</td>
                                                    <td>{{$total}}</td>
                                                    <td>{{isset($row['ANSWERED']) ? $row['ANSWERED'] : ''}}</td>
                                                    <td>{{isset($row['MISSED']) ? $row['MISSED'] : ''}}</td>
                                                    <td>{{isset($row['lead_count']) ? $row['lead_count'] : ''}}</td>
                                                    <td>{{isset($row['completed']) ? $row['completed'] : ''}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td  colspan="6">No Data Found</td>
                                            </tr>
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <h1>CRM</h1>
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                       
                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background: #A5D6A7;">
                                    <i class="i-Consulting"></i>
                                    <p class="text-muted mt-2 mb-2">New</p>
                                    <p class="lead text-22 m-0">{{$level_1}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #BBDEFB;">
                                    <i class="i-Telephone"></i>
                                    <p class="text-muted mt-2 mb-2">Contacted</p>
                                    <p class="lead text-22 m-0">{{$level_2}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #FFCDD2;">
                                    <i class="i-Money"></i>
                                    <p class="text-muted mt-2 mb-2">Interested</p>
                                    <p class="lead text-22 m-0">{{$level_3}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #FFF59D;">
                                    <i class="i-Letter-Open"></i>
                                    <p class="text-muted mt-2 mb-2">Under review</p>
                                    <p class="lead text-22 m-0">{{$level_4}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #E1BEE7;">
                                    <i class="i-Monitor-5"></i>
                                    <p class="text-muted mt-2 mb-2">Demo</p>
                                    <p class="lead text-22 m-0">{{$level_5}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body" style="background-color: #F5F5F5;;">
                                    <input type="hidden" name="level_6" id="level_6" value="{{$level_6}}">
                                    <input type="hidden" name="level_7" id="level_7" value="{{$level_7}}">
                                    <h6 class="mb-0 text-muted">Converted/Unqualified</h6>
                                    <div id="echart5" style="height: 87px;"></div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>

    <div class="modal fade" id="ToDoTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title mb-3">Add ToDo Task<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                    {!! Form::open(['action' => 'HomeController@ToDoTaskAdd', 'method' => 'post']) !!}
                         {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12 form-group mb-3">
                                <label for="task">Task*</label>
                                 <input type="text" name="task" id="task" class="form-control" placeholder="Enter Task" required="" />    
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

    <div class="modal fade EditToDo" id="EditToDo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title mb-3">Edit ToDo Task<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                    {!! Form::open(['action' => 'HomeController@ToDoTaskUpdate', 'method' => 'PATCH']) !!}
                         {{ csrf_field() }}
                        <div class="row">
                            <input type="hidden" name="todo_id" id="todo_id">
                            <div class="col-md-12 form-group mb-3">
                                <label for="task">Task*</label>
                                 <input type="text" name="task" id="task" class="form-control" placeholder="Enter Task" required="" />    
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="date">Date*</label>
                                 <input type="datetime-local" name="date" id="date" class="form-control" required="" />    
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

        <div class="col-lg-6 col-xl-6 mt-4">
                <div class="card">
                  <div class="card-body">
                    <div class="ul-widget__head v-margin">
                      <div class="ul-widget__head-label">
                        <h3 class="ul-widget__head-title">
                          ToDo List
                        </h3>
                      </div>
                      <button type="button" class="btn bg-white _r_btn border-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="_dot _inline-dot bg-primary"></span>
                        <span class="_dot _inline-dot bg-primary"></span>
                        <span class="_dot _inline-dot bg-primary"></span>
                      </button>
                      <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a href="javascript:void(0)" class="dropdown-item" id="add_do" data-toggle="modal" data-target="#ToDoTask">Add</a>
                      </div>
                    </div>
                    <div class="ul-widget-body">
                      <div class="ul-widget3">
                        <div class="ul-widget6__item--table" style="height: 243px;overflow-y: auto;">
                          <table class="table">
                            <thead>
                              <tr class="ul-widget6__tr--sticky-th">
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- start tr --> 
                              @foreach($todo_lists as $todo_list)
                              <tr>
                                <th scope="row">
                                  <label class="checkbox checkbox-outline-info">
                                    <input type="checkbox" value="{{$todo_list->id}}">
                                    <a class="checkmark" href="{{ route('UpdateToDo', $todo_list->id) }}" >
                                    <!-- <span class="checkmark"></span> -->
                                  </label>
                                </th>
                               
                                <td>
                                  {{$todo_list->title}}
                                </td>
                                <td>{{$todo_list->date}}</td>
                                <td>
                                <?php if ($todo_list->status == 'Pending') { ?>
                                    <span class="badge badge-pill badge-outline-danger p-2 m-1">{{$todo_list->status}}</span>
                                <?php }else{ ?>
                                    <span class="badge badge-pill badge-outline-warning p-2 m-1">{{$todo_list->status}}</span>
                                <?php }?>
                                  
                                </td>
                                <td>
                                  <button type="button" class="btn bg-white _r_btn border-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="_dot _inline-dot bg-primary"></span>
                                    <span class="_dot _inline-dot bg-primary"></span>
                                    <span class="_dot _inline-dot bg-primary"></span>
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item editto" href="javascript:void(0)" data-toggle="modal" id="editto" data-target="#EditToDo" data-id="{{$todo_list->id}}">
                                      <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                      Edit</a>
                                    <a class="dropdown-item" href="{{ route('deleteToDo', $todo_list->id) }}">
                                      <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                      Delete
                                    </a>
                                    <?php if ($todo_list->status == 'Hold') { ?>
                                        <a class="dropdown-item" href="{{ route('UpdateStatus', $todo_list->id) }}">
                                      <i class="fa fa-credit-card"></i>
                                      Status Update to Pending
                                    </a>
                                    <?php }else{ ?>
                                        <a class="dropdown-item" href="{{ route('UpdateStatus', $todo_list->id) }}">
                                      <i class="fa fa-credit-card"></i>
                                      Status Update to Hold
                                    </a>
                                    <?php }?>
                                    
                                  </div>
                                </td>
                                
                              </tr>
                              @endforeach
                              <!-- end tr -->
                            </tbody>
                          </table>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-primary" href=""><i class="fa fa-credit-card"></i>Undo</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
        </div>

        <div class="col-lg-6 col-xl-6 mt-4">
            <?php
            if (Auth::user()->usertype == 'groupadmin' /*|| Auth::user()->usertype == 'admin'*/) { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Users Details</h3>
                            <!-- <div class="dropdown dropleft text-right w-50 float-right">
                                <button class="btn bg-gray-100" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="nav-icon i-Gear-2"></i>
                                </button>
                            </div> -->
                        </div>
                        <?php //print_r($lead_count);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Phone No</th>
                                            <th scope="col">Lead Count</th>
                                            <th scope="col">View Leads</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; 
                                        foreach($users_list as $row)
                                        { ?>
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$row->opername}}</td>
                                            <td>{{$row->phonenumber}}</td>
                                            <td>
                                                <?php
                                                echo $lead_count[$row->id];
                                                ?>

                                            </td>
                                            <td>
                                                <a href="{{ route('ListLeads') }}" class="text-success mr-2">
                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                        </a>
                                            </td>
                                        </tr>
                                        <?php $i++;
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Remainder Details</h3>
                        </div>

                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; 
                                        foreach($remainders as $row)
                                        { ?>
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$row->date}}</td>
                                            <td>{{$row->time}}</td>
                                            <td>{{$row->title}}</td>
                                            <td>{{$row->task}}</td>
                                        </tr>
                                        <?php $i++;
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>

        <!-- <div class="col-lg-6 col-xl-6 mt-4"></div> -->

        <div class="col-lg-12 col-xl-12 mt-4">
            <?php
            if (Auth::user()->usertype == 'groupadmin' /*|| Auth::user()->usertype == 'admin'*/) { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Users Lead Details</h3>
                        </div>
                        <?php //print_r($operator_lead_stage);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">New</th>
                                            <th scope="col">Contacted</th>
                                            <th scope="col">Interested</th>
                                            <th scope="col">Under review</th>
                                            <th scope="col">Demo</th>
                                            <th scope="col">Unqualified</th>
                                            <th scope="col">Converted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; 
                                        foreach($operator_lead_stage as $row)
                                        { 
                                            foreach ($row as $key => $value) { 
                                                ?>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$value->opername}}</td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'new') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'Contacted') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'Interested') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'Under review') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'Demo') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'Unqualified') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                                <td>
                                                    <?php
                                                       if ($value->lead_stage == 'Converted') { 
                                                           echo $value->lead_count;
                                                       }else{
                                                        echo "0";
                                                       }
                                                    ?> 
                                                </td>
                                            </tr>        
                                        <?php $i++; }
                                       
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-lg-4 col-xl-4 mt-4">
            <?php
            if (Auth::user()->usertype == 'groupadmin' /*|| Auth::user()->usertype == 'admin'*/) { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Predicted Amount Details</h3>
                        </div>
                        <?php //print_r($predict_cost);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Predicted Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; 
                                        foreach($predict_cost as $row)
                                        { //print_r($row);
                                            foreach ($row as $key => $value) { 
                                                ?>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$value->opername}}</td>
                                                <td>
                                                    <?php
                                                        echo sprintf("%.2f", $value->pre_cost);
                                                    ?> 
                                                </td>
                                            </tr>        
                                        <?php $i++; }
                                       
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>    

        <div class="col-lg-4 col-xl-4 mt-4">
            <?php
            if (Auth::user()->usertype == 'groupadmin' /*|| Auth::user()->usertype == 'admin'*/) { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Proposal Amount Details</h3>
                        </div>
                        <?php //print_r($predict_cost);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; 
                                        foreach ($proposal as $row) {
                                            foreach ($row as $key => $value) { 
                                                ?>
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$value->opername}}</td>
                                                    <td>
                                                        <?php
                                                            echo sprintf("%.2f", $value->proposal_total);
                                                        ?> 
                                                    </td>
                                                </tr>        
                                            <?php $i++; }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-lg-4 col-xl-4 mt-4">
            <?php
            if (Auth::user()->usertype == 'groupadmin' /*|| Auth::user()->usertype == 'admin'*/) { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Invoice Amount Details</h3>
                        </div>
                        <?php //print_r($predict_cost);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1; 
                                        foreach ($invoice as $row) {
                                            foreach ($row as $key => $value) { 
                                                ?>
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$value->opername}}</td>
                                                    <td>
                                                        <?php
                                                            echo sprintf("%.2f", $value->invoice_total);
                                                        ?> 
                                                    </td>
                                                </tr>        
                                            <?php $i++; }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-md-12 col-xl-12 mt-4">
        	<?php
            if (Auth::user()->usertype == 'reseller') { ?>
        	<div class="row">
        		<div class="col-md-4">
	        		<label for="date_from">Date From:</label>
	        		<input type="date" name="date_from" id="date_from" class="form-control">
        		</div>
        		<div class="col-md-4">
        			<label for="date_to">Date To:</label>
        			<input type="date" name="date_to" id="date_to" class="form-control">
        		</div>
        		<div class="col-md-4">
        			<label for="groupadmin_id">Select Group</label>
        			<select id="groupadmin_id" name="groupadmin_id" class="form-control">
        				<option value="">select Admin</option>
        				<?php
        				foreach ($group_admin as $key => $value) { ?>
        					<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
        				<?php } ?>
        			</select>
        		</div>
        		<div class="col-md-6">
        			<br><br>
        			<button id="btn" class="btn btn-primary">Submit</button>
        		</div>
        	</div>
        	<?php } ?>
        </div>

        <div class="col-lg-6 col-xl-6 mt-4">
            <?php
            if (Auth::user()->usertype == 'reseller') { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Users Details</h3>
                        </div>
                        <?php //print_r($lead_count);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Phone No</th>
                                            <th scope="col">Lead Count</th>
                                            <th scope="col">View Leads</th>
                                        </tr>
                                    </thead>
                                    <tbody id="user_details">
                                    	
                                        <?php
                                        $i = 1; 
                                        foreach($users_list as $row)
                                        { ?>
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$row->opername}}</td>
                                            <td>{{$row->phonenumber}}</td>
                                            <td>
                                                <?php
                                                echo $lead_count[$row->id];
                                                ?>

                                            </td>
                                            <td>
                                                <a href="{{ route('ListLeads') }}" class="text-success mr-2">
                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                        </a>
                                            </td>
                                        </tr>
                                        <?php $i++;
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-lg-12 col-xl-12 mt-4">
            <?php
            if (Auth::user()->usertype == 'reseller') { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Users Lead Details</h3>
                        </div>
                        <?php //print_r($operator_lead_stage);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">New</th>
                                            <th scope="col">Contacted</th>
                                            <th scope="col">Interested</th>
                                            <th scope="col">Under review</th>
                                            <th scope="col">Demo</th>
                                            <th scope="col">Unqualified</th>
                                            <th scope="col">Converted</th>
                                        </tr>
                                    </thead>
                                    <tbody id="user_lead_details">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-lg-4 col-xl-4 mt-4">
            <?php
            if (Auth::user()->usertype == 'reseller') { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Predicted Amount Details</h3>
                        </div>
                        <?php //print_r($predict_cost);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Predicted Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="predicted_amount_details">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-lg-4 col-xl-4 mt-4">
            <?php
            if (Auth::user()->usertype == 'reseller') { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Proposal Amount Details</h3>
                        </div>
                        <?php //print_r($predict_cost);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="proposal_amount_details">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-lg-4 col-xl-4 mt-4">
            <?php
            if (Auth::user()->usertype == 'reseller') { ?>
                <div class="col-md-12">
                    <div class="card o-hidden mb-4">
                        <div class="card-header d-flex align-items-center border-0">
                            <h3 class="w-50 float-left card-title m-0">Invoice Amount Details</h3>
                        </div>
                        <?php //print_r($predict_cost);?>
                        <div class="">
                            <div class="table-responsive" style="height: 301px;overflow-y: auto;">
                                <table id="user_table" class="table  text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice_amount_details">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        


@endsection

@section('page-js')
     <script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
     <script src="{{asset('assets/js/es5/echart.options.min.js')}}"></script>
     <script src="{{asset('assets/js/es5/card.metrics.script.min.js')}}"></script>
     <script src="{{asset('assets/js/moment.js')}}"></script>
     <script src="{{asset('assets/js/daterangepicker.js')}}"></script>
     <script type="text/javascript">
        $(document).ready(function () {
            var data = <?php echo !empty($p_data) ? json_encode($p_data) : json_encode(array()); ?>;
            var bdata = <?php echo !empty($series) ? json_encode($series) : json_encode(array()); ?>;
            //console.log('sds', data);
            // Chart in Dashboard version 1
            var echartElemBar = document.getElementById('echartBar');
            if (echartElemBar) {
                var echartBar = echarts.init(echartElemBar);
                echartBar.setOption({
                    legend: {
                        borderRadius: 0,
                        orient: 'horizontal',
                        x: 'right',
                        data: ['Online', 'Offline']
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
                        data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
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
                            formatter: '${value}'
                        },
                        min: 0,
                        max: 100000,
                        interval: 25000,
                        axisLine: {
                            show: false
                        },
                        splitLine: {
                            show: true,
                            interval: 'auto'
                        }
                    }],

                    series: [{
                        name: 'Online',
                        data: [35000, 69000, 22500, 60000, 50000, 50000, 30000, 80000, 70000, 60000, 20000, 30005],
                        label: { show: false, color: '#0168c1' },
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
                        name: 'Offline',
                        data: [45000, 82000, 35000, 93000, 71000, 89000, 49000, 91000, 80200, 86000, 35000, 40050],
                        label: { show: false, color: '#639' },
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
                $(window).on('resize', function () {
                    setTimeout(function () {
                        echartBar.resize();
                    }, 500);
                });
            }

            // Chart in Dashboard version 1
            var echartElemPie = document.getElementById('echartPie');
            if (echartElemPie) {
                var echartPie = echarts.init(echartElemPie);
                echartPie.setOption({
                    color: ['#62549c', '#8877bd'],
                    tooltip:   {
                        show: true,
                        backgroundColor: 'rgba(0, 0, 0, .8)'
                    },
                    series: [{
                        name: 'Call Status',
                        type: 'pie',
                        radius: '60%',
                        center: ['50%', '50%'],
                        data: data,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                });
                $(window).on('resize', function () {
                    setTimeout(function () {
                        echartPie.resize();
                    }, 500);
                });
            }
            var level_7 = $('#level_7').val();
            var level_6 = $('#level_6').val();
            var echartElem5 = document.getElementById('echart5');
            if (echartElem5) {
                var echart5 = echarts.init(echartElem5);
                echart5.setOption(_extends({}, echartOptions.defaultOptions, {
                    series: [{
                        type: 'pie',
                        itemStyle: echartOptions.pieLineStyle,
                        data: [_extends({
                            name: 'Converted',
                            value: level_7
                        }, echartOptions.pieLabelOff, {
                            itemStyle: {
                                borderColor: '#4CAF50'
                            }
                        }), _extends({
                            name: 'Unqualified',
                            value: level_6
                        }, echartOptions.pieLabelOff, {
                            itemStyle: {
                                borderColor: '#df0029'
                            }
                        })]
                    }]
                }));
                $(window).on('resize', function () {
                    setTimeout(function () {
                        echart5.resize();
                    }, 500);
                });
            }

            $('#config-demo').daterangepicker({
            "startDate": "<?php echo $sdate; ?>",
            "endDate": "<?php echo $edate; ?>"
            }, function(start, end, label) { 
                
                $('#dfrom').val(start.format('DD-MM-YYYY'));
                $('#dto').val(end.format('DD-MM-YYYY'));
                $('#dhid').submit();
            });
        });
     </script>
     <script type="text/javascript">
        $('.editto').click(function(){
            myid = $(this).data('id');
            $(".EditToDo").animate({width: 'toggle'}, "slow");
            $(".EditToDo #todo_id").val(myid);
            //alert(myid);
            jQuery.ajax({
                type: "POST",
                url: "home/edit/todotask",
                dataType: 'text',
                data: {myid:myid},
                success: function(edit_data) 
                {
                    //console.log(edit_data);
                    var obj = jQuery.parseJSON(edit_data);
                    //console.log(obj);
                    var newdate = obj[0].date.replace(' ','T');
                    //console.log(newdate);
                    $(".EditToDo #task").val(obj[0].title);
                    $(".EditToDo #date").val(newdate);
                }
            });
        });

     </script>
     <script type="text/javascript">
     	$('#btn').click(function(){
     		var date_from = $('#date_from').val();
     		var date_to = $('#date_to').val();
     		var groupadmin_id = $('#groupadmin_id').val();
     		//alert(date_from+'-'+date_to+'-'+groupadmin_id);
     		jQuery.ajax({
                type: "POST",
                url: "home/crm_data",
                dataType: 'text',
                data: {date_from:date_from,date_to:date_to,groupadmin_id:groupadmin_id},
                success: function(data) 
                {
                    //console.log(data);
                    var html = '';
                    var html1 = '';
                    var html2 = '';
                    var html3 = '';
                    var html4 = '';

                    var i = 1;
                    var j = 1;
                    var k = 1;
                    var x = 1;
                    var y = 1;

                    var new1 = '';
					var contacted = '';
					var interested = '';
					var under_review = '';
					var demo = '';
					var unqualified = '';
					var converted = '';
                    
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);
                    
                    $.each(obj['users_list'], function( index, value ) {
					  
					  html += '<tr><td>'+i+'</td><td>'+value.opername+'</td><td>'+value.phonenumber+'</td><td>'+obj['lead_count'][value.id]+'</td><td><a href="{{ route('ListLeads') }}" class="text-success mr-2"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a></td></tr>';
					  i++;
					});

					$('#user_details').html(html);

					$.each(obj['operator_lead_stage'],function( index, value ) {
						$.each(value, function( index1, value1 ) {

							/*switch(value1.lead_stage) {
								case 'new':
									new1 = value1.lead_count;
									break;
								case 'Contacted':
									contacted = value1.lead_count;
									break; 
								case 'Interested':
									interested = value1.lead_count;
									break; 
								case 'Under review':
									under_review = value1.lead_count;
									break; 
								case 'Demo':
									demo = value1.lead_count;
									break; 
								case 'Unqualified':
									unqualified = value1.lead_count;
									break; 
								case 'Converted':
									converted = value1.lead_count;
									break; 
							}*/
							
							if (value1.lead_stage == 'new') {
								new1 = value1.lead_count;
							}
							else{
								new1 = 0;
							}
							
							if (value1.lead_stage == 'Contacted') { 
                               contacted =  value1.lead_count;
                           	}else{
                           		contacted = 0;
                           	}

                           	if (value1.lead_stage == 'Interested') {
                           		interested = value1.lead_count;
                           	}
                           	else{
                           		interested = 0;
                           	}

                           	if (value1.lead_stage == 'Under review') {
                           		under_review = value1.lead_count;
                           	}
                           	else{
                           		under_review = 0;
                           	}

                           	if (value1.lead_stage == 'Demo') {
                           		demo = value1.lead_count;
                           	}
                           	else{
                           		demo = 0;
                           	}

                           	if (value1.lead_stage == 'Unqualified') {
                           		unqualified = value1.lead_count;
                           	}
                           	else{
                           		unqualified = 0;
                           	}

                           	if (value1.lead_stage == 'Converted') {
                           		converted = value1.lead_count;
                           	}
                           	else{
                           		converted = 0;
                           	}
							html1 += '<tr><td>'+j+'</td><td>'+value1.opername+'</td><td>'+new1+'</td><td>'+contacted+'</td><td>'+interested+'</td><td>'+under_review+'</td><td>'+demo+'</td><td>'+unqualified+'</td><td>'+converted+'</td></tr>';
							
						});
						j++;
					});
					$('#user_lead_details').html(html1);

					$.each(obj['predict_cost'],function( index, value ) {

						html2 += '<tr><td>'+k+'</td><td>'+value[0].opername+'</td><td>'+parseFloat(value[0].pre_cost).toFixed(2)+'</td></tr>';
						k++;
					});
					$('#predicted_amount_details').html(html2);

					$.each(obj['proposal'],function( index, value ) {

						html3 += '<tr><td>'+x+'</td><td>'+value[0].opername+'</td><td>'+parseFloat(value[0].proposal_total).toFixed(2)+'</td></tr>';
						x++;
					});
					$('#proposal_amount_details').html(html3);

					$.each(obj['invoice'],function( index, value ) {

						html4 += '<tr><td>'+y+'</td><td>'+value[0].opername+'</td><td>'+parseFloat(value[0].invoice_total).toFixed(2)+'</td></tr>';
						y++;
					});
					$('#invoice_amount_details').html(html4);
                }
            });
     	});
     </script>
@endsection
<form id="dhid" style="display:none;">
<input type="hidden" name="dfrom" id="dfrom" />
<input type="hidden" name="dto" id="dto" />

</form>
