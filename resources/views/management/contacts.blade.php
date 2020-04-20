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
                    <a title="Add Contact" href="#" data-toggle="modal" data-target="#contact_update_modal" class="btn btn-primary add_contact">Add Contact</a>

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
                                <tr id="row_{{ $contact->id }}">
                                    <td>{{$contact->fname}}</td>
                                    <td>{{$contact->lname}}</td>
                                    <td>{{$contact->phone}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td><a href="#" data-toggle="modal" onClick="ContactUpdate( '{{$contact->id}}', '{{$contact->fname}}', '{{$contact->lname}}', '{{$contact->email}}', '{{$contact->phone}}')" class="contact_update" id="{{$contact->id}}" data-target="#contact_update_modal">
                                             <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                        </a>
                                        <a href="javascript:void(0)" onClick="deleteItem({{$contact->id}}, 'contacts')" class="text-danger mr-2">
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
                        {{ $contacts->links() }}
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
                    <h5 class="modal-title">Edit Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 {!! Form::open(['class' => 'contact_form', 'method' => 'post']) !!} 
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
                        <div class="row" id="phone_div">
                            <div class="col-md-2 form-group mb-3">  
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="firstName1">Phone*</label>
                                <input type="number" id="phone" class="form-control" placeholder="Phone Number" name="phone">
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
    function ContactUpdate(id,fname,lname,email,phone) {
        console.log(phone);
        $(".modal-title").text('Edit Contact');
        $("#phone_div").hide();
        $("#id").val(id);
        $("#f_name").val(fname);
        $("#l_name").val(lname);
        $("#e_mail").val(email);
        $("#phone").val(phone);
    }

    $(document).ready(function() {
        $( '.contact_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("EditContact") }}', // This is the url we gave in the route
            data: $('.contact_form').serialize(),
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
                    $("#contact_update_modal").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 300);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.add_contact').on('click',function(e) {
            $(".modal-title").text('Add Contact');
            $(".contact_form")[0].reset();
            $("#phone_div").show();
        });
    });
</script>

@endsection

