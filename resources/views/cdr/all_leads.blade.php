@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/custom.css')}}">
<style>
.select2-container {
    width: 100%!important;    
}

.card {
    border-radius: 0px !important; 
}
</style>
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Cdr Leads </h1> 
    </div>

    <div class="modal fade" id="AddLead" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
        <div class="col-md-12">
            <div class="card mb-4 lead_modal">
                <div class="card-body">
                    <div class="card-title mb-3">Add Lead<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                    {!! Form::open(['action' => 'ReportController@addLead', 'method' => 'post','enctype' => 'multipart/form-data']) !!}
                    <form method="post">
                         {{ csrf_field() }}
                        <div class="row">
                            <input type="hidden" name="cdrreport_id" id="cdrreport_id">
                            <div class="col-md-12 form-group mb-3">
                                <label for="first_name">First Name*</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your First Name" required="" />  
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="last_name">Last Name*</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"placeholder="Enter your Last Name" required="" />  
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="company_name">Company Name</label>
                                <input type="text" name="company_name" id="company_name" class="form-control"placeholder="Enter Company Name" />  
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"placeholder="Enter Email Address" />  
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="phoneno">Phone No*</label>
                                <input type="text" name="phoneno" id="phoneno" class="form-control"placeholder="Enter Phone No" required="" />  
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="alt_phoneno">Another Phone No</label>
                                <input type="text" name="alt_phoneno" id="alt_phoneno" class="form-control"placeholder="Enter Another Phone No" />  
                            </div>

                            <div class="col-md-12">
                                <label for="products">Products</label>
                                    <div class="row">
                                         <section class="container col-xs-12">
                                            <div class="table table-responsive">
                                            <!-- <h4>Select Details</h4> -->
                                            <table id="ppsale" class="table table-striped table-bordered" border="0">
                                              
                                              <tbody id="TextBoxContainer">
                                                <td style="width: 50%;">
                                                  <select name="products[]" id="products" class="form-control js-example-basic-single products">
                                                      <option>Select Products</option>
                                                      @if(!empty($products))
                                                        @foreach($products as $prod )
                                                            <option value="{{$prod->id}}">{{$prod->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif 
                                                  </select> 
                                                  <input type="hidden" name="pro_amount[]" id="pro_amount" class="form-control pro_amount">
                                                </td>
                                                  <td>
                                                    <input type="number" name="quantity[]" id="quantity" class="form-control quantity" placeholder="Enter Quantity" min="1" />  
                                                  </td>
                                                  <td>
                                                      <input type="text" name="sub_amount[]" id="sub_amount" class="form-control sub_amount" placeholder="Sub Amount"readonly="" /> 
                                                  </td>
                                                </tr>
                                              </tbody>
                                              <tfoot>
                                                <tr>
                                                    <th>
                                                        <label for="total_amount">Total Amount:</label>
                                                        <input type="text" name="total_amount" id="total_amount" style="border: none;">
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

                            <div class="col-md-12 form-group mb-3">
                                <label for="owner_name">Operator Name</label>
                                <select id="owner_name" name="owner_name" class="form-control">
                                    <option value="<?php echo Auth::user()->id;?>"><?php echo Auth::user()->username;?></option>
                                    @if(Auth::user()->usertype == 'reseller')
                                        @foreach($users_lists as $users_list)
                                            @foreach($users_list as $us_lis)
                                                <option value="{{$us_lis->id}}">{{$us_lis->opername}}</option>
                                            @endforeach
                                        @endforeach
                                    @else
                                        @foreach($users_lists as $users_list)
                                            <option value="{{$users_list->id}}">{{$users_list->opername}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="lead_stage">Lead Stage</label>  
                                <select class="js-example-basic-single" name="lead_stage" id="lead_stage">
                                    <option value="New">New</option>
                                    <option value="Contacted">Contacted</option>
                                    <option value="Interested">Interested</option>
                                    <option value="Under review">Under review</option>
                                    <option value="Demo">Demo</option>
                                    <option value="Unqualified">Unqualified</option>
                                </select>  
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
   
    <div class="modal fade EditLead" id="EditLead" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="modal-header">
                    <div class="col-md-12 modal-title">Edit Lead<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'LeadController@update', 'method' => 'Patch','enctype' => 'multipart/form-data']) !!}
                    <form method="post">
                         {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id" id="id">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your First Name" required="" />  
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"placeholder="Enter your Last Name" required="" />  
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control"placeholder="Enter Company Name" />  
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"placeholder="Enter Email Address" />  
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="phoneno">Phone No*</label>
                                    <input type="text" name="phoneno" id="phoneno" class="form-control"placeholder="Enter Phone No" required="" />  
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="alt_phoneno">Another Phone No</label>
                                    <input type="text" name="alt_phoneno" id="alt_phoneno" class="form-control"placeholder="Enter Another Phone No" />  
                                </div>

                                <div class="col-md-12">
                                    <label for="products">Products</label>
                                        <div class="row">
                                             <section class="container col-xs-12">
                                                <div class="table table-responsive">
                                                <table id="ppsale" class="table table-striped table-bordered" border="0">
                                                  
                                                  <tbody id="TextBoxContainer">
                                                    <td style="width: 50%;">
                                                      <select name="products[]" id="products" class="form-control js-example-basic-single" required="">
                                                          <option value="">Select Products</option>
                                                          @if(!empty($products))
                                                            @foreach($products as $prod )
                                                                <option value="{{$prod->id}}">{{$prod->name}}
                                                                </option>
                                                            @endforeach
                                                        @endif 
                                                      </select> 
                                                      <input type="hidden" name="pro_amount[]" id="pro_amount" class="form-control pro_amount">
                                                    </td>
                                                      <td>
                                                        <input type="number" name="quantity[]" id="quantity" class="form-control"placeholder="Enter Quantity" min="1" required="" />  
                                                      </td>
                                                      <td>
                                                      <input type="text" name="sub_amount[]" id="sub_amount" class="form-control sub_amount" placeholder="Sub Amount" readonly="" /> 
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                  <tfoot>
                                                    <tr>
                                                        <th>
                                                            <label for="total_amount">Total Amount:</label>
                                                            <input type="text" name="total_amount" id="total_amount" style="border: none;">
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
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="owner_name">Operator Name</label>
                                    <select id="owner_name" name="owner_name" class="form-control">
                                       <!--  <option value="<?php echo Auth::user()->id;?>"><?php echo Auth::user()->username;?></option> -->
                                        @if(Auth::user()->usertype == 'reseller')
                                            @foreach($users_lists as $users_list)
                                                @foreach($users_list as $us_lis)
                                                    <option value="{{$us_lis->id}}">{{$us_lis->opername}}</option>
                                                @endforeach
                                            @endforeach
                                        @else
                                            @foreach($users_lists as $users_list)
                                                <option value="{{$users_list->id}}">{{$users_list->opername}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="lead_stage">Lead Stage</label>  
                                    <select class="js-example-basic-single" name="lead_stage" id="lead_stage">
                                        <option value="New">New</option>
                                        <option value="Contacted">Contacted</option>
                                        <option value="Interested">Interested</option>
                                        <option value="Under review">Under review</option>
                                        <option value="Demo">Demo</option>
                                        <option value="Unqualified">Unqualified</option>
                                    </select>  
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
    
    <div class="modal fade Import_Lead" id="Import_Lead" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true" style="width: 50%;right:0!important;margin-left: auto;">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="modal-header">
                    <div class="col-md-12 modal-title">Add Lead From CSV File<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                </div>
                <div class="card-body">
                    {!! Form::open(['action' => 'LeadController@addLead', 'method' => 'post','enctype' => 'multipart/form-data','onsubmit'=>'return Validate(this);']) !!}
                    
                         {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="csv_file">Add Data from CSV File</label>
                                    <a href="javascript:void(0)" id="excel" style="float: right;font-size: 15px;color: blue;">Sample excel file</a>
                                    <input type="file" name="csv_file" id="csv_file" class="form-control">
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
   
            <div class="separator-breadcrumb border-top"></div>

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                       
                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background: #A5D6A7;">
                                    <i class="i-Consulting"></i>
                                    <p class="text-muted mt-2 mb-2">1.New</p>
                                    <p class="lead text-22 m-0">{{$level_1}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #BBDEFB;">
                                    <i class="i-Telephone"></i>
                                    <p class="text-muted mt-2 mb-2">2.Contacted</p>
                                    <p class="lead text-22 m-0">{{$level_2}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #FFCDD2;">
                                    <i class="i-Money"></i>
                                    <p class="text-muted mt-2 mb-2">3.Interested</p>
                                    <p class="lead text-22 m-0">{{$level_3}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #FFF59D;">
                                    <i class="i-Letter-Open"></i>
                                    <p class="text-muted mt-2 mb-2">4.Under review</p>
                                    <p class="lead text-22 m-0">{{$level_4}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #E1BEE7;">
                                    <i class="i-Monitor-5"></i>
                                    <p class="text-muted mt-2 mb-2">5.Demo</p>
                                    <p class="lead text-22 m-0">{{$level_5}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="card card-icon mb-4">
                                <div class="card-body text-center" style="background-color: #B2DFDB;">
                                    <i class="i-Flag-2"></i>
                                    <p class="text-muted mt-2 mb-2">6.Unqualified/7.Converted</p>
                                    <p class="lead text-22 m-0">{{$level_6.'/'.$level_7}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a href="?" class="btn btn-primary add_lead" id="add_lead" data-toggle="modal" data-target="#AddLead">Add Lead </a>
                            <p id="error_msg" style="font-size: 15px;color: red;text-align: center;"></p>
                            <a href="?" class="btn btn-primary" id="import_lead" data-toggle="modal" data-target="#Import_Lead" style="float: right;margin-top: -49px">Import Lead</a>
                            <br><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="from_date">Date From:</label>
                                            <input type="date" name="from_date" id="from_date" class="form-control">
                                        </div>
                                     
                                        <div class="col-md-4">
                                            <label for="to_date">Date To:</label>
                                            <input type="date" name="to_date" id="to_date" class="form-control" value="<?php echo date('Y-m-d');?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="lead">Lead Stage</label>
                                            <select id="lead" class="form-control" name="lead">
                                                <option value="New" selected="">New</option>
                                                <option value="Contacted">Contacted</option>
                                                <option value="Interested">Interested</option>
                                                <option value="Under review">Under review</option>
                                                <option value="Demo">Demo</option>
                                                <option value="Unqualified">Unqualified</option>
                                                <option value="Converted">Converted</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="search_company_name">Company Name:</label>
                                            <input type="text" name="search_company_name" id="search_company_name" class="form-control" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="search_agent_name">Agent Name:</label>
                                            <input type="text" name="search_agent_name" id="search_agent_name" class="form-control" value="">
                                        </div>
                                        <div class="col-md-6" style="margin-top: 24px;">
                                            <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                             <button id="export_lead" class="btn btn-outline-secondary" name="btn">Export Leads</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <input type="hidden" name="usertype" id="usertype" value="<?php echo Auth::user()->usertype; ?>">
                            <input type="hidden" name="lead_count" id="lead_count" value="<?php echo $lead_count; ?>">
                            <input type="hidden" name="total_leads" id="total_leads" value="<?php echo $total_access_leads; ?>">
                            <div class="table-responsive">
                                <table id="lead_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Phoneno</th>
                                            <th>Lead Stage</th>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin') { ?>
                                                <th>Agent Name</th>
                                            <?php }?>
                                            <?php
                                            if (Auth::user()->usertype == 'reseller') { ?>
                                                <th>Group Name</th>
                                            <?php }?>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody class="filter_data">
                                        @if(Auth::user()->usertype == 'reseller')
                                            @foreach($list_leads as $list_lead)
                                                @foreach($list_lead as $up_lead)
                                                    <tr>
                                                        <td>{{$up_lead->id}}</td>
                                                        <td><a href="{{ route('ViewLeadID', $up_lead->id) }}"><b>{{$up_lead->first_name}}</b></a></td>
                                                        <td>{{$up_lead->last_name}}</td>
                                                        <td>{{$up_lead->company_name}}</td>
                                                        <td>{{$up_lead->email}}</td>
                                                        <td>{{$up_lead->phoneno}}</td>
                                                        <td>
                                                          <span class="badge badge-success">{{$up_lead->lead_stage}}</span>
                                                        </td>
                                                        <?php
                                                        if (Auth::user()->usertype == 'reseller') { ?>
                                                            <td>{{$up_lead->accountgroup_name}}</td>
                                                        <?php }?>
                                                        <td>{{$up_lead->inserted_date}}</td>
                                                        <td>
                                                            <a href="javascript:void(0)" class="text-success mr-2 edit_lead" id="edit_lead" data-toggle="modal" data-target="#EditLead" data-id="{{$up_lead->id}}"><i class="nav-icon i-Pen-2 font-weight-bold" data-toggle="tooltip" data-placement="top" title="Lead Edit"></i>
                                                            </a>
                                                            <a href="{{ route('deleteLead', $up_lead->id) }}" onclick="return confirm('Are you sure you want to delete this Lead?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="Lead Delete">
                                                                <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                            </a>
                                                            <a href="javascript:void(0)" class="text-warning mr-2" id="action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="i-Arrow-Down-2" data-toggle="tooltip" data-placement="top" title="Lead Actions"></i></a>
                                                            <div class="dropdown-menu" aria-labelledby="action">
                                                                <a class="dropdown-item add_note note" href="javascript:void(0)" data-toggle="modal"data-id="{{$up_lead->id}}"  data-target="#add_note_modal" id="note">Add Notes
                                                                </a>
                                                                @if(!isset($row->reminder->id))
                                                                <a class="dropdown-item edit_reminder reminder" href="javascript:void(0)" data-toggle="modal" data-id="{{$up_lead->id}}" data-target="#add_reminder_modal" id="reminder">Add Reminder</a>
                                                                <?php
                                                                if ($up_lead->lead_stage == 'New') {
                                                                    $id = 2;
                                                                    $stage = 'Contacted';
                                                                }
                                                                elseif ($up_lead->lead_stage == 'Contacted') {
                                                                    $id = 3;
                                                                    $stage = 'Interested';
                                                                }
                                                                elseif ($up_lead->lead_stage == 'Interested') {
                                                                    $id = 4;
                                                                    $stage = 'Under review';
                                                                }
                                                                elseif ($up_lead->lead_stage == 'Under review') {
                                                                    $id = 5;
                                                                    $stage = 'Demo';
                                                                }
                                                                elseif ($up_lead->lead_stage == 'Demo') {
                                                                    $id = 6;
                                                                    $stage = 'Unqualified';
                                                                }
                                                                else {
                                                                    $id = 1;
                                                                    $stage = 'New';
                                                                }
                                                                ?>
                                                            
                                                                <a href="{{ route('lead_stages',[$up_lead->id,$id]) }}" class="dropdown-item">Change stage to {{$stage}}</a>
                                                                @endif
                                                    

                                                                <a class="dropdown-item add_note assigned_to" href="javascript:void(0)" data-toggle="modal" data-id="{{$up_lead->id}}"  data-target="#lead_assigned" id="assigned">Lead Assigned to
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            @foreach($list_leads as $list_lead)
                                            <tr>
                                                <td>{{$list_lead->id}}</td>
                                                <td><a href="{{ route('ViewLeadID', $list_lead->id) }}"><b>{{$list_lead->first_name}}</b></a></td>
                                                <td>{{$list_lead->last_name}}</td>
                                                <td>{{$list_lead->company_name}}</td>
                                                <td>{{$list_lead->email}}</td>
                                                <td>{{$list_lead->phoneno}}</td>
                                                <td>
                                                  <span class="badge badge-success">{{$list_lead->lead_stage}}</span>
                                                </td>
                                                <?php
                                                if (Auth::user()->usertype == 'groupadmin') { ?>
                                                    <td>{{$list_lead->opername}}</td>
                                                <?php }?>
                                                <td><?php echo date('d-m-Y',strtotime($list_lead->inserted_date)); ?></td>
                                                <td>
                                                    <a href="javascript:void(0)" class="text-success mr-2 edit_lead" id="edit_lead" data-toggle="modal" data-target="#EditLead" data-id="{{$list_lead->id}}"><i class="nav-icon i-Pen-2 font-weight-bold" data-toggle="tooltip" data-placement="top" title="Lead Edit"></i>
                                                    </a>
                                                    <a href="{{ route('deleteLead', $list_lead->id) }}" onclick="return confirm('Are you sure you want to delete this Lead?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="Lead Delete">
                                                        <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="text-warning mr-2" id="action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="i-Arrow-Down-2" data-toggle="tooltip" data-placement="top" title="Lead Actions"></i></a>
                                                    <div class="dropdown-menu" aria-labelledby="action">
                                                        <a class="dropdown-item add_note note" href="javascript:void(0)" data-toggle="modal"data-id="{{$list_lead->id}}"  data-target="#add_note_modal" id="note">Add Notes
                                                        </a>
                                                        @if(!isset($row->reminder->id))
                                                        <a class="dropdown-item edit_reminder reminder" href="javascript:void(0)" data-toggle="modal" data-id="{{$list_lead->id}}" data-target="#add_reminder_modal" id="reminder">Add Reminder</a>
                                                        <?php
                                                        if ($list_lead->lead_stage == 'New') {
                                                            $id = 2;
                                                            $stage = 'Contacted';
                                                        }
                                                        elseif ($list_lead->lead_stage == 'Contacted') {
                                                            $id = 3;
                                                            $stage = 'Interested';
                                                        }
                                                        elseif ($list_lead->lead_stage == 'Interested') {
                                                            $id = 4;
                                                            $stage = 'Under review';
                                                        }
                                                        elseif ($list_lead->lead_stage == 'Under review') {
                                                            $id = 5;
                                                            $stage = 'Demo';
                                                        }
                                                        elseif ($list_lead->lead_stage == 'Demo') {
                                                            $id = 6;
                                                            $stage = 'Unqualified';
                                                        }
                                                        else {
                                                            $id = 1;
                                                            $stage = 'New';
                                                        }
                                                        ?>
                                                    
                                                        <a href="{{ route('lead_stages',[$list_lead->id,$id]) }}" class="dropdown-item">Change stage to {{$stage}}</a>
                                                        @endif
                                            

                                                        <a class="dropdown-item add_note assigned_to" href="javascript:void(0)" data-toggle="modal" data-id="{{$list_lead->id}}"  data-target="#lead_assigned" id="assigned">Lead Assigned to
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>First_Name</th>
                                            <th>Last Name</th>
                                            <th>Company Name</th>
                                            <th>Email</th>
                                            <th>Owner Number</th>
                                            <th>Lead Stage</th>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin') { ?>
                                                <th>Agent Name</th>
                                            <?php }?>
                                            <?php
                                            if (Auth::user()->usertype == 'reseller') { ?>
                                                <th>Group Name</th>
                                            <?php }?>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <!-- <div class="pull-right">{{ $result->links() }}</div> -->
                    </div>
                </div>
            </div>

            <!-- add note modal -->
                <div class="modal fade Note" id="add_note_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Note</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {!! Form::open(['action' => 'LeadController@Notes', 'method' => 'post']) !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                      <input type="hidden" name="lead_id" id="lead_id">
                                      <textarea class="form-control" rows="5" name="note_msg" placeholder="Comment"></textarea>
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

            <!-- Lead Assigned modal -->
                <div class="modal fade Lead_Assigned" id="lead_assigned" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assigned Lead</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {!! Form::open(['action' => 'LeadController@Assigned_Lead', 'method' => 'post']) !!}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="lead_id" id="lead_id">
                                        <select id="owner_name" name="owner_name[]" class="form-control js-example-basic-multiple" multiple="multiple">
                                            <option value="">Select Account</option>
                                            @if(Auth::user()->usertype == 'reseller')
                                                @foreach($users_lists as $users_list)
                                                    @foreach($users_list as $us_lis)
                                                        <option value="{{$us_lis->id}}">{{$us_lis->opername}}</option>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                @foreach($users_lists as $users_list)
                                                    <option value="{{$users_list->id}}">{{$users_list->opername}}</option>
                                                @endforeach
                                            @endif
                                        </select>
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
            <!-- end of Lead Assigned modal -->


@endsection

@section('page-js')

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
    $(document).ready(function() {
        $('#lead_table').DataTable( {
            "order": [[0, "desc" ]]
        } );
    } );
</script>
<script type="text/javascript">
    $('#timepicker1').timepicker();
    /*$('.edit_lead').click(function(){*/
        $(document).on("click", ".edit_lead", function () {
        myid = $(this).data('id');
        //alert(myid);
        $(".EditLead").animate({width: 'toggle'}, "slow");
        $(".EditLead #id").val(myid);
        jQuery.ajax({
            type: "POST",
            url: '{{ URL::route("editLead") }}',
            dataType: 'text',
            data: {myid:myid},
            success: function(edit_data) 
            {
                //console.log(edit_data);
                var obj = jQuery.parseJSON(edit_data);
                //console.log(obj);
                $(".EditLead #first_name").val(obj.first_name);
                $(".EditLead #last_name").val(obj.last_name);
                $(".EditLead #company_name").val(obj.company_name);
                $(".EditLead #email").val(obj.email);
                $(".EditLead #phoneno").val(obj.phoneno);
                $(".EditLead #alt_phoneno").val(obj.alt_phoneno);
                $(".EditLead #products").val(obj.product_id);
                $('.EditLead #products').select2();
                $(".EditLead #quantity").val(obj.quantity);
                $(".EditLead #owner_name").val(obj.operatorid);
                $(".EditLead #owner_name").select2();
                $(".EditLead #lead_stage").val(obj.lead_stage);
                $(".EditLead #lead_stage").select2();
                $(".EditLead #total_amount").val(parseFloat(obj.total_amount).toFixed(2));
                $(".EditLead #sub_amount").val(parseFloat(obj.subtotal_amount).toFixed(2));
                $(".EditLead #pro_amount").val(parseFloat(obj.pro_amount).toFixed(2));
            }
        });
        jQuery.ajax({
            type: "POST",
            url: '{{ URL::route("LeadProduct") }}',
            dataType: 'text',
            data: {myid:myid},
            success: function(lead_product) 
            {
                //console.log(lead_product);
                var lead_pro = jQuery.parseJSON(lead_product);
                var html= '';
                //console.log(lead_pro);
                $.each(lead_pro,function(index,data){
                    //alert(data.subtotal_amount);
                    html += '<tr><td><select name="products[]" id="products" class="form-control js-example-basic-single"><option>Select Products</option>';
                    html += '<option value="'+data.product_id+'"selected>'+data.name+'</option>';
                    html +='</select><input type="hidden" name="pro_amount[]" id="pro_amount" class="form-control pro_amount" value="'+parseFloat(data.pro_amount).toFixed(2)+'"></td><td><input type="number" name="quantity[]" id="quantity" class="form-control" value="'+data.quantity+'" placeholder="Enter Quantity" min="1" /></td><td><input type="text" name="sub_amount[]" id="sub_amount" class="form-control sub_amount" value="'+parseFloat(data.subtotal_amount).toFixed(2)+'" placeholder="Sub Amount"readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td></tr>';
                  });
                    
                /*$.each(lead_pro,function(index,data){
                    alert(data.product_id);

                    html += '<option value='+data.product_id+'>'+data.product_id+'</option>';
                  });*/
                //alert(html);
                $('#TextBoxContainer').html(html);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        //alert($('#lead_count').val());
        var lead_count = $('#lead_count').val();
        var lead_total = $('#total_leads').val();
        //alert(lead_count);
        //alert(lead_total);
        if ($('#usertype').val() != 'admin' && $('#usertype').val() != 'reseller') 
        {
            if (Number(lead_count) > Number(lead_total) || Number(lead_count) == Number(lead_total)) {
                //alert('if');
                $('.add_lead').prop('disabled',true);
                $('#error_msg').html('Lead Limit access!!! Please contact to admin');
            }
        }
        
        
        $('.js-example-basic-single').select2();
        
        $("#btnAdd").bind("click", function () {
            var div = $("<tr />");
            div.html(GetDynamicTextBox(""));
            $("#TextBoxContainer").append(div);
            $('.js-example-basic-single').select2();
            
        });
        $("body").on("click", ".remove", function () {

            var sub = $(this).closest("tr").find("input.amount").val();
            if (sub != '') {
                $(this).closest("tr").remove();
                total_amount();
            }else{
                $(this).closest("tr").remove();
                total_amount();
            }
          
        });

        $("body").on("change", ".products", function () {
            //alert($(this).val());
            var pro_id = $(this).val();
            var thiss = $(this);
            jQuery.ajax({
                type: "POST",
                url: '{{ URL::route("ProductAmount") }}',
                dataType: 'text',
                data: {pro_id:pro_id},
                success: function(data) 
                {
                    //console.log(data);
                    var obj = jQuery.parseJSON(data);
                    //console.log(obj[0].selling_cost);
                   
                    thiss.closest("td").find("input.pro_amount").val(parseFloat(obj[0].selling_cost).toFixed(2));
                    /*$('.pro_amount').val(obj[0].selling_cost);*/
                }
            });
        });

        $("body").on("change", ".quantity", function () {
            //alert($(this).val());
            var quantity = $(this).val();
            var am = $(this).closest("tr").find("input.pro_amount").val();
            var sub_amount = quantity * am;
            //alert(quantity*am);
            $(this).closest("tr").find("input.sub_amount").val(parseFloat(sub_amount).toFixed(2));
            total_amount();
        });

      });
      function GetDynamicTextBox(value) 
      {
          return '<td><select name="products[]" id="products" class="form-control js-example-basic-single products" required><option value="">Select Products</option>@if(!empty($products)) @foreach($products as $prod )<option value="{{$prod->id}}">{{$prod->name}}</option>@endforeach @endif</select><input type="hidden" name="pro_amount[]" id="pro_amount" class="form-control pro_amount"> </td><td><input type="number" name="quantity[]" id="quantity" class="form-control quantity"placeholder="Enter Quantity" min="1" required/></td><td><input type="text" name="sub_amount[]" id="sub_amount" class="form-control sub_amount" placeholder="Sub Amount" readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td>';
      }

      function total_amount(){
                var sum = 0.0;
                $('.sub_amount').each(function(){
                    //alert($(this).val());
                    sum += Number($(this).val()); 
                });
                $('#total_amount').val(parseFloat(sum).toFixed(2));
            }
</script>

<script type="text/javascript">
    $('.add_lead').click(function(){
        $('.js-example-basic-single').select2({dropdownParent: $("#AddLead")});
        $("#AddLead").animate({width: 'toggle'}, "slow");
    });

    $('#excel').click(function(e){
        
            e.preventDefault();  //stop the browser from following
            window.location.href = 'general_file/Add_Lead.csv';
        
    });

    function exportexcel() { 
    var table2excel = new Table2Excel(); 
        table2excel.export(document.querySelectorAll('#pdf_view'));  
    }  

  
    var _validFileExtensions = [".csv",".xlsx"];    
    function Validate(oForm) {
        /*var arrInputs = oForm.getElementsByTagName("input");
        for (var i = 0; i < arrInputs.length; i++) {
            var oInput = arrInputs[i];
            //alert(oInput.type);
            if (oInput.type == "file") {
                var sFileName = oInput.value;
                if (sFileName.length > 0) {
                    var blnValid = false;
                    for (var j = 0; j < _validFileExtensions.length; j++) {
                        var sCurExtension = _validFileExtensions[j];
                        if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                            blnValid = true;
                            break;
                        }
                    }
                    
                    if (!blnValid) {
                        alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                        return false;
                    }
                }
            }
        }
      
        return true;*/
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.reminder').click(function(){
            myid = $(this).data('id');
            alert(myid);
            $(".Reminder #lead_id").val(myid);
        });

        $('.note').click(function(){
            myid = $(this).data('id');
            alert(myid);
            $(".Note #lead_id").val(myid);
        });

        $('.assigned_to').click(function(){
            $('.js-example-basic-multiple').select2({dropdownParent: $("#lead_assigned")});
            myid = $(this).data('id');
            $(".Lead_Assigned #lead_id").val(myid);
        });
    });
</script>

<script type="text/javascript">
    $('#btn').click(function(){
        var date_to = $('#to_date').val();
        var date_from = $('#from_date').val();
        var lead = $('#lead').val();
        var company_name = $('#search_company_name').val();
        var agent_name = $('#search_agent_name').val();
        //alert(date_from+date_to+lead);
        if (date_from != '' && date_to != '') 
        {
            jQuery.ajax({
                type: "POST",
                url: '{{ URL::route("FilterData") }}',
                dataType: 'text',
                data: {date_to:date_to,date_from:date_from,lead:lead,company_name:company_name,agent_name:agent_name},
                success: function(data) 
                {
                   // alert('qwerty');
                    //console.log(data);
                    var obj = jQuery.parseJSON(data);
                    //console.log(obj);
                    var html = '';

                    $.each(obj['filter_data'],function(index,data){
                        //alert(index+data.tbl_booking_id);
                        var url = '{{ route("deleteLead", ":data") }}';

                        url = url.replace(':data', data.id);

                        var ViewLeadID = '{{ route("ViewLeadID", ":lead") }}';

                        ViewLeadID = ViewLeadID.replace(':lead',data.id);

                        if (data.opername != null) {
                            var opername = data.opername;
                        }
                        else
                        {
                            var opername = '';
                        }

                        if (data.lead_stage == 'New') {
                            var id = 2;
                            var stage = 'Contacted';
                        }
                        else if (data.lead_stage == 'Contacted') {
                            var id = 3;
                            var stage = 'Interested';
                        }
                        else if (data.lead_stage == 'Interested') {
                            var id = 4;
                            var stage = 'Under review';
                        }
                        else if (data.lead_stage == 'Under review') {
                            var id = 5;
                            var stage = 'Demo';
                        }
                        else if (data.lead_stage == 'Demo') {
                            var id = 6;
                            var stage = 'Unqualified';
                        }

                        var usertype = $('#usertype').val();
                        if (usertype != 'operator') 
                        {
                            var op = '<td>'+opername+'</td>';
                        }
                        else
                        {
                            var op = '';
                        }

                        var d = new Date(data.inserted_date);

                        var month = d.getMonth()+1;
                        var day = d.getDate();

                        var output = ((''+day).length<2 ? '0' : '') + day  + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + d.getFullYear();

                        /*alert(output);*/


                        html += '<tr><td>'+data.id+'</td>'+'<td><a href="'+ViewLeadID+'"><b>'+data.first_name+'</b></a></td>'+'<td>'+data.last_name+'</td>'+'<td>'+data.company_name+'</td>'+'<td>'+data.email+'</td>'+'<td>'+data.phoneno+'</td>'+'<td><span class="badge badge-success">'+data.lead_stage+'</span></td>'+op+'<td>'+output+'</td>'+'<td><a href="javascript:void(0)" class="text-success mr-2 edit_lead" id="edit_lead" data-toggle="modal" data-target="#EditLead" data-id="'+data.id+'"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a><a href="'+url+'" onclick="return confirm("Are you sure you want to delete this Lead?")" class="text-danger mr-2"><i class="nav-icon i-Close-Window font-weight-bold"></i></a><a href="javascript:void(0)" class="text-warning mr-2" id="action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="i-Arrow-Down-2"></i></a><div class="dropdown-menu" aria-labelledby="action"><a class="dropdown-item add_note note" href="javascript:void(0)" data-toggle="modal"data-id="'+data.id+'"  data-target="#add_note_modal" id="note">Add Notes</a> @if(!isset($row->reminder->id)) <a class="dropdown-item edit_reminder reminder" href="javascript:void(0)" data-toggle="modal" data-id="'+data.id+'" data-target="#add_reminder_modal" id="reminder">Add Reminder</a><a href="#" class="dropdown-item">Change stage to '+stage+'</a> @endif<a class="dropdown-item add_note assigned_to" href="javascript:void(0)" data-toggle="modal" data-id="'+data.id+'"  data-target="#lead_assigned" id="assigned">Lead Assigned to</a></div></td>';

                    });
                    //alert(html);
                    $('.filter_data').html(html);
                    $('#lead_table_info').html('Total count are '+obj['count_data']);
                }
            });
        }
        else
        {
            alert('please enter Date From');
        }
    });

    $('#export_lead').click(function(){
                //alert('excel');
                exportexcel();
            });

            function exportexcel() {  
                $(".filter_data").table2excel({  
                    name: "Table2Excel",  
                    filename: "Lead_Data",  
                    fileext: ".csv"  
                });  
            }  
</script>

@endsection
