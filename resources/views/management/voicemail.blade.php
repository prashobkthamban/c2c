@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1> VoiceMails </h1>
</div>
<div class="separator-breadcrumb border-top"></div>


<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <h5 class="ml-3">Search Panel</h5></br>
                <div class="row" style="margin-right: 24px;margin-left: 24px;">
                    <div class="col-md-12">
                        <form id="voice_mail_form" method="GET" autocomplete="off">
                            <div class="row">
                                @if(Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'reseller')
                                <div class="col-md-4" id="customer_div">
                                    <label class="filter-col" for="pref-perpage">Customers</label>
                                    <select name="customer" class="form-control" id="customer_id">
                                        <option value="">All</option>
                                        @if(!empty($customers))
                                        @foreach($customers as $customer )
                                        <option value="{{$customer->id}}" @if($customer->id == $groupId) selected @endif>{{$customer->name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @else
                                    <input type="hidden" name="customer" id="customer_id" value="{{Auth::user()->groupid}}" />
                                @endif
                                <div class="col-md-4">
                                    <label class="filter-col" for="pref-perpage">Departments</label>
                                    <select name="department" class="form-control">
                                        <option value="">All</option>
                                        <option value="afteroffice" <?= ($department == 'afteroffice') ? 'selected' : ''; ?>>After Office</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-col" for="pref-search">By Caller Number</label>
                                    <input type="text" value="<?= $call_no; ?>" class="form-control input-sm" name="caller_number">
                                </div>
                                <div class="col-md-4">
                                    <label class="filter-col" for="pref-perpage">Date</label>
                                    <select class="form-control" name="date" id="date_select">
                                        <option value="">All</option>
                                        <option value="today" <?= ($date == 'today') ? 'selected' : ''; ?>>Today</option>
                                        <option value="yesterday" <?= ($date == 'yesterday') ? 'selected' : ''; ?>>Yesterday</option>
                                        <option value="week" <?= ($date == 'week') ? 'selected' : ''; ?>>1 Week</option>
                                        <option value="month" <?= ($date == 'month') ? 'selected' : ''; ?>>1 Month</option>
                                        <option value="custom" <?= ($date == 'custom') ? 'selected' : ''; ?>>Custom</option>
                                    </select>
                                </div>
                                <div class="col-md-4 custom_date_div <?= ($date !== 'custom') ? 'd-none' : ''; ?>">
                                    <label class="filter-col" for="pref-search">Date From</label>
                                    <input type="text" class="form-control input-sm datepicker" name="date_from" value="<?= (isset($_GET['date_from'])) ? $_GET['date_from'] : ''; ?>">
                                </div>
                                <div class="col-md-4 custom_date_div <?= ($date !== 'custom') ? 'd-none' : ''; ?>">
                                    <label class="filter-col" for="pref-search">Date To</label>
                                    <input type="text" class="form-control input-sm datepicker" name="date_to" value="<?= (isset($_GET['date_to'])) ? $_GET['date_to'] : ''; ?>">
                                </div>
                                <div class="col-md-4 custom_date_div <?= ($date !== 'custom') ? 'd-none' : ''; ?>" style="display:none">
                                </div>
                                <div class="col-md-6" style="margin-top: 24px;">
                                    <button id="btn" class="btn btn-outline-danger" name="btn" style="margin-right: 15px;">Search</button>
                                    <a href="{{url('voicemail')}}" class="btn btn-outline-secondary" name="btn">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br><br>
                <div class="table-responsive">
                    <table id="voicemail_table" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Dnid</th>
                                <th>Caller</th>
                                <th>Department</th>
                                <th>Duration</th>
                                <th>Datetime</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($voicemails as $voicemail)
                            <tr>
                                <td>{{$voicemail->name}}</td>
                                <td>{{$voicemail->dnid}}</td>
                                <td>{{$voicemail->callerid}}</td>
                                <td>{{$voicemail->departmentname}}</td>
                                <td>{{$voicemail->duration}}</td>
                                <td>{{$voicemail->datetime}}</td>
                                <td>
                                    <a href="{{ url('download_file/' . $voicemail->filename)}}" class="btn bg-gray-100">
                                        <i class="nav-icon i-Download1 font-weight-bold"></i>
                                    </a>
                                    <a href="#" class="btn bg-gray-100 play_audio" data-toggle="modal" data-target="#play_modal" data-file="{{ url('download_file/' . $voicemail->filename)}}">
                                        <i class="i-Play-Music" style="color:#0000c9"></i>
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Customer</th>
                                <th>Dnid</th>
                                <th>Caller</th>
                                <th>Department</th>
                                <th>Duration</th>
                                <th>Datetime</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>

                    </table>
                    <div class="pull-right">{{ $voicemails->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- play modal -->
<div class="modal fade" id="play_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <audio controls id="audio_section">
                    <source src="" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#voicemail_table').DataTable({
            "order": [
                [5, "desc"]
            ],
            "bLengthChange": false
        });
        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy'
        });
        $('.play_audio').on('click', function(e) {
            var aud = document.getElementById("audio_section");
            var file = $(this).attr("data-file");
            aud.src = file;
            aud.load();
        });

        $(document).on("change", "#date_select", function() {
            var date_val = $(this).val();
            if (date_val == 'custom') {
                $(".custom_date_div").removeClass('d-none');
            }
        });
    });
</script>

@endsection