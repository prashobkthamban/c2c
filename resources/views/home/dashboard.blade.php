@extends('layouts.master')
@section('main-content')
           <div class="breadcrumb">
                <h1>Version 1</h1>
                <ul>
                    <li><a href="">Dashboard</a></li>
                    <li>Version 1</li>
                </ul>
            </div>

            <div class="separator-breadcrumb border-top"></div>

            <div class="row">
                <?php if(Auth::user()->usertype == 'admin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Administrator"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">No of Users</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $nousers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Administrator"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Inactive Users</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $inusers }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Administrator"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Active Operators</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $activeoperator }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator' || Auth::user()->usertype == 'admin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Telephone"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Today's Total Calls</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ (Auth::user()->usertype == 'groupadmin' ? $g_callstoday : (Auth::user()->usertype == 'operator' ? $o_callstoday : $callstoday))}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Headphone"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-3">Live Calls</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ (Auth::user()->usertype == 'groupadmin' ? $g_activecalls  : $activecalls) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(Auth::user()->usertype == 'groupadmin'): ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Thumbs-Up-Smiley"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">IVR Answered</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $ivranswer }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Thumbs-Down-Smiley"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-3">IVR Missed</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{ $ivrmissed }}</p>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php endif; ?> 

               
            </div>

            <div class="row" style="margin-bottom: 34px;">
                <div class="col-md-6">
                    <h5>Select Date Range</h5>
                    <input type="text" id="config-demo" class="form-control" value="">
                </div>
                <div class="col-md-6"></div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Pie Chart Based on Call Status</div>
                            <div id="echartPie" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Bar Stacked Chart- Hourly call Trafic</div>
                            <div id="echartBar" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>       
            </div>

@endsection

@section('page-js')
     <script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
     <script src="{{asset('assets/js/es5/echart.options.min.js')}}"></script>
     <script src="{{asset('assets/js/moment.js')}}"></script>
     <script src="{{asset('assets/js/daterangepicker.js')}}"></script>
     <script type="text/javascript">
        $(document).ready(function () {
            var data = <?php echo !empty($p_data) ? json_encode($p_data) : json_encode(array()); ?>;
            var bdata = <?php echo !empty($series) ? json_encode($series) : json_encode(array()); ?>;
            //console.log('sds', data);
            // Chart in Dashboard version 1
            var echartElemBar = document.getElementById('echartBar');
            if (echartElemBar) {
                var echartBar = echarts.init(echartElemBar);
                echartBar.setOption({
                    legend: {
                        borderRadius: 0,
                        orient: 'horizontal',
                        x: 'right',
                        data: ['Online', 'Offline']
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
                        data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
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
                            formatter: '${value}'
                        },
                        min: 0,
                        max: 100000,
                        interval: 25000,
                        axisLine: {
                            show: false
                        },
                        splitLine: {
                            show: true,
                            interval: 'auto'
                        }
                    }],

                    series: [{
                        name: 'Online',
                        data: [35000, 69000, 22500, 60000, 50000, 50000, 30000, 80000, 70000, 60000, 20000, 30005],
                        label: { show: false, color: '#0168c1' },
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
                        name: 'Offline',
                        data: [45000, 82000, 35000, 93000, 71000, 89000, 49000, 91000, 80200, 86000, 35000, 40050],
                        label: { show: false, color: '#639' },
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
                $(window).on('resize', function () {
                    setTimeout(function () {
                        echartBar.resize();
                    }, 500);
                });
            }

            // Chart in Dashboard version 1
            var echartElemPie = document.getElementById('echartPie');
            if (echartElemPie) {
                var echartPie = echarts.init(echartElemPie);
                echartPie.setOption({
                    color: ['#62549c', '#8877bd'],
                    tooltip:   {
                        show: true,
                        backgroundColor: 'rgba(0, 0, 0, .8)'
                    },
                    series: [{
                        name: 'Call Status',
                        type: 'pie',
                        radius: '60%',
                        center: ['50%', '50%'],
                        data: data,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                });
                $(window).on('resize', function () {
                    setTimeout(function () {
                        echartPie.resize();
                    }, 500);
                });
            }

            $('#config-demo').daterangepicker({
            "startDate": "<?php echo $sdate; ?>",
            "endDate": "<?php echo $edate; ?>"
            }, function(start, end, label) { 
                
                $('#dfrom').val(start.format('DD-MM-YYYY'));
                $('#dto').val(end.format('DD-MM-YYYY'));
                $('#dhid').submit();
            });
        });
     </script>
@endsection
<form id="dhid" style="display:none;">
<input type="hidden" name="dfrom" id="dfrom" />
<input type="hidden" name="dto" id="dto" />

</form>