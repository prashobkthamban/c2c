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
});
</script>

@endsection

