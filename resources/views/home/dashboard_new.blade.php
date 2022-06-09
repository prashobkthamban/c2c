@extends('layouts.master')
@section('main-content')

<!-- plugins:css -->
<link rel="stylesheet" type="text/css" href="{{asset('d1/vendors/feather/feather.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('d1/vendors/mdi/css/materialdesignicons.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('d1/vendors/ti-icons/css/themify-icons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('d1/vendors/typicons/typicons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('d1/vendors/simple-line-icons/css/simple-line-icons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('d1/vendors/css/vendor.bundle.base.css')}}">
<!-- endinject -->
<!-- Plugin css for this page -->
<link rel="stylesheet" type="text/css" href="{{asset('d1/js/select.dataTables.min.css')}}">
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" type="text/css" href="{{asset('d1/css/vertical-layout-light/style.css')}}">
<!-- endinject -->
<style>
    label.badge {
        background-color: unset;
        min-width: 90px;
    }

    .table td,
    .table th {
        padding: 8px;
    }

    .notify-header .badge {
        color: #fff;
        border: none;
        padding: 0.25em 0.4em;
        font-weight: 600;
        font-size: 10px;
    }

    #doughnut-chart-legend ul li {
        margin-right: 10px;
    }

    #doughnut-chart-legend ul li span {
        margin-right: 3px;
    }

    #marketing-overview-legend ul li {
        margin-right: 10px;
    }
</style>

<form id="search_data" style="display:none;">
    <input type="hidden" name="from_date" id="from_date" />
    <input type="hidden" name="to_date" id="to_date" />

</form>
<div class="main-panel" style="width: 100%;margin-top: 48px;">
    <div class="content-wrapper">
        <div class="row">
            @if(count($announcements))
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card" style="border-radius: 5px;background: #ddf1ff;">
                    <div class="card-body" style="padding: 10px;">
                        @foreach($announcements as $listOne)
                        <h6 class="text-muted">{{$listOne->msg}}</h6>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                        @foreach($todaysData as $data)
                                        <div>
                                            <p class="statistics-title">{{$data['title']}}</p>
                                            <h3 class="rate-percentage text-center">{{$data['count']}}</h3>
                                            <!-- <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p> -->
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8 d-flex flex-column">
                                    <div class="row flex-grow">
                                        <div class="col-12 grid-margin stretch-card">
                                            <div class="card card-rounded">
                                                <div class="card-body">
                                                    <div class="d-sm-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h4 class="card-title card-title-dash">Hourly Calls</h4>
                                                            <p class="card-subtitle card-subtitle-dash">Today's call data on an hourly basis</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-sm-flex align-items-center mt-1 justify-content-between">
                                                        <div class="col-lg-7 offset-lg-5">
                                                            <div id="marketing-overview-legend"></div>
                                                        </div>
                                                    </div>
                                                    <div class="chartjs-bar-wrapper mt-3">
                                                        <canvas id="marketingOverview"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 d-flex flex-column">
                                    <div class="row flex-grow">
                                        <div class="col-12 grid-margin stretch-card">
                                            <div class="card card-rounded">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                <h4 class="card-title card-title-dash">Today's Calls</h4>
                                                            </div>
                                                            <canvas class="my-auto" id="doughnutChart" height="200"></canvas>
                                                            <div id="doughnut-chart-legend" class="mt-5 text-center"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(in_array(Auth::user()->usertype, ["groupadmin","reseller","operator"]))
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-addon input-group-prepend border-right">
                                                    <span class="icon-calendar input-group-text calendar-icon"></span>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" name="dashboard_date" id="dashboard_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(Auth::user()->usertype == 'reseller')
                            <div class="row">
                                <div class="col-lg-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- <h4 class="card-title">Incoming Calls</h4> -->
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Users</th>
                                                            <th>Answered Call</th>
                                                            <th>Missed Call</th>
                                                            <th>After Office/Voicemail</th>
                                                            <th>Total Call</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($customerCallData as $customer)
                                                        <tr>
                                                            <td>{{$customer->group_name}}</td>
                                                            <td>{{$customer->answeredCalls}}</td>
                                                            <td>{{$customer->missedCalls}}</td>
                                                            <td>{{$customer->afterOfficeCalls}}</td>
                                                            <td>{{$customer->totalCalls}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(Auth::user()->usertype == 'groupadmin')
                            <div class="row">
                                <div class="col-lg-4 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Incoming Calls</h4>
                                            <!-- <p class="card-description">
                                                        Add class <code>.table-hover</code>
                                                    </p> -->
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Status</th>
                                                            <th>Calls</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($incomingCallData as $row)
                                                        <tr>
                                                            <td><label class="badge {{$row['label_class']}}">{{$row['label']}}</label></td>
                                                            <td>{{$row['count']}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(count($operatorCallData) > 0)
                                <div class="col-lg-8 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Operators Calls</h4>
                                            <!-- <p class="card-description">
                                                        Add class <code>.table-hover</code>
                                                    </p> -->
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Operator</th>
                                                            <th>Answered</th>
                                                            <th>Missed</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($operatorCallData as $operator)
                                                        <tr>
                                                            <td>{{$operator->opername}}</td>
                                                            <td>{{$operator->answeredCalls}}</td>
                                                            <td>{{$operator->missedCalls}}</td>
                                                            <td>{{$operator->totalCalls}}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            @if(in_array(Auth::user()->usertype, ["groupadmin","operator"]))
                            @if(count($departmentData))
                            <div class="row">
                                <div class="col-lg-12 d-flex flex-column">
                                    <div class="row flex-grow">
                                        @foreach($departmentData as $name => $value)
                                        <div class="col-md-3 col-lg-3 grid-margin stretch-card">
                                            <div class="card card-rounded">
                                                <div class="card-body">
                                                    <h4 class="card-title card-title-dash text-center mb-4">{{$name}}</h4>
                                                    <div class="row text-center">
                                                        <div class="col-sm-6">
                                                            <p class="text-success"><b>Answered</b></p>
                                                            <span>{{$value['answeredCalls']}}</span>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <p class="text-danger"><b>Missed</b></p>
                                                            <span>{{$value['missedCalls']}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<!-- main-panel ends -->

<!-- plugins:js -->
<script type="text/javascript" src="{{asset('d1/vendors/js/vendor.bundle.base.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script type="text/javascript" src="{{asset('d1/vendors/chart.js/Chart.min.js')}}"></script>
<script type="text/javascript" src="{{asset('d1/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('d1/vendors/progressbar.js/progressbar.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/daterangepicker.js')}}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script type="text/javascript" src="{{asset('d1/js/off-canvas.js')}}"></script>
<script type="text/javascript" src="{{asset('d1/js/hoverable-collapse.js')}}"></script>
<!-- <script type="text/javascript" src="{{asset('d1/js/template.js')}}"></script> -->
<script type="text/javascript" src="{{asset('d1/js/settings.js')}}"></script>
<script type="text/javascript" src="{{asset('d1/js/todolist.js')}}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script type="text/javascript" src="{{asset('d1/js/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{asset('d1/js/dashboard.js')}}"></script>
<script type="text/javascript" src="{{asset('d1/js/Chart.roundedBarCharts.js')}}"></script>
<!-- End custom js for this page-->

<script>
    $('#dashboard_date').daterangepicker({
        startDate: "<?php echo $startDate; ?>",
        endDate: "<?php echo $endDate; ?>",
        locale: {
            format: 'DD-MMM-YYYY'
        }
    }, function(start, end, label) {

        $('#from_date').val(start.format('YYYY-MM-DD'));
        $('#to_date').val(end.format('YYYY-MM-DD'));
        $('#search_data').submit();
    });
</script>

@endsection