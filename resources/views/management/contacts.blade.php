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

                <div class="table-responsive">
                    <a title="Add Contact" href="#" data-toggle="modal" data-target="#contact_update_modal" class="btn btn-primary add_contact" style="float:right;margin-right: 16px;">Add Contact</a>

                    <table id="contact_data" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Last name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th class="noExport">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
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
                <h5 class="modal-title">Edit Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'contact_form_1', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                        <input type="hidden" name="id" id="id">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">First Name</label>
                        <input type="text" id="f_name" class="form-control" placeholder="First Name" name="fname">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Last Name</label>
                        <input type="text" id="l_name" class="form-control" placeholder="Last Name" name="lname">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Email</label>
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

    const dataTable = $('#contact_data').DataTable({
        "dom": 'Bfrtip',
        "buttons": [{
            extend: 'excel',
            text: 'Export',
            title: $('h1').text(),
            exportOptions: {
                modifier: {
                    page: 'current'
                },
                columns: "thead th:not(.noExport)"
            },
            dom: 'Bfrtip',
        }],
        "order": [
            [0, "desc"]
        ],
        "searchDelay": 1000,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("contactsAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
            }
        },
        "columns": [{
                "data": "firstName"
            },
            {
                "data": "lastName"
            },
            {
                "data": "phone"
            },
            {
                "data": "email"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    let htmlData = '<a href="#" data-toggle="modal" onClick="contactUpdate( ' + data.id + ", '" + data.firstName + "', '" + data.lastName + "', '" + data.email + "', '" + data.phone + "'" + ')" class="contact_update mr-2" id="' + data.id + '" data-target="#contact_update_modal">' +
                        '<i class="nav-icon i-Pen-2 font-weight-bold"></i>' +
                        '</a>' +
                        '<a href="javascript:void(0)" onClick="deleteItem(' + data.id + ', ' + "'contacts'" + ')" class="text-danger mr-2">' +
                        '<i class="nav-icon i-Close-Window font-weight-bold"></i>' +
                        '</a>';
                    return htmlData;
                }
            }
        ]
    });

    function reloadDataTable() {
        dataTable.ajax.reload(null, false);
    }

    function contactUpdate(id, fname, lname, email, phone) {
        $(".modal-title").text('Edit Contact');
        $("#phone_div").hide();
        $("#id").val(id);
        $("#f_name").val(fname);
        $("#l_name").val(lname);
        $("#e_mail").val(email);
        $("#phone").val(phone);
    }

    $(document).on('submit', '.contact_form_1', function(e) {
        e.preventDefault();
        var errors = '';
        $.ajax({
            type: "POST",
            url: '{{ URL::route("EditContact") }}', // This is the url we gave in the route
            data: $('.contact_form_1').serialize(),
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
                    $("#contact_update_modal").modal('hide');
                    toastr.success(res.success);
                    reloadDataTable();
                }

            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
        });
    });

    $(document).on('click', '.add_contact', function(e) {
        $(".modal-title").text('Add Contact');
        $(".contact_form_1")[0].reset();
        $("#phone_div").show();
    });
</script>

@endsection