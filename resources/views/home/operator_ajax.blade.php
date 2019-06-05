
            <div class="row mb-4" id="div_table">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">

                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Operator</th>
                                        <th>Phone</th>
                                        <th>Loginid</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Exten</th>
                                        <th>Start time</th>
                                        <th>End time</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr>
                                        <td>
                                           {{ $row->opername }}

                                        </td>
                                        <td>{{$row->phonenumber}}</td>
                                        <td>{{$row->login_username}}</td>
                                        <td>{{$row->password}}</td>
                                        <td><a>{{$row->oper_status}}</a></td>
                                        <td>{{$row->livetrasferid}}</td>
                                        <td>{{$row->start_work}}</td>
                                        <td>{{$row->end_work}}</td>


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
