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
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Archived CDR Report </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

            @if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator' || Auth::user()->usertype == 'admin')
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card mb-2">
            <div class="card-body">
                <div id="filter-panel" class="filter-panel collapse {{count($requests) > 0 ? 'show' : ''}}">
                    <h5 class="ml-3">Search Panel</h5></br>
                    <form class="form" role="form" id="cdr_filter_form">
                        <div class="row" style="margin-right: 24px;margin-left: 24px;">
                            @if(Auth::user()->usertype == 'admin')
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Customers</label>
                                <select name="customer" class="form-control">
                                    <option value="">All</option>
                                    @if(!empty($customers))
                                    @foreach($customers as $customer )
                                    <option value="{{$customer->id}}" @if(isset($requests['customer']) && $customer->id == $requests['customer']) selected @endif>{{$customer->name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            @if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator')
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Departments</label>
                                <select name="department" class="form-control">
                                    <option value="">All</option>
                                    @if(!empty($departments))
                                    @foreach($departments as $dept )
                                    <option value="{{$dept->dept_name}}" @if(isset($requests['department']) && $dept->dept_name == $requests['department']) selected @endif>{{$dept->dept_name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            @if( Auth::user()->usertype == 'groupadmin' )
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Operators</label>
                                <select name="operator" class="form-control">
                                    <option value="">All</option>
                                    @if(!empty($operators))
                                    @foreach($operators as $opr )
                                    <option value="{{$opr->id}}" @if(isset($requests['operator']) && $opr->id == $requests['operator']) selected @endif>{{$opr->opername}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Cdr Tag</label>
                                {!! Form::select('tag', $tags->prepend('Select Tag', ''), isset($requests['tag']) ? $requests['tag'] : '' ,array('class' => 'form-control', 'id' => 'tag')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Status</label>
                                {!! Form::select('status', array('' => 'All', 'MISSED' => 'Missed', 'ANSWERED' => 'Answered', 'DIALING' => 'Dialing', 'LIVECALL' => 'Live call', 'AFTEROFFICE' => 'After Office'), isset($requests['status']) ? $requests['status'] : '',array('class' => 'form-control')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Assigned To</label>
                                <select class="form-control" name="assigned_to">
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
                            @if(Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'operator')
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">Dnid Name</label>
                                <select class="form-control" name="did_no">
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
                                <input type="text" class="form-control input-sm" name="caller_number" value="@if(isset($requests['caller_number'])) {{$requests['caller_number']}} @endif">
                            </div>
                            @endif
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
                                <label for="billingdate">Start Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" value="@if(isset($requests['start_date'])) {{$requests['start_date']}} @endif" name="start_date" id="start_date">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary"  type="button">
                                            <i class="icon-regular i-Calendar-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 custom_date_div" @if((isset($requests['date']) && $requests['date'] != 'custom') || !isset($requests['date'])) style="display: none;" @endif>
                                <label for="billingdate">End Date</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" value="@if(isset($requests['end_date'])) {{$requests['end_date']}} @endif" name="end_date" id="end_date">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary"  type="button">
                                            <i class="icon-regular i-Calendar-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 24px;">
                                <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                <a href="{{url('cdrreport')}}" class="btn btn-outline-secondary" name="btn">Clear</a>
                            </div>
                    </form>
                </div>
                <div class="col-md-2 mt-3 mt-md-0">
                </div>
                <!-- <a class="btn btn-secondary m-1" id="btn_download" href="{{ url('cdrexport') }}">Download</a>  -->
                <div class="btn-group" id="assign" name="assign">
                    <!-- <a href="#" class="btn btn-primary m-1 dropdown-toggle" data-toggle="dropdown"><i class="i-Add-User"> </i></a> -->
                    <!-- <ul class="dropdown-menu" role="menu">
                            @foreach($operators as $operator)
                            @if( $account_service['smsservice_assign_cdr'] =='Yes' ||  $account_service['emailservice_assign_cdr'] =='Yes')
                            <li>
                                <a href="javascript:assignoper({{$operator->id}},{{$operator->opername}});">{{$operator->opername}}</a><ul>
                            @else
                            <li>
                                <a href="javascript:assignoper({{$operator->id}},{{$operator->opername}});">{{$operator->opername}}</a>
                            @endif
                            @if($account_service['smsservice_assign_cdr'] =='Yes')
                            <li>
                                <a href="javascript:assignoper({{$operator->id}},'{{$operator->opername}}','S');">Notify By SMS</a>
                            </li>
                            @endif
                            @if($account_service['emailservice_assign_cdr'] =='Yes')
                            <li>
                                <a href="javascript:assignoper({{$operator->id}},{{$operator->opername}},'E');">Notify By Email</a>
                            </li>
                            @endif
                            @if( $account_service['smsservice_assign_cdr'] =='Yes' ||  $account_service['emailservice_assign_cdr'] =='Yes')
                            </ul>
                            @else
                            </li>
                            @endif
                            @endforeach
                            <?php echo '<li><a href="javascript:assignoper(0);">Unassign</a></li>'; ?>
                        </ul>  -->
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endif

            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Caller</th>
                                        <th>Date</th>
                                        <th>Didname</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Assignedto</th>
                                        <th>Rec</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->fname ? $row->fname : $row->number }}
                                                @else
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->datetime}}</td>
                                        <td>{{$row->did_no}}</td>
                                        <td>{{$row->firstleg .'('. $row->secondleg.')'}}</td>
                                        <td><a>{{$row->status}}</a></td>
                                        <td>{{$row->creditused}}</td>
                                        <td>{{$row->deptname}}</td>
                                        <td>{{$row->opername}}</td>
                                        <td>{{$row->assignedto}}</td>
                                        <td></td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Caller</th>
                                        <th>Date</th>
                                        <th>Didname</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Assignedto</th>
                                        <th>Rec</th>
                                    </tr>

                                    </tfoot>

                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
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

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
$(document).on("change", "#date_select", function() {
        var date_val = $(this).val();
        $(".custom_date_div").hide();
        if (date_val == 'custom') {
            $(".custom_date_div").show();
        }
    });
</script>

@endsection
