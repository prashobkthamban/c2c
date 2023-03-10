@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
        <h1>Cdr Tag Manager </h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <a title="Compact Sidebar" href="#" data-toggle="modal" data-target="#tag_modal" class="btn btn-primary"> Add CdrTag </a>
                    <div class="table-responsive">
                        <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Tag Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row )
                            <tr>
                                <td>{{ $row->tag }}</td>
                                <td><a href="{{ route('deleteRecord', [$row->id, 'cdr_tags']) }}" onclick="return confirm('Are you sure want to delete this tag ?')" class="text-danger mr-2">
                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                    </a></td>
                            </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Tag Name</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end of row -->
    <!-- tag modal -->
    <div class="modal fade" id="tag_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tag_title">Add Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['class' => 'cdrtag_form', 'method' => 'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label for="firstName1">Tag</label> 
                                <input type="text" name="tag" class="form-control" placeholder="New Tag" />  
                        </div>
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
    <!--end of tag modal -->
@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $( '.cdrtag_form' ).on( 'submit', function(e) {
            e.preventDefault();
            var errors = ''; 
              $.ajax({
                type: "POST",
                url: '{{ URL::route("tagStore") }}',
                data: $('.cdrtag_form').serialize(),
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
                        $("#tag_modal").modal('hide');
                        toastr.success(res.success); 
                        setTimeout(function(){ location.reload() }, 300);               
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
