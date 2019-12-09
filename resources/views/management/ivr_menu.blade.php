@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Ivr Menu Manager </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#ivr_modal" id="add_ivr" class="btn btn-primary"> Add Ivr Menu </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>CoperateAcc</th>
                                            <th>IVR Level Name</th>
                                            <th>IVR Options</th>
                                            <th>OperatorDept.</th>
                                            <th>Add Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                        @foreach($customers as $listOne)
                                        <tr>
                                            <td>{{$listOne->name}}</td>
                                            <td>{{$listOne->resellername}}</td>
                                            <td>{{$listOne->ivr_level_name}}</td>
                                            <td>{{$listOne->ivr_level}}</td>
                                            <td>{{$listOne->operator_dept}}</td>
                                            <td>{{$listOne->adddate}}</td>
                                           
                                            <td>
                                                <a href="#" class="text-success mr-2 edit_ivr" data-toggle="modal" data-target="#ivr_modal" id="{{$listOne->id}}">
                                                <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('deleteIvr', $listOne->id) }}" onclick="return confirm('Are you sure want to delete this record?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a></td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Customer</th>
                                            <th>CoperateAcc</th>
                                            <th>IVR Level Name</th>
                                            <th>IVR Options</th>
                                            <th>OperatorDept.</th>
                                            <th>Add Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                {{ $customers->links() }}
                            </div>

                        </div>
                    </div>
                </div>
           </div>

            <!-- add ivr menu modal -->
            <div class="modal fade" id="ivr_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle-2">Add Customer Ivr Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         {!! Form::open(['class' => 'ivr_menu_form', 'method' => 'post', 'files' => true]) !!} 
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                        {!! Form::hidden('file_lang', null, ['class' => 'form-control', 'id' => 'file_lang']) !!}
                                        {!! Form::hidden('file_shortcode', null, ['id' => 'file_shortcode']) !!}
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Coperate Account</label> 
                                         {!! Form::select('resellerid', getResellers()->prepend('Select coperate', ''), null,array('class' => 'form-control', 'id' => 'resellerid')) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">Customer *</label> 
                                         {!! Form::select('groupid', getAccountgroups()->prepend('Select Customer', ''), null,array('class' => 'form-control', 'id' => 'groupid')) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">IVR Level Name *</label> 
                                        {!! Form::text('ivr_level_name', null, ['class' => 'form-control', 'id' => 'ivr_level_name']) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">IVR Level *</label> 
                                        {!! Form::text('ivr_level', null, ['class' => 'form-control', 'id' => 'ivr_level']) !!}
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">IVR Options *</label> 
                                        {!! Form::text('ivroption', null, ['class' => 'form-control', 'id' => 'ivroption']) !!}
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                       <label for="firstName1">Operator department *</label> 
                                        <label class="radio-inline"> {{ Form::radio('operator_dept', 'Yes', '', array('id' => 'operator_yes')) }} Yes</label>
                                            <label class="radio-inline">{{ Form::radio('operator_dept', 'No', true, array('id' => 'operator_no')) }} No</label>   
                                    </div>
                                </div>
                                @foreach($languages as $lang)
                                <div class="row">
                                    <div class="col-md-2 form-group mb-3"> 
                                    </div>

                                    <div class="col-md-8 form-group mb-3">
                                        <label for="firstName1">File to play in {{$lang->Language}} *</label>
                                        <span id="lang_id_{{$lang->id}}" data-short-code="{{$lang->shortcode}}"></span> 
                                        {!! Form::file($lang->id, null, ['accept' => '.gsm', 'enctype' => 'multipart/form-data', 'multiple' => true]) !!}
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
            var ext = $('input:file').val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['gsm', 'wav']) == -1) {
                $("input:file").val('');
                return false;
            } else {
                var fileLang = $(this).attr("name");
                var shortcode = $("#lang_id_"+fileLang).attr("data-short-code");
                langs.push(fileLang+'_'+shortcode);
                $("#file_lang").val(langs);
                $("#file_shortcode").val(shortcode);
            }
        });

        $( '.ivr_menu_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("addIvrmenu") }}', // This is the url we gave in the route
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
                    $("#ivr_modal").modal('hide');
                    setTimeout(function(){ location.reload() }, 3000);
                    toastr.success(res.success);                
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });

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

        $('.edit_ivr').on('click',function(e)
        {
            $("#exampleModalCenterTitle-2").text('Edit Customer Ivr Menu');
            var id = $(this).attr("id");
            $.ajax({
            type: "GET",
            url: '/get_ivr_menu/'+ id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                var result = res[0]
                $("#resellerid").val(result.resellerid);
                $("#groupid").val(result.groupid);
                $("#ivr_level_name").val(result.ivr_level_name);
                $("#ivr_level").val(result.ivr_level);
                $("#ivroption").val(result.ivroption);
                if(result.operator_dept == 'Yes') {
                    $("#operator_yes").prop("checked", true);
                } else {
                    $("#operator_no").prop("checked", true);
                } 
    
                for(var i = 0; i < res.length;i++) {
                    var lang_id = res[i].lang_id.split('_');
                    $("#lang_id_" + lang_id[0]).text(res[i].orginalfilename);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
          });
        });

        $('#add_ivr').click(function() {
            $(".ivr_menu_form").trigger("reset");
        });
     });
 </script>

@endsection




