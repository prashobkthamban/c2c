@extends('layouts.master')
@section('page-css')
<meta http-equiv="refresh" content="30">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Live Calls</h1>

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
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>Customer</th>
                                        @endif
                                        <th>Callerid</th>
                                        <th>Call time</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Call status</th>
                                        <th>Dial Statergy</th>
                                        <th>Priority</th>
                                        <th>Duration</th>
                                        @if(Auth::user()->usertype == 'groupadmin')
                                        <th>Listen</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                        <?php $contactName = getConatctName($row->callerid);
                                        date_default_timezone_set('Asia/Kolkata');
                                        $datetime1 = new DateTime();
                                        $datetime2 = new DateTime($row->status_change_time);
                                        $interval = $datetime1->diff($datetime2);
                                        $fname = count($contactName) == null ? $row->callerid :  contactName[0]->fname; 
                                        ?>
                                    <tr>
                                        @if(Auth::user()->usertype == 'admin')
                                        <td>{{ $row->name }}</td>
                                        @endif
                                        <td>{{ $fname }}</td>
                                        <td>{{ $row->time }}</td>
                                        <td>{{ $row->dept_name }}</td>
                                        <td>{{ $row->opername }}</td>
                                        <td>{{ $row->call_status }}</td>
                                        <td>{{ $row->dial_statergy }}</td>
                                        <td>{{ $row->priority }}</td>
                                        <td>{{ $interval->format('%H:%i:%s') }}</td>
                                        @if(Auth::user()->usertype == 'groupadmin')
                                        <td><i class="i-Headphone" data-toggle="modal" data-target="#listen_modal"></i></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        @if(Auth::user()->usertype == 'admin')
                                        <th>Customer</th>
                                        @endif
                                        <th>Callerid</th>
                                        <th>Call time</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th>Call status</th>
                                        <th>Dial Statergy</th>
                                        <th>Priority</th>
                                        <th>Duration</th>
                                        @if(Auth::user()->usertype == 'groupadmin')
                                        <td>Listen</td>
                                        @endif
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

        <!-- listen modal -->
        <div class="modal fade" id="listen_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle-2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Listen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Form::open(['method' => 'post', 'id' => 'listen_form']) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <label for="number">Listen from Number</label>
                                <input type="number" id="customer_number" onpaste="return false;" class="form-control" placeholder="Customer Number" name="number">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group mb-3">
                            </div>

                            <div class="col-md-8 form-group mb-3">
                                <!-- <label for="firstName1">Option</label> -->
                                <label class="radio-inline"> {{ Form::radio('option', 'Sw') }} Whisper Mode</label>
                                <label class="radio-inline">{{ Form::radio('option', 'bs', true) }} Listen</label>
                                <label class="radio-inline">{{ Form::radio('option', 'BS') }} BargeIn</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="dial_submit" class="btn btn-primary">Dialing</button>
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- end of listen modal -->


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
@endsection
