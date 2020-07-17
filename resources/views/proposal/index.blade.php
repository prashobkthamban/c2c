@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/custom.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Proposal </h1> 
    </div>
            <div class="separator-breadcrumb border-top"></div>

           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" id="add_converted" href="{{route('ProposalAdd')}}" class="btn btn-primary"style="float: right;margin-bottom: 15px;"> Add Proposal </a>
                            <div class="table-responsive">
                                <table id="proposal_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Subject</th>
                                            <th>Customer Name</th>
                                            <th>Company Name</th>
                                            <th>Date</th>
                                            <th>Discount</th>
                                            <th>Total Amount</th>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                                                <th>Agent Name</th>
                                            <?php }?>
                                            <?php
                                            if (Auth::user()->usertype == 'reseller') { ?>
                                                <th>Group Name</th>
                                            <?php }?>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @if(Auth::user()->usertype == 'reseller')
                                            @foreach($list_proposals as $list_proposal)
                                                @foreach($list_proposal as $proposals)
                                                <tr>
                                                    <td>{{$proposals->id}}</td>
                                                    <td>{{$proposals->subject}}</td>
                                                    <td>{{$proposals->first_name.' '.$proposals->last_name}}</td>
                                                    <td>{{$proposals->company_name}}</td>
                                                    <td>{{$proposals->date}}</td>
                                                    <td>{{$proposals->discount}}</td>
                                                    <td>{{$proposals->total_amount}}</td>
                                                    <?php
                                                    if (Auth::user()->usertype == 'reseller') { ?>
                                                        <td>{{$proposals->accountgroup_name}}</td>
                                                    <?php }?>
                                                    <td><?php echo date('d M, Y',strtotime($proposals->inserted_date)); ?></td>
                                                    <td>
                                                        <a href="{{ route('editProposal', $proposals->id) }}" class="text-success mr-2" data-toggle="tooltip" data-placement="top" title="Proposal Edit">
                                                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                        </a>
                                                        <a href="{{ route('deleteProposal', $proposals->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="Proposal Delete">
                                                            <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                        </a>  
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        @else
                                            @foreach($list_proposals as $list_proposal)
                                            <tr>
                                                <td>{{$list_proposal->id}}</td>
                                                <td>{{$list_proposal->subject}}</td>
                                                <td>{{$list_proposal->first_name.' '.$list_proposal->last_name}}</td>
                                                <td>{{$list_proposal->company_name}}</td>
                                                <td>{{$list_proposal->date}}</td>
                                                <td>{{$list_proposal->discount}}</td>
                                                <td>{{$list_proposal->total_amount}}</td>
                                                <?php
                                                if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                                                    <td>{{$list_proposal->username}}</td>
                                                <?php }?>
                                                <td><?php echo date('d M, Y',strtotime($list_proposal->inserted_date)); ?></td>
                                                <td>
                                                    <a href="{{ route('editProposal', $list_proposal->id) }}" class="text-success mr-2" data-toggle="tooltip" data-placement="top" title="Proposal Edit">
                                                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                    </a>
                                                    <a href="{{ route('deleteProposal', $list_proposal->id) }}" onclick="return confirm('Are you sure you want to delete this Data?')" class="text-danger mr-2" data-toggle="tooltip" data-placement="top" title="Proposal Delete">
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
                                            <th>Subject</th>
                                            <th>Customer Name</th>
                                            <th>Company Name</th>
                                            <th>Date</th>
                                            <th>Discount</th>
                                            <th>Total Amount</th>
                                            <?php
                                            if (Auth::user()->usertype == 'groupadmin' || Auth::user()->usertype == 'admin') { ?>
                                                <th>Agent Name</th>
                                            <?php }?>
                                            <th>Date</th>
                                            <th>Actions</th>
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
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    $('#proposal_table').DataTable( {
	        "order": [[0, "desc" ]]
	    } );
	} );
</script>
@endsection
