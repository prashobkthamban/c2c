
            <div class="row mb-4" id="div_table">
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
                                {{--    <tfoot>
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
--}}
                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->
