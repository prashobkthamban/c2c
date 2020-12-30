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
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_general_file" class="btn btn-primary"> Upload General Files</a>
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
                                            <td><a href="{{ route('deleteFile', [$listOne->id, $listOne->filename]) }}" onclick="return confirm('Are you sure want to delete this record ?')" class="text-danger mr-2">
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

             <!-- add general file modal -->
            <div class="modal fade" id="add_general_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add General File</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'general_file_form', 'method' => 'post', 'files' => true]) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('file_lang', null, ['class' => 'form-control', 'id' => 'file_lang']) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Voicefile Type</label> 
                                         {!! Form::select('file_type', ['' => 'Select Voicefile type', 'mainmenupress0' => 'For Main Menu Press Zero', 'thank4caling' => 'Thank You For Calling', 'repeatoptions' => 'Repeatoptions', 'previousmenu' => 'Previousmenu', 'voicemailmsg' => 'Voicemail msg', 'trasfringcall' => 'Trasfringcall', 'contactusoon' => 'Contactusoon', 'talktooperator9' => 'Talktooperator press 9','noinput' => 'No input', 'wronginput' => 'Wrong input', 'nonworkinghours' => 'Nonworking hours', 'languagesection' => 'Language section', 'transferingtodifferentagent' => 'Transfering to different agent', 'holiday' => 'Holiday', 'aombefore' => 'AOM Before Welcome', 'aomafter' => 'AOM After Welcome'], null,array('class' => 'form-control', 'id' => 'file_type')) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Filename without space</label> 
                                        {!! Form::text('filename', null, ['class' => 'form-control', 'id' => 'filename']) !!}
                                    </div>
                                </div>  
                                @foreach($languages as $lang)
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">File to play in {{$lang->Language}} *</label>
                                        <span id="lang_id_{{$lang->id}}"></span> 
                                        {!! Form::file($lang->shortcode, null, ['class' => 'form-control file_play', 'id' => '$lang->id', 'enctype' => 'multipart/form-data', 'multiple' => true]) !!}
                                    </div>
                                </div> 
                                @endforeach 
                                                   
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                         {!! Form::close() !!}
                    </div>
                </div>

            </div>


@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
 <script type="text/javascript">
     $(document).ready(function() {
        var langs = [];
        $("input:file").change(function (){
            var fileLang = $(this).attr("name");
            langs.push(fileLang);
            $("#file_lang").val(langs);
        });

        $( '.general_file_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addGeneralFile") }}', // This is the url we gave in the route
            data: new FormData(this),
            dataType:'JSON',
            contentType: false,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false, 
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    $("#add_general_file").modal('hide');
                    setTimeout(function(){ location.reload() }, 3000);
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

     });
 </script>
@endsection

