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
                @if(Auth::user()->load('accountdetails')->accountdetails != null && Auth::user()->load('accountdetails')->accountdetails->crm == 1)
                <div class="col-lg-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Pie Chart Based on Call Status</div>
                            <div id="echartPie" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Pie Chart Based on CRM Leads</div>
                            <div id="crm" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Pie Chart Based on Call Status</div>
                            <div id="echartPie" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-12 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Multi Line - Hourly call Trafic</div>
                            <div id="multiLine1" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            @if(Auth::user()->load('accountdetails')->accountdetails != null && Auth::user()->load('accountdetails')->accountdetails->crm == 1)
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">This Year Sales</div>
                            <div id="echartBar11" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

@endsection

@section('page-js')
     <script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
     <script src="{{asset('assets/js/es5/echart.options.min.js')}}"></script>
     <script src="{{asset('assets/js/moment.js')}}"></script>
     <script src="{{asset('assets/js/daterangepicker.js')}}"></script>

     <script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
     <script src="{{asset('assets/js/es5/echarts.script.min.js')}}"></script>

     <script src="{{asset('assets/js/es5/script.min.js')}}"></script>
     <script type="text/javascript">
        $(document).ready(function () {
            var data = <?php echo !empty($p_data) ? json_encode($p_data) : json_encode(array()); ?>;
            var bdata = <?php echo !empty($series) ? json_encode($series) : json_encode(array()); ?>;
            var dates = <?php echo !empty($dates) ? json_encode($dates) : json_encode(array()); ?>;
            var missed = <?php echo !empty($missed) ? json_encode($missed) : json_encode(array()); ?>;
            var answered = <?php echo !empty($answered) ? json_encode($answered) : json_encode(array()); ?>;
            var answered_bar = <?php echo !empty($new_ans) ? json_encode($new_ans) : json_encode(array()); ?>;
            var missed_bar =  <?php echo !empty($new_miss) ? json_encode($new_miss) : json_encode(array()); ?>;
            var c_data = <?php echo !empty($crm_data) ? json_encode($crm_data) : json_encode(array()); ?>;

            //console.log(c_data);

            var newans = [];
            $.each(answered_bar, function(index,value){
                newans.push(value);
            });

            var newmiss = [];
            $.each(missed_bar, function(index,value){
                newmiss.push(value);
            });

            /*alert(Math.max.apply(Math,newmiss));
            alert(Math.min.apply(Math,newmiss));
            alert(Math.max.apply(Math,newans));
            alert(Math.min.apply(Math,newans));*/
            if (Math.min.apply(Math,newans) > Math.min.apply(Math,newmiss))
            {
                var min = Math.min.apply(Math,newans);
            }
            else
            {
                var min = Math.min.apply(Math,newmiss);
            }
            if (Math.max.apply(Math,newans) > Math.max.apply(Math,newmiss))
            {
                var max = Math.max.apply(Math,newans);
            }
            else
            {
                var max = Math.max.apply(Math,newmiss);
            }
            /*alert(max);*/

            /*console.log('sds', newmiss);
            console.log('sds111', newans);*/
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

            let multiLineElem = document.getElementById('multiLine1');
            if (multiLineElem) {
                let multiLine = echarts.init(multiLineElem);
                multiLine.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    grid: {
                        top: '10%',
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: ['1:00 AM', '2:00 AM', '3:00 AM', '4:00 AM', '5:00 AM', '6:00 AM', '7:00 AM','8:00 AM','9:00 AM','10:00 AM','11:00 AM','12:00 PM','1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM', '6:00 PM', '7:00 PM','8:00 PM','9:00 PM','10:00 PM','11:00 PM','12:00 AM'],
                        boundaryGap: false,
                        axisLabel: {
                            color: '#999'
                        },
                        axisLine: {
                            color: '#999',
                            lineStyle: {
                                color: '#999999'
                            }
                        }
                    },
                    yAxis: {
                        type: 'value',
                        min: min,
                        max: max,

                        axisLine: {
                            show: false
                        },
                        axisTick: {
                            show: false
                        },
                    },
                    series: [{
                            name: 'Answered',
                            data: newans,
                            type: 'line',
                            smooth: true,
                            symbolSize: 8,
                            lineStyle: {
                                color: '#ff5721',
                                opacity: 1,
                                width: 1.5,
                            },
                            itemStyle: {
                                color: '#fff',
                                borderColor: '#ff5721',
                                borderWidth: 1.5
                            }
                        },
                        {
                            name: 'Missed',
                            data: newmiss,
                            type: 'line',
                            smooth: true,
                            symbolSize: 8,
                            lineStyle: {
                                color: '#5f6cc1',
                                opacity: 1,
                                width: 1.5,
                            },
                            itemStyle: {
                                color: '#fff',
                                borderColor: '#5f6cc1',
                                borderWidth: 1.5
                            }
                        }
                    ]
                });
                $(window).on('resize', function() {
                    setTimeout(() => {
                        multiLine.resize();
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

            //new, contacted, interested, inder review, demo, converted, unqualified
            var echartElemPie_crm = document.getElementById('crm');
            if (echartElemPie_crm) {
                var echartPie_crm = echarts.init(echartElemPie_crm);
                echartPie_crm.setOption({
                    color: ['#62549c', '#7566b5', '#7d6cbb', '#8877bd', '#9181bd', '#6957af','#9370DB'],
                    tooltip:   {
                        show: true,
                        backgroundColor: 'rgba(0, 0, 0, .8)'
                    },
                    series: [{
                        name: 'CRM Leads',
                        type: 'pie',
                        radius: '60%',
                        center: ['50%', '50%'],
                        data: c_data,
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
