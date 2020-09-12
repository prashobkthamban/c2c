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
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-perpage">Departments</label>
                                    <select name="department" class="form-control">
                                        <option value="">All</option>
                                        <option value="afteroffice" <?= ($department == 'afteroffice') ? 'selected' : ''; ?>>After Office</option>
                                    </select>                                
                                </div> 
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-search">By Caller Number</label>
                                    <input type="text" value="<?= $call_no; ?>" class="form-control input-sm" name="caller_number">
                                </div>  
                                <div class="col-md-4">
                                    <label class="filter-col"  for="pref-perpage">Date</label>
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
                                    <label class="filter-col"  for="pref-search">Date From</label>
                                    <input type="text" name="date_from" value="<?= (isset($_GET['date_from'])) ? $_GET['date_from'] : ''; ?>" class="form-control input-sm datepicker" >
                                </div>
                                <div class="col-md-4 custom_date_div <?= ($date !== 'custom') ? 'd-none' : ''; ?>">
                                    <label class="filter-col"  for="pref-search">Date To</label>
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
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
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
                                        <a href="{{ url('download_file/' .$voicemail->filename.'/'.$voicemail->groupid) }}" class="btn bg-gray-100">
                                        <i class="nav-icon i-Download1 font-weight-bold"></i></a>
                                        <a href="#" class="btn bg-gray-100 play_audio" data-toggle="modal" data-target="#play_modal" data-file="{{$voicemail->filename}}" id="play_{{$voicemail->groupid}}"><i class="nav-icon i-Play-Music font-weight-bold"></i></a></td>

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
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- play modal -->
        <div class="modal fade" id="play_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <audio controls>
                          <source src="https://interactive-examples.mdn.mozilla.net/media/examples/t-rex-roar.mp3" type="audio/mpeg">
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
    $('.play_audio').on('click',function(e)
    {
        var id = $(this).attr("id");
        var groupid = id.replace("play_", "");
        console.log(groupid);
        var file = $(this).attr("data-file");
        console.log(file);
        
    });

    $(document).on("change","#date_select",function(){
        var date_val = $(this).val();
        //$(".custom_date_div").addClass('d-none');
        if(date_val == 'custom')
        {
            $(".custom_date_div").removeClass('d-none');
            $('.datepicker').pickadate({format: 'yyyy-mm-dd'});
        }
    });
});
</script>

@endsection

