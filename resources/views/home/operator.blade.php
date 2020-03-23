@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1>Operator Account </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>



              <div class="row">
                  <div class="col-lg-12 col-md-12">
                      <div class="card mb-2">
                          <div class="card-body">
                                <button class="btn btn-success m-1" id="btn_add_operator"  data-toggle="modal" data-target="#myModal">Add Operator</button> 
                                <button class="btn btn-secondary m-1" id="btn_refresh">Refresh</button> 
                                <button class="btn btn-primary collapsed m-1" data-toggle="collapse" data-target="#filter-panel">Filter</button>
                             

                              <div class="row row-xs">



                                  <div id="filter-panel" class="filter-panel collapse">
                                        
                                                <form class="form" role="form" id="cdr_filter_form">
                                                    <div class="row"> 
                                                    
                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="filter-col"  for="pref-perpage">By Operator Name</label>
                                                        <select name="operator" class="form-control">
                                                            <option value="">All</option>
                                                            @if(!empty($operators))
                                                                @foreach($operators as $opr )
                                                                    <option value="{{$opr->id}}">{{$opr->opername}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                            
                                                        </select>                                
                                                    </div> 
                                                   
                                                   
                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="filter-col"  for="pref-search">Phone Number</label>
                                                        <input type="text" class="form-control input-sm" name="caller_number">
                                                    </div>

                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="filter-col"  for="pref-search">By Department</label>
                                                        <input type="text" class="form-control input-sm" name="department">
                                                    </div>

                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="filter-col"  for="pref-search">By Priority</label>
                                                        <input type="text" class="form-control input-sm" name="caller_number">
                                                    </div>


                                                    <div class="col-md-6 form-group mb-3">    
                                                        
                                                        <button type="button" id="report_search_button" class="btn btn-default filter-col">
                                                            <span class="glyphicon glyphicon-record"></span> Search
                                                        </button>  
                                                    </div>
                                                </div>
                                                </form>
                                            
                                    </div>


                                 
                              </div>
                              
                          </div>
                      </div>
                  </div>
              </div>



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
                <!-- end of col -->

            </div>
            <!-- end of row -->


<div id="myModal" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="" id="user_date_form">
    <div class="modal-content">
      <div class="modal-header">
       <h4>Add Operator</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       
            <div class="form-group">
              <label for="date">Phonenumber:</label>
              <input type="text" class="form-control" id="phonenumber" placeholder="Phonenumber" maxlength="30" name="phonenumber" required >
            </div>

            <div class="form-group">
              <label for="date">Login Id:</label>
              <input type="text" class="form-control" id="login_username" placeholder="Login Id" name="login_username" required maxlength="30">
            </div>
             <div class="form-group">
              <label for="date">Password:</label>
              <input type="text" class="form-control" id="password" placeholder="Password" name="password" required maxlength="30">
            </div>
             <div class="form-group">
              <label for="date">Extension Number:</label>
                
                <select name="extensions" class="form-control" required>
                    <option value="">Select</option>
                    
                </select>
            </div>
            
            <div class="form-group">
              <label for="start_time">Operator:</label>
              <input type="text" class="form-control" id="opname" placeholder="Select Time" name="opname"  required maxlength="30">
            </div>
            <div class="form-group">
              <label for="end_time">Status:</label>
              <select name="opstatus" class="form-control" required>
                    <option value="">Select</option>
                    
                </select>
            </div>
            <div class="form-group">
              <label for="time_interval">Live Transfer no:</label>
              <input type="text" class="form-control" id="livetransfer" placeholder="Live Transfer no" maxlength="2" name="livetransfer"  required>
            </div>

             <div class="form-group">
              <label for="time_interval">Start time  (00:00:00):</label>
             <input type="text" id="start_work" name="start_work" size="8" maxlength="10" value="00:00:00" required class="form-control">
            </div>

             <div class="form-group">
              <label for="time_interval">End time  (23:59:59):</label>
              <input type="text" id="end_work" name="end_work" size="8" maxlength="10" value="23:59:59" required class="form-control">
            </div>

             <div class="form-group">
              <label for="time_interval">Andriod App:</label>
              <select name="app_use" class="form-control" required>
                    <option value="">Select</option>
                    
                </select>
            </div>

            <div class="form-group">
              <label >Options:</label><br/>
               <label>Cdr Download</label>
              <select name="edit" class="form-control" required>
                    <option value="">Select</option>
                    
                </select>
                <label>Rec Download</label>
                <select name="download" class="form-control" required>
                    <option value="">Select</option>
                    
                </select>
                <label>Rec Play</label>
                <select name="play" class="form-control" required>
                    <option value="">Select</option>
                    
                </select>
            </div>

           
       
      </div>
      <div class="modal-footer">
        <input type="hidden" name="act" value="insert">
        <input type="submit" name="Submit" id="submit_btn" value="submit" class="btn btn-primary">
        <input type="button" name="Cancel" value="cancel" class="btn btn-default" data-dismiss="modal">
      </div>
    </div>
 </form>
  </div>
</div>



@endsection

@section('page-js')

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">

    $(document).on("click","#report_search_button",function(){
        get_report_search(1);
    });
    $(document).on("click",".page-link",function(event)
    {
        event.preventDefault();
        var page = $(this).text();       
        get_report_search(page);
    });
    $(document).on("click","#btn_refresh",function(){
        $("#cdr_filter_form")[0].reset();
        get_report_search(1);
    });


function get_report_search(page)
{
    $.ajax({
        type: 'POST',
        url : "{{ url('getoperatorsearch') }}",
       
        data: $("#cdr_filter_form").serialize()+'&page='+page,
        success: function (data) {
            if (data.success == 1) {
                $("#div_table").replaceWith(data.view);
                 $('#zero_configuration_table').DataTable();
            } else if (data.error == 1) {
                alert(data.errormsg);
            }
        }

    });
}
 </script>



@endsection
