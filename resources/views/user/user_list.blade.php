@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1> User </h1>

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
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">SMS</label>
                                {!! Form::select('sms_support', array('' => 'All', 'Yes' => 'Yes', 'No' => 'No'), isset($requests['sms_support']) ? $requests['sms_support'] : '',array('class' => 'form-control', 'id' => 'sms_support')) !!}
                            </div>
                            <div class="col-md-4">
                                <label class="filter-col" for="pref-perpage">DT</label>
                                {!! Form::select('operator_dpt', array('' => 'All', 'Yes' => 'Yes', 'No' => 'No'), isset($requests['operator_dpt']) ? $requests['operator_dpt'] : '',array('class' => 'form-control', 'id' => 'operator_dpt')) !!}
                            </div>
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
                                <label class="filter-col" for="pref-perpage">Status</label>
                                {!! Form::select('status', array('' => 'All', 'ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'), isset($requests['status']) ? $requests['status'] : '',array('class' => 'form-control', 'id' => 'status')) !!}
                            </div>
                            <div class="col-md-6" style="margin-top: 24px;">
                                <button type="submit" id="search_btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                <button type="button" id="reload_page_btn" class="btn btn-outline-secondary" name="clear_btn">Clear</button>
                            </div>
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
                <a title="Compact Sidebar" href="{{route('addUser')}}" class="btn btn-primary"> Add User </a>
                <div class="table-responsive">
                    <table class="display table table-striped table-bordered zero-configuration-table" style="width:100%">
                        <thead>
                            <tr>
                                @if(Auth::user()->usertype == 'admin')
                                <th>ID</th>
                                @endif
                                <th>Customer name</th>
                                <th>Coperate name</th>
                                <th>Start Date</th>
                                <th>End date</th>
                                @if(Auth::user()->usertype == 'admin')
                                <th>Channels</th>
                                <th>SMS</th>
                                <th>DT</th>
                                <th>Multi-Lang</th>
                                @endif
                                <th>Did</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($users as $user)
                            <tr id="row_{{ $user->id }}">
                                @if(Auth::user()->usertype == 'admin')
                                <td>{{$user->id}}</td>
                                @endif
                                <td>{{$user->name}}</td>
                                <td>{{$user->resellername}}</td>
                                <td>{{ date('d-m-Y', strtotime($user->startdate)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($user->enddate)) }}</td>
                                @if(Auth::user()->usertype == 'admin')
                                <td>{{$user->c2c_channels}}</td>
                                <td>{{$user->sms_support}}</td>
                                <td>{{$user->operator_dpt}}</td>
                                <td>{{$user->multi_lang}}</td>
                                @endif
                                <td>{{$user->did}}</td>
                                <td>{{ date('d-m-Y', strtotime($user->created_at)) }}</td>
                                <td>{{$user->status}}</td>
                                <td>
                                    <!-- <a href="{{ route('editUserSettings', $user->id) }}" class="text-success mr-2" title="IVR User Permisions">
							                    <i class="nav-icon i-Pen-2 "></i></a> -->
                                    <a href="{{ route('editUser', $user->id) }}" class="text-success mr-2" title=" Edit User">
                                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                    </a><a href="javascript:void(0)" onClick="deleteItem({{$user->id}}, 'accountgroup')" class="text-danger mr-2">
                                        <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                @if(Auth::user()->usertype == 'admin')
                                <th>ID</th>
                                @endif
                                <th>Customer name</th>
                                <th>Coperate name</th>
                                <th>Start Date</th>
                                <th>End date</th>
                                @if(Auth::user()->usertype == 'admin')
                                <th>Channels</th>
                                <th>SMS</th>
                                <th>DT</th>
                                <th>Multi-Lang</th>
                                @endif
                                <th>Did</th>
                                <th>created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="customizer" title="Search" style="top:75px">
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
<script>

$("#customer_id").on("change", function() {
    if ($("#customer_id").val() == "") {
        resetData();
    } else {
        fetchDidNumbers();
    }
})

function resetData() {
    $("#did_no").find('option').not(':first').remove();
    $("#did_no").val("");
}

function fetchDidNumbers() {
    var data = {'groupId': $("#customer_id").val()};
    var url = "{{ url('fetch_did_numbers') }}";
    ajaxCall(url, data)
    .then(function(result) {
        if(result.status) {
            var html = '<option value="">All</option>';
            result.data.forEach(function(data) {
                html += '<option value="'+data.did_no+'" >'+data.did_no+'</option>';
            });
            $("#did_no").html(html);
        } else {
            toastr.error(result.message);
        }
    });
}
</script>

@endsection