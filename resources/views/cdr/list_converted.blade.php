@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Customer </h1> 
    </div>

    <div class="addConverted" style="position: absolute;display: none;z-index: 11;right: 0;width: 50%;">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Customer<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                            {!! Form::open(['action' => 'ConvertedController@store', 'method' => 'post']) !!}
                            <form method="post">
                                 {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="first_name">Name*</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your First Name" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('first_name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="last_name">Last Name*</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('last_name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="gst_no">Gst No*</label>
                                        <input type="text" name="gst_no" id="gst_no" class="form-control"placeholder="Enter Gst No" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('gst_no', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="phone_no">Phone No*</label>
                                        <input type="text" name="phone_no" id="phone_no" class="form-control"placeholder="Enter Your Mobile Number" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phone_no', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="email">Email*</label>
                                        <input type="text" name="email" id="email" class="form-control"placeholder="Enter Your Email Address" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('email', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="address">Address</label>
                                       <textarea name="address" id="address" class="form-control"></textarea> 
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

    <div class="editConverted" style="position: absolute;display: none;z-index: 11;right: 0;width: 50%;">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit Customer<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                            {!! Form::open(['action' => 'ConvertedController@update', 'method' => 'PATCH']) !!}
                            <form method="post">
                                 {{ csrf_field() }}
                                <div class="row">
                                    <input type="hidden" name="id" id="id" class="form-control" />
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="company_name_converted">Company Name*</label>
                                        <input type="text" name="company_name_converted" id="company_name_converted" class="form-control" placeholder="Enter Company Name">
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="first_name">Name*</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your First Name" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('first_name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="last_name">Last Name*</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('last_name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="gst_no">Gst No*</label>
                                        <input type="text" name="gst_no" id="gst_no" class="form-control"placeholder="Enter Gst No" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('gst_no', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="phone_no">Phone No*</label>
                                        <input type="text" name="phone_no" id="phone_no" class="form-control"placeholder="Enter Your Mobile Number" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('phone_no', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="email">Email*</label>
                                        <input type="text" name="email" id="email" class="form-control"placeholder="Enter Your Email Address" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('email', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="address">Address</label>
                                       <textarea name="address" id="address" class="form-control"></textarea> 
                                    </div>
                                    <div class="form-group mb-3 col-md-12">
                                        <p>Lead Deatils</p>
                                    <table border="1" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <td>First Name</td>
                                                <td>Last Name</td>
                                                <td>Email</td>
                                                <td>Company Name</td>
                                                <td>Phone No</td>
                                            </tr>
                                        </thead>
                                        <tbody id="lead_details"></tbody>
                                    </table>
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

            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" id="add_converted" href="javascript:void(0)" class="btn btn-primary"> Add Converted </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>First_Name</th>
                                            <th>Last Name</th>
                                            <th>GST No</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @foreach($list_converteds as $list_converted)
                                        <tr>
                                            <td>{{$list_converted->id}}</td>
                                            <td>{{$list_converted->first_name}}</td>
                                            <td>{{$list_converted->last_name}}</td>
                                            <td>{{$list_converted->gst_no}}</td>
                                            <td>{{$list_converted->email}}</td>
                                            <td>{{$list_converted->mobile_no}}</td>
                                            <td>{{$list_converted->address}}</td>
                                            <td>
                                                <a href="javascript:void(0)" class="text-success mr-2 edit_converted" id="edit_converted" data-id="{{$list_converted->id}}"><i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('deleteConverted', $list_converted->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>  
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>First_Name</th>
                                            <th>Last Name</th>
                                            <th>GST No</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
            </div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>

<script type="text/javascript">
    $('#add_converted').click(function(){
        //$(".addProduct").show(1000);
        $(".addConverted").animate({width: 'toggle'}, "slow");
        //$('.addProduct').css('display','block');
    });

    $('.edit_converted').click(function(){
        myid = $(this).data('id');
         $(".editConverted").animate({width: 'toggle'}, "slow");
         $(".editConverted #id").val(myid);
         //alert(myid);
         jQuery.ajax({
            type: "POST",
            url: "converted/edit",
            dataType: 'text',
            data: {myid:myid},
            success: function(edit_data) 
            {
                //console.log(edit_data);
                var obj = jQuery.parseJSON(edit_data);
                var html = '';
                //console.log(obj);
                $(".editConverted #company_name_converted").val(obj.company_name);
                $(".editConverted #first_name").val(obj.first_name);
                $(".editConverted #last_name").val(obj.last_name);
                $(".editConverted #gst_no").val(obj.gst_no);
                $(".editConverted #phone_no").val(obj.mobile_no);
                $(".editConverted #email").val(obj.email);
                $(".editConverted #address").val(obj.address);

                if (obj.cdr_firstname != null) {
                    html = '<tr><td>'+obj.cdr_firstname+'</td><td>'+obj.cdr_lastname+'</td><td>'+obj.cdr_email+'</td><td>'+obj.cdr_companyname+'</td><td>'+obj.cdr_phn+'</td></tr>';    
                }
                else {
                    html = '';
                }
                
                $(".editConverted #lead_details").html(html);
            }
        });
    });
</script>

@endsection
