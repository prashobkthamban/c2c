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

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">This Year Sales</div>
                            <div id="echartBar11" style="height: 300px;"></div>
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
            var dates = <?php echo !empty($dates) ? json_encode($dates) : json_encode(array()); ?>;
            var missed = <?php echo !empty($missed) ? json_encode($missed) : json_encode(array()); ?>;
            var answered = <?php echo !empty($answered) ? json_encode($answered) : json_encode(array()); ?>;
            console.log('sds', missed);
            console.log('sds111', answered);
            // Chart in Dashboard version 1
            var echartElemBar = document.getElementById('echartBar');
            var seriesdata = <?php echo !empty($series) ? json_encode($series) : json_encode(array()); ?>;
            //console.log('sds222', seriesdata);
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
                    xAxis: {
                        mode: 'categories',
                    },
                    yAxis: {color: '#edeff0'},
                    series: [{
                        name: 'Call Status',
                        label: { show: false, color: '#639' },
                        type: 'bar',
                        color: '#7569b3',
                        smooth: true,
                        data: seriesdata,
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
                    color: ['#B22222', '#1A9B5C','#9370DB','#5B50D5'],
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


            var echartElemBar = document.getElementById('echartBar11');
            if (echartElemBar) {
                var echartBar = echarts.init(echartElemBar);
                echartBar.setOption({
                    legend: {
                        borderRadius: 0,
                        orient: 'horizontal',
                        x: 'right',
                        data: ['Missed', 'Answered']
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
                        name: 'Missed',
                        data: missed,
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
                        name: 'Answered',
                        data: answered,
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
