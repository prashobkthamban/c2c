@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/custom.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> SMS Template </h1> 
    </div>
            <div class="separator-breadcrumb border-top"></div>

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" id="add_converted" href="{{route('SMSTemplateAdd')}}" class="btn btn-primary" style="float: right;margin-bottom: 15px;"> Add SMS Template</a>
                            <div class="table-responsive">
                                <table id="sms_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Body</th>
                                            <?php
                                            if (Auth::user()->usertype == 'reseller') 
                                            {
                                                echo "<th>Group Name</th>";
                                            }
                                            ?>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @if(Auth::user()->usertype == 'reseller')
                                            @foreach($list_smstemplates as $list_smstemplate)
                                                @foreach($list_smstemplate as $smstemplate)
                                                    <tr>
                                                        <td>{{$smstemplate->id}}</td>
                                                        <td>{{$smstemplate->name}}</td>
                                                        <td>{{$smstemplate->body}}</td>
                                                        <?php
                                                            if (Auth::user()->usertype == 'reseller') 
                                                            {
                                                                echo '<td>'.$smstemplate->accountgroup_name.'</td>';
                                                            }
                                                            ?>
                                                        <td>
                                                            <a href="{{ route('SMSTemplateEdit', $smstemplate->id) }}" class="text-success mr-2" data-toggle="tooltip" data-placement="top" title="SMS Edit">
                                                                <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                            </a>
                                                            <a href="{{ route('SMSTemplateDelete', $smstemplate->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="SMS Delete">
                                                                <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                            </a>  
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            @foreach($list_smstemplates as $list_smstemplate)
                                            <tr>
                                                <td>{{$list_smstemplate->id}}</td>
                                                <td>{{$list_smstemplate->name}}</td>
                                                <td>{{$list_smstemplate->body}}</td>
                                                <td>
                                                    <a href="{{ route('SMSTemplateEdit', $list_smstemplate->id) }}" class="text-success mr-2" data-toggle="tooltip" data-placement="top" title="SMS Edit">
                                                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                    </a>
                                                    <a href="{{ route('SMSTemplateDelete', $list_smstemplate->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="SMS Delete">
                                                        <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                    </a>  
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Body</th>
                                            <?php
                                            if (Auth::user()->usertype == 'reseller') 
                                            {
                                                echo "<th>Group Name</th>";
                                            }
                                            ?>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
            </div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sms_table').DataTable( {
            "order": [[0, "desc" ]]
        } );
    } );
</script>
@endsection
