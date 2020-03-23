
            <div class="row mb-4"  id="div_table">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Dnid</th>
                                        <th>Caller</th>
                                        <th>Department</th>
                                        <th>Duration</th>
                                        <th>Date</th>
                                        <th>Actions</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                           {{ $row->name }}

                                        </td>
                                        <td>{{$row->dnid}}</td>
                                        <td>{{$row->callerid}}</td>
                                        <td>{{$row->departmentname}}</td>
                                        <td><a>{{$row->duration}}</a></td>
                                        <td>{{$row->datetime}}</td>
                                        <td>
                                            <a href="{{asset('voicefiles/'.$row->filename)}}" class="nav-icon i-Download" title="Download"></a>
                                            <a href="{{asset('voicefiles/'.$row->filename)}}" class="nav-icon i-Download" title="Play"></a>
                                        </td>

                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                    

                                </table>
                            </div>

                        </div>
                        <div class="pull-right">{{ $result->links() }}</div>
                    </div>
                </div>
                <!-- end of col -->

            </div>
            <!-- end of row -->