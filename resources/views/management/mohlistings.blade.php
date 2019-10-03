@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> MOH Manager </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#add_moh" class="btn btn-primary"> MOH Files</a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>MOH Name</th>
                                            <th>Number of files</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                       
                                        @foreach($moh as $listOne)
                                        <tr>
                                            <td>{{$listOne->classname}}</td>
                                            <?php $dir = config('constants.moh_file').'/'.$listOne->classname; 
                                            $i = 0; 
                                            if (file_exists($dir)) {
                                                if ($handle = opendir($dir)) {
                                                    while (($file = readdir($handle)) !== false){
                                                        if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
                                                            $i++;
                                                    }
                                                } 
                                            }?>
                                            <td>{{$i}}</td>
                                            <td><a href="#" data-toggle="modal" data-target="#add_moh" class="text-success mr-2 edit_moh" id="{{$listOne->id}}">
                                                    <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('deleteMoh', [$listOne->id, $listOne->classname]) }}" onclick="return confirm('Are you sure want to delete this record ?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>MOH Name</th>
                                            <th>Number of files</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>

                                </table>
                                {{ $moh->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
             <!-- add operator modal -->
            <div class="modal fade" id="add_moh" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add MOH File</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {!! Form::open(['class' => 'add_moh_form', 'method' => 'post', 'files' => true]) !!}  
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('id', '', array('id' =>'moh_id')) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">MOH Classwithout space</label> 
                                         {!! Form::text('classname', null, ['class' => 'form-control', 'id' => 'classname']) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Multiple Files (File should be asterisk supported GSM or WAV)</label> 
                                        {!! Form::file('moh_file[]', array('class' => 'form-control', 'id' => 'moh_file_1', 'enctype' => 'multipart/form-data', 'multiple' => true)) !!} 
                                    </div>
                                    <i class="nav-icon i-Add font-weight-bold" id="add_image"></i> 
                                </div>
                                <div id="multiple_images">
                                </div>

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

        $( '.add_moh_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '/add_moh/', // This is the url we gave in the route
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
                    $("#add_moh").modal('hide');
                    toastr.success(res.success); 
                    setTimeout(function(){ location.reload() }, 3000);               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

        $('.edit_moh').on('click',function(e)
        {
            var id = $(this).attr("id");
            console.log(id);
            $.ajax({
            type: "GET",
            url: '/get_moh/'+ id, // This is the url we gave in the route
            success: function(result){ // What to do if we succeed
                console.log(result)
                var res = result[0];
                $("#moh_id").val(res.id);
                $("#classname").val(res.classname);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

     });
 </script>
@endsection

