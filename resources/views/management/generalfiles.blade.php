@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Voicefile Manager </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_operator" class="btn btn-primary"> Upload General Files</a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>File Type</th>
                                            <th>File Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                        @foreach($voicefiles as $listOne)
                                        <tr>
                                            <td>{{$listOne->file_type}}</td>
                                            <td>{{$listOne->filename}}</td>
                                            <td><a href="{{ route('deleteFile', $listOne->id) }}" onclick="return confirm('Are you sure want to delete this record ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>File Type</th>
                                            <th>File Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $voicefiles->links() }}
                            </div>

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
        $('#resellerid').on('change',function(e)
            {
                var resellerid = $(this).val();
                $.ajax({
                type: "GET",
                url: '/get_customer/admin/'+ resellerid, // This is the url we gave in the route
                success: function(res){ // What to do if we succeed
     
                  $('#groupid').find('option').not(':first').remove();
                    $.each(res, function (i, item) {
                        $('#groupid').append($('<option>', { 
                            value: i,
                            text : item 
                        }));
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                }
              });
            });

        $('#groupid').on('change',function(e)
                {
                    var groupid = $(this).val();
                    $.ajax({
                    type: "GET",
                    url: '/get_ivr/'+ groupid, // This is the url we gave in the route
                    success: function(res){ // What to do if we succeed
                        console.log(res)
                      $('#ivr_level').find('option').not(':first').remove();
                        $.each(res, function (i, item) {
                            $('#ivr_level').append($('<option>', { 
                                value: i,
                                text : item 
                            }));
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    }
                  });
                });

     });
 </script>
@endsection

