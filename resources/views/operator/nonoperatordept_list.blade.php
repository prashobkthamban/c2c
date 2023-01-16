@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> NonOperator Department </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

            <!-- search bar -->
            @include('layouts.search_panel', ['request' => '{{request}}'])
            <!-- search bar ends -->

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_non_operator" class="btn btn-primary" id="new_non_opt"> Add New </a>
                            <div class="table-responsive">
                                <table id="non_opr_dept_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>CoperateName</th>
                                            <th>Depatment Name</th>
                                            <th>Sms to caller</th>
                                            <th>Sms to operator</th>
                                            <th>Operator no</th>
                                            <th>Datetime</th>
                                            <th>Action</th>
                                            <th>Upload File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($nonoperatordept as $listOne)
                                        <tr>
                                            <td>{{$listOne->name}}</td>
                                            <td>{{$listOne->resellername}}</td>
                                            <td>@if($listOne->dept_name == '') 
                                                {{'MissCall'}}
                                                @else
                                                {{$listOne->dept_name}}
                                                @endif
                                            </td>
                                            <td>{{$listOne->sms_to_caller}}</td>
                                            <td>{{$listOne->sms_to_operator}}</td>
                                            <td>{{$listOne->operator_no}}</td>
                                            <td>{{$listOne->adddate}}</td>
                                            <td><a href="#" class="text-success mr-2 edit_non_operator" data-toggle="modal" data-target="#add_non_operator" id="{{$listOne->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a><a href="{{ route('deleteNonOperator', $listOne->id) }}" onclick="return confirm('Are you sure want to delete this non-operator?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                            <td><a href="#" class="text-success mr-2 upload_complaints" data-toggle="modal" data-target="#upload_comp" id="upload_{{$listOne->id}}"><i class="nav-icon i-Upload1 font-weight-bold"></i></a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Customer</th>
                                            <th>CoperateName</th>
                                            <th>Depatment Name</th>
                                            <th>Sms to caller</th>
                                            <th>Sms to operator</th>
                                            <th>Operator no</th>
                                            <th>Datetime</th>
                                            <th>Action</th>
                                            <th>Upload File</th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- add non-operator modal -->
            <div class="modal fade" id="add_non_operator" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add NonOperator Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'non_operator_form', 'method' => 'post']) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('id', '', array('id' =>'non_operator_id')) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Coperate Account</label> 
                                         {!! Form::select('resellerid', getResellers()->prepend('Select coperate', ''), null,array('class' => 'form-control', 'id' => 'resellerid')) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Customer *</label> 
                                         {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'customerId', 'onChange' => 'setDepartment()')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Department Name*</label> 
                                        {!! Form::select('departmentid', [], null,array('class' => 'form-control', 'id' => 'departmentid')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">SMS to caller</label> 
                                        {!! Form::select('sms_to_caller', ['No' => 'No', 'Yes' => 'Yes'], null,array('class' => 'form-control', 'id' => 'sms_to_caller')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">SMS to operator</label> 
                                        {!! Form::select('sms_to_operator', ['No' => 'No', 'Yes' => 'Yes'], null,array('class' => 'form-control', 'id' => 'sms_to_operator')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Generate Ticketid</label> 
                                        <label class="radio-inline"> {{ Form::radio('generateticket', 'Yes', '', array('id' => 'generateticket_yes')) }} Yes</label>
                                        <label class="radio-inline">{{ Form::radio('generateticket', 'No', true, array('id' => 'generateticket_no')) }} No</label>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Record Complaint</label> 
                                        <label class="radio-inline"> {{ Form::radio('record_com', 'Yes', true, array('id' => 'record_com_yes')) }} Yes</label>
                                        <label class="radio-inline">{{ Form::radio('record_com', 'No', '', array('id' => 'record_com_no')) }} No</label>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Operator number</label> 
                                        {!! Form::text('operator_no', null, ['class' => 'form-control', 'id' => 'operator_no']) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Operator Email</label> 
                                        {!! Form::text('operator_email', null, ['class' => 'form-control', 'id' => 'operator_email']) !!}
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="firstName1">Sms template to caller</label> 
                                        <textarea id="sms_template_caller" name="sms_template_caller" rows="6" cols="6" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="firstName1">Available Parameters</label> 
                                        <select id="param1" name="param" multiple="multiple" onchange="copyVal('sms_template_caller', 'param1')" size="6">
                                            <option value="{CUSTOMERNAME}">CUSTOMERNAME </option>
                                            <option value="{TICKETID}">TICKETID</option>
                                            <option value="{CUSTOMER DID}">CUSTOMER DID</option>
                                            <option value="{CALLERID}">CALLER ID</option>
                                            <option value="{DATETIME}">DATETIME</option>
                                        </select>
                                    </div>
                                </div>     
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="caller_sms_template_id">Caller SMS Template ID</label> 
                                        <input class="form-control" id="caller_sms_template_id" name="caller_sms_template_id" type="text" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="firstName1">Sms template to operator</label> 
                                        <textarea id="sms_template_operator" name="sms_template_operator" rows="6" cols="6" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="firstName1">Available Parameters</label> 
                                        <select id="param2" name="param" multiple="multiple" onchange="copyVal('sms_template_operator', 'param2')" size="6">
                                            <option value="{CUSTOMERNAME}">CUSTOMERNAME </option>
                                            <option value="{TICKETID}">TICKETID</option>
                                            <option value="{CUSTOMER DID}">CUSTOMER DID</option>
                                            <option value="{CALLERID}">CALLER ID</option>
                                            <option value="{DATETIME}">DATETIME</option>
                                        </select>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="operator_sms_template_id">Operator SMS Template ID</label> 
                                        <input class="form-control" id="operator_sms_template_id" name="operator_sms_template_id" type="text" autocomplete="off">
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="firstName1">Email to operator</label> 
                                        <textarea id="email_to_operator" name="email_to_operator" rows="6" cols="6" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="firstName1">Available Parameters</label> 
                                        <select id="param3" name="param" multiple="multiple" onchange="copyVal('email_to_operator', 'param3')" size="6">
                                            <option value="{CUSTOMERNAME}">CUSTOMERNAME </option>
                                            <option value="{TICKETID}">TICKETID</option>
                                            <option value="{CUSTOMER DID}">CUSTOMER DID</option>
                                            <option value="{CALLERID}">CALLER ID</option>
                                            <option value="{DATETIME}">DATETIME</option>
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

            <div class="modal fade" id="upload_comp" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Upload Compliants Recording Promot</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'upload_form', 'method' => 'post', 'files' => true]) !!} 
                        <div class="modal-body">
                            <table class="display table table-striped table-bordered" style="width:100%">
                                <tbody>
                                    <tr>
                                        <td nowrap="" align="left">Non Operatoer dpt</td>
                                        <td align="left" id="non_op_dept"></td>
                                    </tr>
                                </tbody>
                            </table>
                            {!! Form::hidden('file_lang', null, ['class' => 'form-control', 'id' => 'file_lang']) !!}
                            {!! Form::hidden('file_shortcode', null, ['id' => 'file_shortcode']) !!}
                            {!! Form::hidden('nonopt_id', '', array('id' =>'nonopt_id')) !!}
                            @foreach($languages as $lang)
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">File to play in {{$lang->Language}} *</label>
                                        <span id="lang_id_{{$lang->id}}" data-short-code="{{$lang->shortcode}}"></span> 
                                        {!! Form::file($lang->id, null, ['class' => 'form-control file_play', 'id' => '$lang->id', 'enctype' => 'multipart/form-data', 'multiple' => true]) !!}
                                    </div>
                                </div> 
                            @endforeach                        
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
    const dataTable = $('#non_opr_dept_table').DataTable();
    function copyVal(id, param){
        document.getElementById(id).value = document.getElementById(id).value + $("#"+param).val() ;
    }

    function setDepartment(dept_id) {
        var groupid = $("#customerId").val();
        $.ajax({
            type: "GET",
            url: '/get_department/'+ groupid, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                $('#departmentid').empty();
                $.each(res, function (i, item) {
                    if(dept_id == i) {
                        $('#departmentid').append('<option value="'+ i +'" selected>'+ item +'</option>');
                    } else {
                       $('#departmentid').append('<option value="'+ i +'">'+ item +'</option>'); 
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        }); 
    }
    $(document).ready(function() {
        var langs = [];
        $("input:file").change(function (){
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['gsm', 'wav']) == -1) {
                $("input:file").val('');
                return false;
            } else {
                var fileLang = $(this).attr("name");
                var shortcode = $("#lang_id_"+fileLang).attr("data-short-code");
                langs.push(fileLang+'_'+shortcode);
                $("#file_lang").val(langs);
                $("#file_shortcode").val(shortcode);
            }
        });

        $( '.upload_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addFiles") }}', // This is the url we gave in the route
            data: new FormData(this),
            dataType:'JSON',
            contentType: false,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false, 
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
                    $("#upload_comp").modal('hide');
                    toastr.success(res.success);            
                    setTimeout(function(){ location.reload() }, 500);
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $( '.non_operator_form' ).on( 'submit', function(e) {
            e.preventDefault();
            //var noteHTML = "";
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addNonOperator") }}', // This is the url we gave in the route
            data: $('.non_operator_form').serialize(),
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
                    $("#add_non_operator").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 500);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $(document).on('click', '.upload_complaints', function(e)
        {
            var uploadid = this.id;
            var upload_val = uploadid.replace("upload_", "");
            $("#nonopt_id").val(upload_val);
            $.ajax({
            type: "GET",
            url: '/get_non_operator/'+ upload_val, // This is the url we gave in the route
                success: function(result){ // What to do if we succeed
                    var res = result[0];
                    $("#non_op_dept").text(res.dept_name);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });             
        });
        
        $(document).on('click', '.edit_non_operator', function(e)
        {
            $("#exampleModalCenterTitle-2").text('Edit NonOperator Department');
            var id = $(this).attr("id");

            $.ajax({
            type: "GET",
            url: '/get_non_operator/'+ id, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                var res = result[0];
                $("#non_operator_id").val(res.id);
                $("#resellerid").val(res.resellerid);
                $("#customerId").val(res.groupid);
                setDepartment(res.departmentid);
                $("#departmentid").val(res.departmentid);
                $("#sms_to_caller").val(res.sms_to_caller);
                $("#sms_to_operator").val(res.sms_to_operator);
                $("#operator_no").val(res.operator_no);
                $("#operator_email").val(res.operator_email);
                $("#email_to_operator").val(res.email_to_operator);
                $("#sms_template_caller").val(res.sms_template_caller);
                $("#sms_template_operator").val(res.sms_template_operator);
                $("#caller_sms_template_id").val(res.templateid_caller);
                $("#operator_sms_template_id").val(res.templateid_operator);
                if(res.generateticket == 'Yes') {
                    $("#generateticket_yes").prop("checked", true);
                } else {
                    $("#generateticket_no").prop("checked", true);
                } 
                if(res.record_com == 'Yes') {
                    $("#record_com_yes").prop("checked", true);
                } else {
                    $("#record_com_no").prop("checked", true);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('#new_non_opt').click(function() {
            $(".non_operator_form").trigger("reset");
            $("#exampleModalCenterTitle-2").text('Add NonOperator Department');
        });
    });

    $(document).on('change', '#resellerid', function(e) {
        $('#departmentid').val([])
    });
</script>

@endsection

