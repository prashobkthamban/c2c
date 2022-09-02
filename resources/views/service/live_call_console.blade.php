<link rel="stylesheet" href="{{asset('assets/styles/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<style>
    .draggable {
        position: fixed;
        z-index: 9;
        width: 40%;
        right: 20px;
        border-radius: 3px;
    }
    #live_call_console .console-div {
        margin-bottom: 0px !important;
        max-height: 200px;
        overflow: auto;
        padding: 0;
    }
    #live_call_console h3 {
        margin-top: 0px !important;
        background-color: #7FB77E;
        border: #7FB77E;
    }
    #live_call_console .table-responsive {
        margin-top: 0px !important;
    }
    #live_call_console ::-webkit-scrollbar {
        width: 2px;
    }
    #live_call_console {
        font-size: 13px;
    }
    #live_call_console .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
    }
</style>
<script src="{{asset('assets/js/jquery-3.6.0.js')}}"></script>
<script src="{{asset('assets/js/jquery-ui.js')}}"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<div id="live_call_console" class="draggable ui-widget-content">
    <h3>&nbsp;&nbsp;&nbsp;&nbsp;Live Call Console</h3>
    <div class="col-md-12 mb-4 console-div">
        <div class="table-responsive">
            <table id="live_console_table" class="display table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Caller ID</th>
                        <th>Department</th>
                        <th>Operator</th>
                        <th>Duration</th>
                        <th>Call Status</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let topHeight = $(window).height()- ($("#live_call_console").outerHeight() + 200);
    $("#live_call_console").css('top', topHeight);
    $(function() {
        $("#live_call_console").draggable({
            containment: ".main-content"
        });
        $("#live_call_console").accordion({
            collapsible: true
        });
        // $("#live_call_console").resizable();
    });
    const liveCallDataTable = $('#live_console_table').DataTable({
        "order": [[0, "desc" ]],
        "searching": false,
        "lengthChange": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '{{ URL::route("liveCallsDataAjaxLoad") }}',
            "type": "POST",
            "data": function(data) {
                data._token = "{{ csrf_token() }}";
            }
        },
        "columnDefs": [
            { targets: 0, visible: ['admin', 'reseller'].includes('{{Auth::user()->usertype}}') }
        ],
        "columns": [
            {
                "data": "customerName"
            },
            {
                "data": "callerId"
            },
            {
                "data": "departmentName"
            },
            {
                "data": "operatorName"
            },
            {
                "data": "duration"
            },
            {
                "data": "callStatus"
            }
        ]
    });
    setInterval(function () {
        liveCallDataTable.ajax.reload(null, false);
    }, 10000);
</script>