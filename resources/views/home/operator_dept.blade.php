@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Operator Department </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

             <div class="row mb-4">

                <div class="col-md-6 mb-3">
                    <div class="card text-left">

                        <div class="card-body">
                            <h4 class="card-title mb-3">Operator Groups</h4>
                           
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Group Name</th>
                                            <th scope="col">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($result))
                                            @foreach($result as $row )
                                            <tr>
                                                <th scope="row">{{$row->dept_name }}</th>
                                                <td><i class="fa fa-users"></i></td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- end of col-->

                <div class="col-md-6 mb-3">
                    <div class="card text-left">

                    </div>
                </div>
                <!-- end of col-->

            </div>
            <!-- end of row-->

@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
 <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    


@endsection
