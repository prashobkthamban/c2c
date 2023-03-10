@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<style>
    .table-header-bg-color {
        background-color: #6633994f;
    }
</style>
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1> Voicefile Manager </h1>

</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_voice_file" class="btn btn-primary" id="add_voice"> Add New Voicefile</a>
                <div class="table-responsive">
                    <table id="voice_file_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="table-header-bg-color">Customer</th>
                                <th class="table-header-bg-color">Welcome</th>
                                <th>Multi Language file</th>
                                <th class="table-header-bg-color">DID</th>
                                <th>MOH</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="table-header-bg-color">Customer</th>
                                <th class="table-header-bg-color">Welcome</th>
                                <th>Multi Language file</th>
                                <th class="table-header-bg-color">DID</th>
                                <th>MOH</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- add operator modal -->
<div class="modal fade" id="add_voice_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Voicefile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['class' => 'add_voicefile_form', 'method' => 'post']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Customer *</label>
                        {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'groupid')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Did *</label>
                        {!! Form::select('did', ['' => 'Select Did'], null,array('class' => 'form-control', 'id' => 'did')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                        {!! Form::hidden('id', '', array('id' =>'voicefile_id')) !!}
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Welcome File</label>
                        {!! Form::select('wfile', array('PLAY' => 'Play Welcome', 'DO_NOT_PLAY' => 'Do not Play Welcome'), null,array('class' => 'form-control', 'id' => 'wfile')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3" id="welcome_msg_div">
                        <!-- <label for="firstName1">Welcome File</label>  -->
                        {!! Form::file('welcomemsg', array('class' => 'form-control', 'id' => 'welcomemsg')) !!}
                        {!! Form::hidden('old_welcomemsg', '', array('id' =>'old_welcomemsg')) !!}
                        <span id="welcomemsg_value"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Multi Language File</label>
                        {!! Form::select('languagesection', array('' => 'Do not Play Language'), null,array('class' => 'form-control', 'id' => 'languagesection')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <!-- <label for="firstName1">Welcome File</label>  -->
                        {!! Form::file('flanguagesection', array('class' => 'form-control', 'id' => 'flanguagesection')) !!}
                        {!! Form::hidden('old_flanguagesection', '', array('id' =>'old_flanguagesection')) !!}
                        <span id="flanguagesection_value"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Main Menu press</label>
                        {!! Form::select('mainmenupress0', $voicefilesnames->prepend('Select Mainmenupress', ''), null,array('class' => 'form-control', 'id' => 'mainmenupress0')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Thank4caling</label>
                        {!! Form::select('thank4caling', $thank4calling->prepend('Select Thank4caling', ''), null,array('class' => 'form-control', 'id' => 'thank4caling')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Repeat Options</label>
                        {!! Form::select('repeatoptions', $repeatoptions->prepend('Select Repeatoptions', ''), null,array('class' => 'form-control', 'id' => 'repeatoptions')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Previous Menu</label>
                        {!! Form::select('previousmenu', $previousmenu->prepend('Select Previous Menu', ''), null,array('class' => 'form-control', 'id' => 'previousmenu')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Voice Mail Msg</label>
                        {!! Form::select('voicemailmsg', $voicemailmsg->prepend('Select Voicemailmsg', ''), null,array('class' => 'form-control', 'id' => 'voicemailmsg')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Transfering Call</label>
                        {!! Form::select('trasfringcall', $trasfringcall->prepend('Select Transfering Call', ''), null,array('class' => 'form-control', 'id' => 'trasfringcall')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Contactus Soon</label>
                        {!! Form::select('contactusoon', $contactusoon->prepend('Select Contactus Soon', ''), null,array('class' => 'form-control', 'id' => 'contactusoon')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Talk to Operator press9</label>
                        {!! Form::select('talktooperator9', $talktooperator9->prepend('Select Talk to operator9', ''), null,array('class' => 'form-control', 'id' => 'talktooperator9')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">No Input</label>
                        {!! Form::select('noinput', $noinput->prepend('Select No Input', ''), null,array('class' => 'form-control', 'id' => 'noinput')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Wrong Input</label>
                        {!! Form::select('wronginput', $wronginput->prepend('Select Wrong Input', ''), null,array('class' => 'form-control', 'id' => 'wronginput')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Nonworkinghours</label>
                        {!! Form::select('nonworkinghours', $nonworkinghours->prepend('Select Nonworkinghours', ''), null,array('class' => 'form-control', 'id' => 'nonworkinghours')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">MOH-classname</label>
                        {!! Form::select('moh', $moh->prepend('Select MOH', ''), null,array('class' => 'form-control', 'id' => 'moh')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Transfering to different agent</label>
                        {!! Form::select('transferingtodifferentagent', $transferingagent->prepend('Select Transfering to different agent', ''), null,array('class' => 'form-control', 'id' => 'transferingtodifferentagent')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">Holiday</label>
                        {!! Form::select('holiday', $holiday->prepend('Select holiday', ''), null,array('class' => 'form-control', 'id' => 'holiday')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">AOM Before Welcome</label>
                        {!! Form::select('aombefore', $aombefore->prepend('Select Aom', ''), null,array('class' => 'form-control', 'id' => 'aombefore')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group mb-3">
                    </div>

                    <div class="col-md-8 form-group mb-3">
                        <label for="firstName1">AOM After Welcome</label>
                        {!! Form::select('aomafter', $aomafter->prepend('Select Aom', ''), null,array('class' => 'form-control', 'id' => 'aomafter')) !!}
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

<!-- <div class="customizer" title="Search" style="top:75px">
    <a href="#" data-toggle="collapse" data-target="#filter-panel">
        <div class="handle collapsed">
            <i class="i-Search-People"></i>
        </div>
    </a>
</div> -->

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    const dataTable = $('#voice_file_table').DataTable({
        "order": [
            [0, "desc"]
        ],
        "searchDelay": 1000,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("voiceFilesAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
            }
        },
        "columns": [{
                "data": "name"
            },
            {
                "data": "welcomemsg"
            },
            {
                "data": "flanguagesection"
            },
            {
                "data": "did_number"
            },
            {
                "data": "MOH"
            },
            {
                data: null,
                orderable: false,
                render: function(data, type) {
                    let htmlData = '<a href="#" data-toggle="modal" data-target="#add_voice_file" class="text-success mr-2 edit_voicefile" id="' + data.id + '">' +
                        '<i class="nav-icon i-Pen-2 font-weight-bold"></i>' +
                        '</a>' +
                        '<a href="javascript:void(0)" onClick="deleteItem(' + data.id + ', ' + "'did_voicefilesettings'" + ')" class="text-danger mr-2">' +
                        '<i class="nav-icon i-Close-Window font-weight-bold"></i>' +
                        '</a>';
                    return htmlData;
                }
            }
        ]
    });

    function reloadDataTable() {
        dataTable.draw();
    }

    $("#clear_btn").on("click", function() {
        $("#voice_file_filter_form")[0].reset();
        dataTable.draw();
    });
    $(document).ready(function() {
        $("input:file").change(function() {
            var ext = $('input:file').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gsm', 'wav']) == -1) {
                $("input:file").val('');
                return false;
            }
        });

        $('.add_voicefile_form').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("addVoicefile") }}', // This is the url we gave in the route
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
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
                        $("#add_voice_file").modal('hide');
                        toastr.success(res.success);
                        setTimeout(function() {
                            location.reload()
                        }, 300);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        $(document).on('click', '.edit_voicefile', function(e) {
            $("#exampleModalCenterTitle-2").text('Edit Voicefile');
            var id = $(this).attr("id");
            $.ajax({
                type: "GET",
                url: '/get_voicefile/' + id, // This is the url we gave in the route
                success: function(result) { // What to do if we succeed
                    var res = result[0];
                    console.log(res);
                    $("#groupid").val(res.groupid);
                    didList(res.groupid, res.did);
                    $("#voicefile_id").val(res.id);
                    $("#mainmenupress0").val(res.mainmenupress0);
                    $("#thank4caling").val(res.thank4caling);
                    $("#repeatoptions").val(res.repeatoptions);
                    $("#previousmenu").val(res.previousmenu);
                    $("#voicemailmsg").val(res.voicemailmsg);
                    $("#trasfringcall").val(res.trasfringcall);
                    $("#contactusoon").val(res.contactusoon);
                    $("#talktooperator9").val(res.talktooperator9);
                    $("#noinput").val(res.noinput);
                    $("#wronginput").val(res.wronginput);
                    $("#nonworkinghours").val(res.nonworkinghours);
                    $("#moh").val(res.MOH);
                    $("#transferingtodifferentagent").val(res.transferingtodifferentagent);
                    $("#holiday").val(res.holiday);
                    $("#aombefore").val(res.aombeforewelcome);
                    $("#aomafter").val(res.aomafterwelcome);
                    $("#old_flanguagesection").val(res.flanguagesection);
                    $("#flanguagesection_value").text(res.flanguagesection);
                    let wFile = res.welcomemsg ? 'PLAY' : 'DO_NOT_PLAY';
                    if (wFile == 'PLAY') {
                        $("#welcome_msg_div").show();
                    } else {
                        $("#welcome_msg_div").hide();
                    }
                    $("#wfile").val(wFile);
                    $("#old_welcomemsg").val(res.welcomemsg);
                    $("#welcomemsg_value").text(res.welcomemsg);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });
        });

        $(document).on("click", '#add_voice', function() {
            $(".add_voicefile_form")[0].reset();
            $("#welcomemsg_value").text("");
            $("#flanguagesection_value").text("");
            $("#exampleModalCenterTitle-2").text('Add Voicefile');
            $("#did").find('option').not(':first').remove();
        });

        $("#wfile").on('change', function() {
            if ($(this).val() == 'PLAY') {
                $("#welcome_msg_div").show();
            } else {
                $("#welcome_msg_div").hide();
            }
        })
    });
</script>
@endsection