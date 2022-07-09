@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<style>
    .dropdown-menu>li>a {
        margin: 4px;
        padding-bottom: 7px;
        padding-top: 7px;
        border-radius: 3px;
        line-height: 18px;
    }

    .customizer {
        z-index: 0;
    }

    .select2-container {
        width: 100% !important;
    }

    audio {
        margin-left: 82px;
    }

    .datepicker {
        z-index: 1600 !important;
        /* has to be larger than 1050 */
    }
</style>
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>Archived CDR Report </h1>

</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div id="filter-panel" class="col-lg-12 col-md-12 filter-panel collapse {{count($requests) > 0 ? 'show' : ''}}">
        <div class="card mb-2">
            <div class="card-body">
                <div>
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="cdr_filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                            <div class="col-md-4" id="customer_div">
                                <label class="filter-col" for="pref-perpage">Customers</label>
                                <select name="customer" class="form-control" id="customer_id">
                                    <option value="">All</option>
                                    @if(!empty($customers))
                                    @foreach($customers as $customer )
                                    <option value="{{$customer->id}}" @if(isset($requests['customer']) && $customer->id == $requests['customer']) selected @endif>{{$customer->name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="customer" id="customer_id" value="{{Auth::user()->groupid}}" />
                            @endif
                            @if(in_array(Auth::user()->usertype, ["groupadmin", "operator", "reseller"]))
                                <div class="col-md-4">
                                    <label class="filter-col" for="pref-perpage">Departments</label>
                                    <select name="department" class="form-control" id="department_id">
                                        <option value="">All</option>
                                        @if(!empty($departments))
                                        @foreach($departments as $dept )
                                        <option value="{{$dept->dept_name}}" @if(isset($requests['department']) && $dept->dept_name == $requests['department']) selected @endif>{{$dept->dept_name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @if(in_array(Auth::user()->usertype, ["groupadmin", "reseller"]))
                                <div class="col-md-4">
                                    <label class="filter-col" for="pref-perpage">Operators</label>
                                    <select name="operator" class="form-control" id="operator_id">
                                        <option value="">All</option>
                                        @if(!empty($operators))
                                        @foreach($operators as $opr )
                                        <option value="{{$opr->id}}" @if(isset($requests['operator']) && $opr->id == $requests['operator']) selected @endif>{{$opr->opername}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @elseif(Auth::user()->usertype == 'operator')
                                    <input type="hidden" name="operator" id="operator_id" value="{{Auth::user()->operator_id}}" />
                                @endif
                                <div class="col-md-4">
                                    <label class="filter-col" for="pref-perpage">Cdr Tag</label>
                                    {!! Form::select('tag', $tags->prepend('Select Tag', ''), isset($requests['tag']) ? $requests['tag'] : '' ,array('class' => 'form-control', 'id' => 'tag')) !!}
                                </div>
                            @endif
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Status</label>
                                {!! Form::select('status', array('' => 'All', 'MISSED' => 'Missed', 'ANSWERED' => 'Answered', 'DIALING' => 'Dialing', 'LIVECALL' => 'Live call', 'AFTEROFFICE' => 'After Office'), isset($requests['status']) ? $requests['status'] : '',array('class' => 'form-control', 'id' => 'status_id')) !!}
                            </div>
                            @if(in_array(Auth::user()->usertype, ["groupadmin", "operator", "reseller"]))
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Assigned To</label>
                                <select class="form-control" name="assigned_to" id="assigned_to_id">
                                    <option value="">All</option>
                                    @if(!empty($operators))
                                    @foreach($operators as $opr )
                                    <option value="{{$opr->id}}" @if(isset($requests['assigned_to']) && $opr->id == $requests['assigned_to']) selected @endif>{{$opr->opername}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Dnid Name</label>
                                <select class="form-control" name="did_no" id="did_no">
                                    <option value="">All</option>
                                    @if(!empty($dnidnames))
                                    @foreach($dnidnames as $dnid )
                                    <option value="{{$dnid->did_no}}" @if(isset($requests['did_no']) && $dnid->did_no == $requests['did_no']) selected @endif>{{$dnid->did_no}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-search">By Caller Number</label>
                                <input type="text" class="form-control input-sm" id="caller_number" name="caller_number" value="@if(isset($requests['caller_number'])) {{$requests['caller_number']}} @endif">
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Date</label>
                                <select class="form-control" name="date" id="date_select">
                                    @foreach($dateOptions as $key => $val )
                                    <option value="{{$key}}" @if(isset($requests['date']) && $key == $requests['date']) selected @endif>{{$val}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 custom_date_div" @if((isset($requests['date']) && $requests['date'] != 'custom') || !isset($requests['date'])) style="display: none;" @endif>
                                <label>Start Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" value="@if(isset($requests['start_date'])) {{$requests['start_date']}} @endif" name="start_date" id="start_date">
                                    <div class="input-group-append">
                                        <label class="btn btn-secondary" for="start_date">
                                            <i class="icon-regular i-Calendar-4"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 custom_date_div" @if((isset($requests['date']) && $requests['date'] != 'custom') || !isset($requests['date'])) style="display: none;" @endif>
                                <label>End Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" value="@if(isset($requests['end_date'])) {{$requests['end_date']}} @endif" name="end_date" id="end_date">
                                    <div class="input-group-append">
                                        <label class="btn btn-secondary" for="end_date">
                                            <i class="icon-regular i-Calendar-4"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 24px;">
                                <button type="button" id="search_btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                <button type="button" id="clear_btn" class="btn btn-outline-secondary" name="clear_btn">Clear</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cdr_archive_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Caller Id</th>
                                <th>Date & Time</th>
                                <th>Duration</th>
                                <th>Coin</th>
                                <th>Status</th>
                                <th>Department</th>
                                <th>Operator</th>
                                <th>DID</th>
                                <th>Assignedto</th>
                                <!-- <th>Rec</th> -->
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Caller Id</th>
                                <th>Date & Time</th>
                                <th>Duration</th>
                                <th>Coin</th>
                                <th>Status</th>
                                <th>Department</th>
                                <th>Operator</th>
                                <th>DID</th>
                                <th>Assignedto</th>
                                <!-- <th>Rec</th> -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end of col -->

</div>
<!-- end of row -->


<div class="customizer" title="Search" style="top:73px">
    <a href="#" data-toggle="collapse" data-target="#filter-panel">
        <div class="handle collapsed">
            <i class="i-Search-People"></i>
        </div>
    </a>
</div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
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
    const cdrTable = $('#cdr_archive_table').DataTable({
        "order": [
            [2, "desc"]
        ],
        "searching": false,
        "searchDelay": 1000,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("cdrDataAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
                data.customer = $("#customer_id").val();
                data.department = $("#department_id").val();
                data.operator = $("#operator_id").val();
                data.tag = $("#tag").val();
                data.status = $("#status_id").val();
                data.assigned_to = $("#assigned_to_id").val();
                data.did_no = $("#did_no").val();
                data.caller_number = $("#caller_number").val();
                data.date = $("#date_select").val();
                data.start_date = $("#start_date").val();
                data.end_date = $("#end_date").val();
                data.fetchArchive = true;
            }
        },
        "headerCallback": function( thead, data, start, end, display ) {
            let title = '{{Auth::user()->usertype}}' == 'admin' ? 'Customer' : '#';
            $(thead).find('th').eq(0).html(title);
        },
        "columns": [
            {
                data: null,
                orderable: '{{Auth::user()->usertype}}' == 'admin' ? true : false,
                render: function(data, type, row, meta) {
                    if (data.userType == 'admin') {
                        return data.customerName;
                    }
                    return meta.row+1;
                }
            },
            {
                "orderable": false,//need to change later
                "data": "callerId"
            },
            {
                "data": "dateTime"
            },
            {
                "data": "duration"
            },
            {
                "data": "creditUsed"
            },
            {
                data: null,
                render: function(data, type) {
                    return data.status + '(' + data.cdrSubCount + ')';
                }
            },
            {
                "data": "departmentName"
            },
            {
                "data": "operatorName"
            },
            {
                orderable: false,
                data: "didNumber"
            },
            {
                orderable: false,
                data: "assignedOperatorName"
            },
        ]
    });

    $("#search_btn").on("click", function() {
        cdrTable.draw();
    })

    $("#clear_btn").on("click", function() {
        $("#cdr_filter_form")[0].reset();
        cdrTable.draw();
    });
    // var table = $('#cdr_table').DataTable();

    // var data = table.buttons.exportData( {
    //     columns: ':visible'
    // } );
    //     $('#example').DataTable( {
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ]
    // } );
    $('#timepicker1').timepicker();
</script>
<script type="text/javascript">
    function moreOption(id, did_no, firstLeg, creditUsed) {
        var className = $("#second_row").attr('class');
        if (className == 'show') {
            $("#second_row").remove();
        } else {
            var tag = $('#row_' + id + ' .more-details').attr('data-tag');
            var operName = $('#row_' + id + ' .more-details').attr('data-operatorname');
            $('#row_' + id).after('<tr id="second_row" class="show"><td></td><td colspan="7"><span style="margin-right:100px;"><b>DNID :</b>' + did_no + '</span><span style="margin-right:100px;"><b>Duration :</b>' + firstLeg + '</span><span style="margin-right:100px;"><b>Coin :</b>' + creditUsed + '</span><span style="margin-right:100px;"><b>Assigned To :</b> <span id="assigned_' + id + '">' + operName + '</span></span><span style="margin-right:100px;"><b>Tag :</b> <span id="cdrTag_' + id + '">' + tag + '</span></span></td></tr>');
        }
    }

    function selectAll() {
        if ($('#allselect').is(":checked")) {
            $(".allselect").prop("checked", true);
        } else {
            $(".allselect").prop("checked", false);
        }
    }

    function xajax_show(id) {
        $(".cdr_form").addClass('d-none');
        $("#" + id).removeClass('d-none');
    }

    function xajax_hide() {
        $(".cdr_form").addClass('d-none');
    }

    function xajax_play(id) {
        $("#" + id).removeClass('d-none');
    }

    function cdrDial(phone) {
        $("#customer_number").val(phone);
    }

    $(document).ready(function() {
        $('.more_data').hide();
        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy'
        });
        $('.timepicker').pickatime();

        $('#dial_form').on('submit', function(e) {
            e.preventDefault();
            $('#dial_submit').val('Dialing...');
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("AddCdr") }}', // This is the url we gave in the route
                data: $('#dial_form').serialize(),
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
                        $("#dial_modal").modal('hide');
                        toastr.success(res.success);
                        setTimeout(function() {
                            location.reload(true);
                        }, 300);

                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        $('#msg_form').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("SendMessage") }}', // This is the url we gave in the route
                data: $('#msg_form').serialize(),
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
                        $("#msg_modal").modal('hide');
                        toastr.success(res.success);
                        setTimeout(function() {
                            location.reload(true);
                        }, 300);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        //add reminder
        $('.reminder_form').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("addReminder") }}', // This is the url we gave in the route
                data: $('.reminder_form').serialize(),
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
                        $("#add_reminder_modal").modal('hide');
                        toastr.success(res.success);
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        //show graph
        $('.graph_report').on('submit', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "POST",
                url: '{{ URL::route("graphReport") }}', // This is the url we gave in the route
                data: $('.graph_report').serialize(),
                success: function(res) { // What to do if we succeed
                    console.log('res', res)
                    if (res.error) {
                        $.each(res.error, function(index, value) {
                            if (value.length != 0) {
                                errors += value[0];
                                errors += "</br>";
                            }
                        });
                        toastr.error(errors);
                    } else {
                        var answrd = Object.values(res.answered);
                        var dialed = Object.values(res.dialed);
                        var dates = Object.values(res.dates);
                        const max = res.max;
                        $("#graph_search_modal").modal('hide');
                        $("#graph_modal").modal('show');
                        var echartElemBar = document.getElementById('echartBar');
                        if (echartElemBar) {
                            var echartBar = echarts.init(echartElemBar);
                            echartBar.setOption({
                                legend: {
                                    borderRadius: 0,
                                    orient: 'horizontal',
                                    x: 'right',
                                    data: ['Dialed', 'Answered']
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
                                    data: dates,
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
                                        formatter: '{value}'
                                    },
                                    min: 0,
                                    max: 10,
                                    interval: 2,
                                    axisLine: {
                                        show: false
                                    },
                                    splitLine: {
                                        show: true,
                                        interval: 'auto'
                                    }
                                }],
                                series: [{
                                    name: 'Dialed',
                                    data: dialed,
                                    label: {
                                        show: false,
                                        color: '#0168c1'
                                    },
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
                                    name: 'Answered',
                                    data: answrd,
                                    label: {
                                        show: false,
                                        color: '#639'
                                    },
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
                            $(window).on('resize', function() {
                                setTimeout(function() {
                                    echartBar.resize();
                                }, 500);
                            });
                        }
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    toastr.error('Some errors are occured');
                }
            });
        });

        //filter operation
        $('#graph').on('change', function(e) {
            e.preventDefault();
            var errors = '';
            $.ajax({
                type: "GET",
                url: '/search_cdr/' + $(this).val(), // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    $("#graph_modal").modal('show');
                }
            });
        });

        $('.close').on('click', function(e) {
            $(".graph_report")[0].reset();
        });

        $(document).on('click', '.edit_tag', function(e) {
            var id = $(this).attr("id");
            var tag = $(this).attr("data-tag");
            var cdrid = id.replace("tag_", "");
            console.log('tag : ' + tag);
            $("#cdrid").val(cdrid);
            if (tag != '') {
                $("#tag_title").text("Update Tag");
                $("#cdr_tag").val(tag);
            } else {
                $("#tag_title").text("Add Tag");
                $("#cdr_tag").val('');
            }
        });

        $(document).on('click', '.edit_contact', function(e) {
            var id = $(this).attr("id");
            var email = $(this).attr("data-email");
            var fname = $(this).attr("data-fname");
            var lname = $(this).attr("data-lname");
            var phone = $(this).attr("data-phone");
            var contactid = id.replace("contact_", "");
            $("#contact_id").val(contactid);
            $("#emailaddress").val(email);
            $("#fname").val(fname);
            $("#lname").val(lname);
            $("#phone").val(phone);
            if (contactid != '') {
                $("#contact_title").text("Update Contact");
            } else {
                $("#contact_title").text("Add Contact");
            }

        });

        $(document).on('click', '.play_audio', function(e) {
            var file = $(this).attr("data-file");
            file = 'voicefiles/' + file;
            $("#play_src").html('<audio controls id="audioSource"><source src="' + file + '" type="video/mp4"></source></audio>');
        });

        $(document).on('hide.bs.modal', '#play_modal', function() {
            $("#audioSource").remove();
        });

        $(document).on('click', '.edit_reminder', function(e) {
            var id = $(this).attr("id");
            var cdrid = id.replace("add_reminder_", "");
            $("#cdr_id").val(cdrid);
        });

        $(document).on('click', '.add_note', function(e) {
            var id = $(this).attr("id");
            var uniqueid = id.replace("add_note_", "");
            $("#uniqueid").val(uniqueid);
        });

        $(document).on('click', '.notes_list', function(e) {
            var id = $(this).attr("id");
            var uniqueid = id.replace("notes_", "");
            $.ajax({
                url: '/notes/' + uniqueid, // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    var response = JSON.stringify(res);
                    var noteHTML = "";
                    if (res.length > 0) {
                        $.each(res, function(idx, obj) {
                            noteHTML += "<tr class='cmnt_row_" + obj.id + "'>";
                            noteHTML += "<td>" + obj.operator + "</td>";
                            noteHTML += "<td>" + obj.datetime + "</td>";
                            noteHTML += "<td>" + obj.note + "</td>";
                            noteHTML += "<td><a href='#' class='text-danger mr-2 delete_comment' id='" + obj.id + "'><i class='nav-icon i-Close-Window font-weight-bold'></td>";
                            noteHTML += "</tr>";

                        });
                    } else {
                        noteHTML += "<tr><td colspan='4'><center>No Data Found</center></td></tr>";
                    }
                    $("#notes_list_table tbody").html(noteHTML);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });

        });

        $('.history_list').on('click', function(e) {
            var id = $(this).attr("id");
            var number = id.replace("history_", "");
            $.ajax({
                url: '/call_history/' + number, // This is the url we gave in the route
                success: function(res) { // What to do if we succeed
                    var response = JSON.stringify(res);
                    var historyHTML = "";
                    if (res.length > 0) {
                        $.each(res, function(idx, obj) {
                            historyHTML += "<tr>";
                            historyHTML += "<td>" + ++idx + "</td>";
                            historyHTML += "<td>" + obj.number + "</td>";
                            historyHTML += "<td>" + obj.datetime + "</td>";
                            historyHTML += "<td>" + obj.status + "</td>";
                            historyHTML += "</tr>";

                        });
                    } else {
                        historyHTML += "<tr><td colspan='3'><center>No Data Found</center></td></tr>";
                    }
                    $("#history_list_table tbody").html(historyHTML);
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
            });

        });

    });

    $('.add_contact').click(function() {
        $("#contact_form").removeClass('hide');
    });

    $(document).on("change", "#date_select", function() {
        var date_val = $(this).val();
        $(".custom_date_div").hide();
        if (date_val == 'custom') {
            $(".custom_date_div").show();
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click", ".remove", function() {

            var sub = $(this).closest("tr").find("input.amount").val();
            if (sub != '') {
                $(this).closest("tr").remove();
                total_amount();
            } else {
                $(this).closest("tr").remove();
                total_amount();
            }

        });
        $("body").on("change", ".quantity", function() {
            //alert($(this).val());
            var quantity = $(this).val();
            var am = $(this).closest("tr").find("input.pro_amount").val();
            var sub_amount = quantity * am;
            //alert(quantity*am);
            $(this).closest("tr").find("input.sub_amount").val(parseFloat(sub_amount).toFixed(2));
            total_amount();
        });
    });

    function GetDynamicTextBox(value) {
        return '<td><select name="products[]" id="products" class="form-control js-example-basic-single products"><option>Select Products</option>@if(!empty($products)) @foreach($products as $prod )<option value="{{$prod->id}}">{{$prod->name}}</option>@endforeach @endif</select><input type="hidden" name="pro_amount[]" id="pro_amount" class="form-control pro_amount"> </td><td><input type="number" name="quantity[]" id="quantity" class="form-control quantity"placeholder="Enter Quantity" min="1" /></td><td><input type="text" name="sub_amount[]" id="sub_amount" class="form-control sub_amount" placeholder="Sub Amount" readonly="" /></td><td><button type="button" class="btn btn-danger remove" data-toggle="tooltip" data-original-title="Remove"><i class="nav-icon i-Close-Window"></i></button></td>';
    }

    function total_amount() {
        var sum = 0.0;
        $('.sub_amount').each(function() {
            //alert($(this).val());
            sum += Number($(this).val());
        });
        $('#total_amount').val(parseFloat(sum).toFixed(2));
    }

    function assignoper(cdr_id, operator_id, opername, type) {
        var cdrIds = [];
        if (cdr_id == '0') {
            $(".allselect:checked").each(function() {
                cdrIds.push($(this).attr('id'));
            })
        } else {
            cdrIds.push(cdr_id);
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('assign_cdr') }}",
            data: {
                'cdr_id': cdrIds,
                'opr_id': operator_id,
                'type': type,
            },
            success: function(data) {
                if (data.status) {
                    cdrIds.forEach(function(cdrId) {
                        $('#row_' + cdrId + ' .more-details').data('operatorname', opername);
                        $("#assigned_" + cdrId).text(opername);
                    });
                    toastr.success(data.message);
                } else {
                    toastr.error('Some errors are occured');
                }
            }

        });
    }
    $(document).on("click", ".show-cdr-sub", function() {
        var data = {
            'cdrId': $(this).parent('tr').data('cdr-id')
        };
        var url = "{{ url('cdrreport/call_details') }}";
        ajaxCall(url, data)
            .then(function(result) {
                if (result.status) {
                    $('#call_details_modal .modal-body').html(result.content);
                } else {
                    toastr.error(result.message);
                }
            });
    });

    if ($("#customer_div").length) {
        if ($("#customer_id").val() == "") {
            resetData();
        }
    }
    $("#customer_id").on("change", function() {
        if ($("#customer_id").val() == "") {
            resetData();
        } else {
            fetchDepartments();
            fetchOperators();
            fetchTags();
            fetchDidNumbers();
        }
    })

    function resetData() {
        $("#department_id").find('option').not(':first').remove();
        $("#department_id").val("");
        $("#operator_id").find('option').not(':first').remove();
        $("#operator_id").val("");
        $("#tag").find('option').not(':first').remove();
        $("#tag").val("");
        $("#assigned_to_id").find('option').not(':first').remove();
        $("#assigned_to_id").val("");
        $("#did_no").find('option').not(':first').remove();
        $("#did_no").val("");
    }

    function fetchDepartments() {
        var data = {
            'groupId': $("#customer_id").val()
        };
        var url = "{{ url('fetch_departments') }}";
        ajaxCall(url, data)
            .then(function(result) {
                if (result.status) {
                    var html = '<option value="">All</option>';
                    result.data.forEach(function(data) {
                        html += '<option value="' + data.dept_name + '" >' + data.dept_name + '</option>';
                    });
                    $("#department_id").html(html);
                } else {
                    toastr.error(result.message);
                }
            });
    }

    function fetchOperators() {
        var data = {
            'groupId': $("#customer_id").val()
        };
        var url = "{{ url('fetch_operators') }}";
        ajaxCall(url, data)
            .then(function(result) {
                if (result.status) {
                    var html = '<option value="">All</option>';
                    result.data.forEach(function(data) {
                        html += '<option value="' + data.id + '" >' + data.opername + '</option>';
                    });
                    $("#operator_id").html(html);
                    $("#assigned_to_id").html(html);
                } else {
                    toastr.error(result.message);
                }
            });
    }

    function fetchTags() {
        var data = {
            'groupId': $("#customer_id").val()
        };
        var url = "{{ url('fetch_tags') }}";
        ajaxCall(url, data)
            .then(function(result) {
                if (result.status) {
                    var html = '<option value="">All</option>';
                    $.each(result.data, function(key, value) {
                        html += '<option value="' + key + '" >' + value + '</option>';
                    });
                    $("#tag").html(html);
                } else {
                    toastr.error(result.message);
                }
            });
    }

    function fetchDidNumbers() {
        var data = {
            'groupId': $("#customer_id").val()
        };
        var url = "{{ url('fetch_did_numbers') }}";
        ajaxCall(url, data)
            .then(function(result) {
                if (result.status) {
                    var html = '<option value="">All</option>';
                    result.data.forEach(function(data) {
                        html += '<option value="' + data.did_no + '" >' + data.did_no + '</option>';
                    });
                    $("#did_no").html(html);
                } else {
                    toastr.error(result.message);
                }
            });
    }
</script>

@endsection