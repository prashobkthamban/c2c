@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/ladda-themeless.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrumb">
                <h1>User Profile</h1>
            </div>

            <div class="separator-breadcrumb border-top"></div>

            <div class="card user-profile o-hidden mb-4">
                <div class="header-cover" style="background-image: url({{asset('assets/images/photo-wide-5.jpeg')}}"></div>
                <div class="user-info">
                    <img class="profile-picture avatar-lg mb-2" src="{{asset('assets/images/faces/1.jpg')}}" alt="">
                    <p class="m-0 text-24">{{ Auth::user()->username }} </p>
                    <!-- s -->
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Settings</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        <div class="tab-pane fade active show" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <h4>Personal Information</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Administrator text-16 mr-1"></i> UserName</p>
                                        <span>{{ Auth::user()->username }} </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-MaleFemale text-16 mr-1"></i> UserType</p>
                                        <span>{{ Auth::user()->usertype }}</span>
                                    </div>
                                </div>
								@if(Auth::user()->usertype == 'groupadmin')
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Telephone text-16 mr-1"></i>Identity Number</p>
                                        <span id="phone_info">{{ Auth::user()->did->did }} ({{ Auth::user()->did->rdins }})</span>
                                    </div>
                                </div>
                                 <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Telephone text-16 mr-1"></i> Account Name</p>
                                        <span id="phone_info">{{ $acGrp[0]->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Telephone text-16 mr-1"></i> Phone</p>
                                        <span id="phone_info">{{ Auth::user()->phone_number }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Email text-16 mr-1"></i> Email</p>
                                        <span id="email_info">{{ Auth::user()->email }} </span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Clock-Forward text-16 mr-1"></i> Office Open From</p>
                                        <span id="office_open_info">{{ isset($acGrp[0]) ? $acGrp[0]->office_start : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Clock-Back text-16 mr-1"></i> Office Close At</p>
                                        <span id="office_end_info">{{ isset($acGrp[0]) ? $acGrp[0]->office_end : '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Telephone text-16 mr-1"></i>After Office Call trasfer to</p>
                                        <span id="phone_info">{{ $acGrp[0]->aocalltransfer }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Telephone text-16 mr-1"></i>Play Message</p>
                                        <span id="phone_info">{{ $acGrp[0]->playaom }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6">
                                    <div class="mb-4">
                                        <p class="text-primary mb-1"><i class="i-Notepad text-16 mr-1"></i> <a href="#" data-toggle="modal" data-target="#list_modal">Extra Did</a></p>
                                        <table id="extra_did_table" class="display table table-bordered" style="width:20%">
                               <thead>
                                    <tr>
                                        <th>Did No</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($did as $listOne) 
                                    <tr>
                                        <td>{{$listOne->did_no}}</td>
                                        <td>{{$listOne->did_name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                                    </div>
                                </div>
                                @endif
                            </div>
                            <hr>
        
                        </div>

                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Update Profile</h4>
                                    <div class="card mb-5">
                                        <div class="card-body">
                                            {!! Form::open(['id' => 'edit_profile']) !!}
                                                <input type="hidden" name="id" value="{{ Auth::user()->id }}" />
                                                <input type="hidden" name="usertype" value="{{ Auth::user()->usertype }}" />            
                                                @if(Auth::user()->usertype == 'groupadmin')
                                                    <input type="hidden" name="groupid" value="{{ Auth::user()->groupid }}" />
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="email" name="email" placeholder="Mail Id" value="{{ Auth::user()->email }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Phone</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="phone" name="phone_number" placeholder="Mobile" value="{{ Auth::user()->phone_number }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Office Open From</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control timepicker" id="office_start" data-rel="timepicker" data-template="dropdown" data-maxHours="24" data-show-meridian="false" data-minute-step="10" name="office_start" placeholder="00:00:00" value="{{ isset($acGrp[0]) ? $acGrp[0]->office_start : '' }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Office Close At</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control timepicker" data-rel="timepicker" data-template="dropdown" data-maxHours="24" data-show-meridian="false" data-minute-step="10" id="office_end" name="office_end" placeholder="23:59:59" value="{{ isset($acGrp[0]) ? $acGrp[0]->office_end : '' }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">After Office Call trasfer to</label>
                                                    <div class="col-sm-9">
                                                        <Select class="form-control"  id="aocalltransfer" name="aocalltransfer" >
                                                        <option value="message"  {{ $acGrp[0]->aocalltransfer =='message'?'SELECTED':'' }} > Message </option>
                                                        <option value="voicemail" {{ $acGrp[0]->aocalltransfer =='voicemail'?'SELECTED':'' }} > Voicemail</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Play Message</label>
                                                    <div class="col-sm-9">
                                                       <Select class="form-control"  id="playaom" name="playaom" >
                                                        <option value="Before"  {{ $acGrp[0]->playaom =='Before'?'SELECTED':'' }} > Before </option>
                                                        <option value="After" {{ $acGrp[0]->playaom =='After'?'SELECTED':'' }} > After</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Working Days</label>
                                                    <div class="col-sm-9">
							<?php $days = json_decode($acGrp[0]->working_days); ?>
                                                             <button type="button" id="Sun" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Sun',$days)){ echo 'btn-primary';} ?>" onClick="selectDay('Sun');" title="Sunday">S</button>
                                        <button type="button" id="Mon" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Mon',$days)){ echo 'btn-primary';} ?>" onClick="selectDay('Mon');" title="Monday">M</button>
                                        <button type="button" id="Tue" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Tue',$days)){ echo 'btn-primary';} ?>" onClick="selectDay('Tue');" title="Tuesday">T</button>
                                        <button type="button" id="Wed" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Wed',$days)){ echo 'btn-primary';} ?>" onClick="selectDay('Wed');" title="Wednesday">W</button>
                                        <button type="button" id="Thu" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Thu',$days)){ echo 'btn-primary';} ?>"onClick="selectDay('Thu');" title="Thursday">T</button>
                                        <button type="button" id="Fri" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Fri',$days)){ echo 'btn-primary';} ?>" onClick="selectDay('Fri');" title="Friday">F</button>
                                        <button type="button" id="Sat" class="btn btn-rounded m-1 <?php  if($days != "" && in_array('Sat',$days)){ echo 'btn-primary';} ?>" onClick="selectDay('Sat');" title="Saturday">S</button>
                                        <input type='hidden' id="working_days" name='working_days' value="" />
                                                    </div>
                                                </div>
                                                <!--  need to update working day settings  -->
                                                @endif
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Password</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="password" name="password" placeholder="Password" value="{{ Auth::user()->user_pwd }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-top mb-5"></div>
                        </div>
                   
				   </div>
                </div>
            </div>



@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/spin.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/ladda.js')}}"></script>
<script src="{{asset('assets/js/ladda.script.js')}}"></script>
<script src="{{asset('assets/js/moment.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-timepicker.min.js')}}"></script>

<script type="text/javascript">
    var days = [];
    <?php foreach($days as $key => $val){ ?>
        days.push('<?php echo $val; ?>');
    <?php } ?>
   
    function selectDay(day) {
        if(!$("#" + day).hasClass('btn-primary')) {
            $("#" + day).addClass('btn-primary');
            days.push(day);
        } else {
            $("#" + day).removeClass('btn-primary');
            var dayIndex = days.indexOf(day); 
            days.splice(dayIndex, 1);
        }
        $("#working_days").val(days);
    }
    $(document).ready(function() {
        $('.timepicker').timepicker();
        $( '#edit_profile' ).on( 'submit', function(e) {
            e.preventDefault();
            var noteHTML = "";
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("editProfile") }}', // This is the url we gave in the route
            data: $('#edit_profile').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    $.each(res.error, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            errors += value[0];
                            errors += "</br>";
                        }
                    });
                    toastr.error(errors);
                } else {
                    toastr.success(res.success); 
                               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });
        $( '#crm_settings' ).on( 'submit', function(e) {
            e.preventDefault();
            var noteHTML = "";
            var errors = ''; 
          $.ajax({
            type: "POST",
            url: '{{ URL::route("crmSettings") }}', // This is the url we gave in the route
            data: $('#crm_settings').serialize(),
            success: function(res){ // What to do if we succeed
                if(res.error) {
                    toastr.error(res.errors);
                } else {
                    toastr.success(res.success); 
                               
                }
               
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                toastr.error('Some errors are occured');
            }
          });
        });
    });
</script>
@endsection
