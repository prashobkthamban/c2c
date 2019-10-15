@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Contacts </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <!-- <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#holiday_modal" class="btn btn-primary"> Add Holiday </a> -->
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Last name</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                        @foreach($contacts as $contact)
                                        <tr>
                                            <td>{{$contact->fname}}</td>
                                            <td>{{$contact->lname}}</td>
                                            <td>{{$contact->phone}}</td>
                                            <td>{{$contact->email}}</td>
                                            <td><a href="#" data-toggle="modal" onClick="ContactUpdate( '{{$contact->id}}', '{{$contact->fname}}', '{{$contact->lname}}', '{{$contact->email}}')" class="contact_update" id="{{$contact->id}}" data-target="#contact_update_modal">
                                                     <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('deleteContact', $contact->id) }}" onclick="return confirm('Are you sure you want to delete this Contact?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Last name</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
           </div>

           <!-- contact modal -->
           <div class="modal fade" id="contact_update_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Edit Contact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['action' => 'ManagementController@editContact', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        <input type="hidden" name="id" id="id"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">First Name*</label> 
                                        <input type="text" id="f_name" class="form-control" placeholder="First Name" name="fname">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Last Name*</label> 
                                        <input type="text" id="l_name" class="form-control" placeholder="Last Name" name="lname">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3">  
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Email*</label>
                                        <input type="text" id="e_mail" class="form-control" placeholder="Email" name="email">
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

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    function ContactUpdate(id,fname,lname,email) {
        console.log(fname);
        $("#id").val(id);
        $("#f_name").val(fname);
        $("#l_name").val(lname);
        $("#e_mail").val(email);
    }
</script>

@endsection
