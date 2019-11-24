@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Account Call Summary </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

    @if(session()->has('success'))  
      <div class="col-12 alert alert-success text-center mt-1" id="alert_message">{{ session()->get('success') }}</div>   
    @endif


            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">

                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Customer</th>
                                        <th>Calls</th>
                                        <th>Total</th>
                                        <th>Outgoing</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                    
                                        <td>{{ $row->id }}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->calls + 1}}</td>
                                        <td>{{$row->total + ceil($row->total/60)}}</td>
                                        <td>{{$row->outgoing + ceil($row->outgoing/60)}}</td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Customer</th>
                                        <th>Calls</th>
                                        <th>Total</th>
                                        <th>Outgoing</th>
                                    </tr>

                                    </tfoot>

                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->



@endsection

@section('page-js')

 <script type="text/javascript">
     setTimeout(function(){ $(".alert-success").hide(); }, 2500);
 </script>   

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

@endsection