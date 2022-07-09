 <div class="row mb-4" id="div_table">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                           <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" name="allselect" id="allselect" value="yes" onclick="selectAll();"></th>
                                        <th>Caller Id</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Department</th>
                                        <th>Agent</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($result))
                                        @foreach($result as $row )
                                    <tr data-toggle="collapse" data-target="#accordion_{{$row->cdrid}}" class="clickable">
                                    <td><input type="checkbox" name="cdr_checkbox" id="{{$row->cdrid}}" value="{{$row->cdrid}}" class="allselect"></td>
                                    <td id="caller_{{$row->cdrid}}">
                                        @if(Auth::user()->usertype=='groupadmin')
                                        <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                        @elseif(Auth::user()->usertype=='admin' or Auth::user()->usertype=='reseller')
                                        {{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : $row->number }}
                                        @else
                                        <a href="?" id="callerid_{{$row->cdrid}}" data-toggle="modal" data-target="#formDiv" title="{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}" onClick="xajax_editc2c({{$row->id}});return false;"><i class="fa fa-phone"></i>{{ $row->contacts->fname ? $row->contacts->fname : $row->number }}</a>
                                        @endif
                                    </td>
                                    <td>{{$row->datetime}}</td>
                                    <td>{{$row->status}}</td>
                                    <td>{{$row->deptname}}</td>
                                    <td>{{ $row->operatorAccount ? $row->operatorAccount->opername : '' }}</td>
                                    <td>
                                        <a class="btn bg-gray-100" data-toggle="collapse" data-target="
                                        #more{{$row->cdrid}}" aria-expanded="false" aria-controls="collapseExample"><i class="i-Arrow-Down-2" aria-hidden="true"></i></a>
                                       
                                        @if($row->recordedfilename !== '')
                                        <a href="#" class="btn bg-gray-100 play_audio" data-toggle="modal" data-target="#play_modal" data-file="{{$row->recordedfilename}}" id="play_{{$row->groupid}}"><i class="i-Play-Music"></i></a>
                                        <a href="{{ url('download_file/' .$row->recordedfilename.'/'.$row->groupid) }}" class="btn bg-gray-100">
                                        <i class="i-Download1"></i></a>
                                        @endif                 
                                        <a href="#" class="btn bg-gray-100 notes_list" data-toggle="modal" data-target="#notes_modal" id="notes_{{$row->uniqueid}}"><i class="i-Notepad"></i></a>
                                        <a href="" class="btn bg-gray-100 history_list" data-toggle="modal" data-target="#history_modal" id="history_{{$row->number}}"><i class="i-Notepad-2"></i></a>
                                        <a href="" class="btn bg-gray-100" data-toggle="dropdown" id="history_{{$row->number}}"><i class="  i-Add-User"></i></a>

                                        <ul class="dropdown-menu" role="menu">
                                          @foreach($operators as $operator)
                                          @if( $account_service['smsservice_assign_cdr'] =='Yes' ||  $account_service['emailservice_assign_cdr'] =='Yes')
                                            <li> 
                                                <a href="#">{{$operator->opername}}</a><ul>
                                          @else 
                                            <li> 
                                                <a href="#">{{$operator->opername}}</a>
                                          @endif
                                          @if($account_service['smsservice_assign_cdr'] =='Yes')
                                            <li>
                                                <a href="javascript:assignoper({{$row->cdrid}},{{$operator->id}},'{{$operator->opername}}','S');">Notify By SMS</a>
                                            </li>
                                          @endif
                                          @if($account_service['emailservice_assign_cdr'] =='Yes')
                                            <li>
                                                <a href="javascript:assignoper({{$row->cdrid}},{{$operator->id}},{{$operator->opername}},'E');">Notify By Email</a>
                                            </li>
                                          @endif 
                                          @if( $account_service['smsservice_assign_cdr'] =='Yes' ||  $account_service['emailservice_assign_cdr'] =='Yes')
                                            </ul>
                                          @else 
                                            </li>
                                          @endif
                                          @endforeach
                                          <?php echo '<li><a href="javascript:assignoper('.$row->cdrid.',0);">Unassign</a></li>'; ?>
                                        </ul>
                                        <span>
                                        <button class="btn bg-gray-100" type="button" id="action_{{$row->cdrid}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="nav-icon i-Arrow-Down-in-Circle"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="action_{{$row->cdrid}}">
                                        <a class="dropdown-item edit_contact" href="#" data-toggle="modal" data-target="#contact_modal" id="contact_{{ $row->contacts && $row->contacts->id ? $row->contacts->id : ''}}" data-email="{{ $row->contacts && $row->contacts->email ? $row->contacts->email : ''}}" data-fname="{{ $row->contacts && $row->contacts->fname ? $row->contacts->fname : ''}}" data-lname="{{ $row->contacts && $row->contacts->lname ? $row->contacts->lname : ''}}" data-groupid="{{$row->groupid}}" data-phone="{{$row->number}}">{{isset($row->contacts->fname) ? 'Update Contact': 'Add Contact'}}</a>
                                        <a class="dropdown-item edit_tag" href="#" data-toggle="modal" data-target="#tag_modal" id="tag_{{$row->cdrid}}" data-tag="{{$row->tag}}">{{$row->tag ? 'Update Tag': 'Add Tag'}}</a>
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#add_note_modal" id="add_note_{{$row->uniqueid}}">Add Notes
                                        </a>
                                        @if(!isset($row->reminder->id))
                                        <a class="dropdown-item edit_reminder" href="#" data-toggle="modal" data-target="#add_reminder_modal" id="add_reminder_{{$row->cdrid}}">Add Reminder</a>
                                        @endif
                                        </span>
                                        </div>
                                    </td>
                                    </tr>
                                    <tr id="more{{$row->cdrid}}" class="collapse">
                                        <td></td>
                                         <td colspan='7'>
                                            <span style="margin-right:100px;"><b>DNID :</b> {{$row->did_no
                                        }}</span><span style="margin-right:100px;"><b>Duration :</b> {{$row->firstleg."(".$row->secondleg.")"}}</span><span style="margin-right:100px;"><b>Coin :</b> {{$row->creditused}}</span><span style="margin-right:100px;"><b>Assigned To :</b> <span id="assigned_{{$row->cdrid}}">{{$row->operatorAssigned ? $row->operatorAssigned->opername : ''}}</span></span><span style="margin-right:100px;"><b>Tag :</b> {{$row->tag}}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                        @endif

                                    </tbody>
                                </table>
                                <div class="pull-right">{{ $result->links() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>