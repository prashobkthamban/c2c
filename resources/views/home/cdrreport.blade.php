@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>CDR Report </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>

              <div class="row">
                  <div class="col-lg-6 col-md-12">
                      <div class="card mb-2">
                          <div class="card-body">
                              <div class="row row-xs">

                                  <div class="col-md-2 mt-3 mt-md-0">
                                      <button class="btn btn-primary btn-block">Filter</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Caller</th>
                                        <th>DNID</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr data-toggle="collapse" data-target="#accordion_{{$row->cdrid}}" class="clickable">
                                        <td>
                                            @if(Auth::user()->usertype=='groupadmin')
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                                @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                                {{ $row->fname ? $row->fname : $row->number }}
                                                @else
                                                <a href="?" data-toggle="modal" data-target="#formDiv" title="{{ $row->fname ? $row->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->fname ? $row->fname : $row->number }}</a>
                                            @endif

                                        </td>
                                        <td>{{$row->did_no}}</td>
                                        <td>{{$row->datetime}}</td>
                                        <td>{{$row->firstleg .'('. $row->secondleg.')'}}</td>
                                        <td><a>{{$row->status}}</a></td>
                                        <td>{{$row->creditused}}</td>
                                        <td>{{$row->deptname}}</td>
                                        <td>{{$row->opername}}</td>

                                    </tr>
                                    <tr id="accordion_{{$row->cdrid}}" class="collapse">
                                        <td colspan="7">
                                            <div >
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.form',{{$row->number}})">Form</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.tag')">Tag</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.reminder')">Add Reminder</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.assign')">Assign</button>
                                                <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.contact')">Add Contact</button>
                                                <button type="button" class="btn btn-info m-1">Play</button>
                                                <button type="button" class="btn btn-info m-1">Download</button>

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info">More</button>
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Call Duration : {{$row->firstleg}}({{$row->secondleg}})</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Coin : {{$row->creditused}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Assign To : {{$row->assignedname}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Reminder : {{$row->creditused}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Tag : {{$row->tag}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-outline-info m-1">Form Owner : {{$row->opername}}</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="#" class="btn btn-info m-1" data-toggle="modal" data-target="#ModalContent" onclick="loadForm({{$row->cdrid}},'cdr.callhistory')">For Call History</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Caller</th>
                                        <th>DNID</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Coin</th>
                                        <th>Department</th>
                                        <th>Operator</th>
                                        <th></th>
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

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
 <script src="{{asset('assets/js/datatables.script.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>
 <script src="{{asset('assets/js/vendor/pickadate/picker.time.js')}}"></script>
 <script type="text/javascript">



 </script>


@endsection
