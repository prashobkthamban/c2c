@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Category </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" href="{{route('addCategory')}}" class="btn btn-primary" style="float: right;margin-bottom: 15px;"> Add Category </a>
                            <div class="table-responsive">
                                <table id="customer_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Parent ID</th>
                                            <th>Child Level</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                       
                                        @foreach($categories as $category)
                                        <tr>
                                            <td>{{$category->id}}</td>
                                            <td>{{$category->name}}</td>
                                            <td>{{$category->parent_id}}</td>
                                            <td>{{$category->child_level}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Parent ID</th>
                                            <th>Child Level</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <!-- <div class="pull-right">{{ $result->links() }}</div> -->
                    </div>
                </div>
            </div>


@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#customer_table').DataTable( {
            "order": [[0, "desc" ]]
        } );
    } );
</script>
@endsection
