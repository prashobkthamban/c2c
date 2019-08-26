@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
                <h1> Operator Groups </h1>

            </div>
            <div class="separator-breadcrumb border-top"></div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive" id="operator_groups">
                                <table id="zero_configuration_table" class="table" style="width:25%">
                                   <thead>
                                        <tr>
                                            <th>Group Name</th>
                                            <th>View</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($operatordept as $listOne)
                                        <tr>
                                            <td>{{$listOne->dept_name}}</td>
                                            <td><i class="i-Checked-User" onClick="showDetails('{{$listOne->id}}');return false;"></i></td>   
                                        </tr>
                                        @endforeach
                                      
                                    </tbody>
                                </table>
                            </div>
                            <div class="container" style="width: 100%;display: none;" id="operator_groups_details">
                                <div id="grid1" name="grid1" align="left" class="table-responsive" style="visibility: visible;width: 50%;display: inline-block;">
                                    <table table="" id="department-basic" class="table">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="grid2" name="grid2" align="center" style="left: 450px; top: 50px; visibility: visible; width: 50%;display: inline-block;">
                                    <table table="" id="settings" class="table">
                                        <tbody>
                                        </tbody>
                                    </table></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of row -->



@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script>

    function showDetails(id) {
        console.log('sfd', id);
        $.ajax({
            url: '/operatorgrp_details/'+id, // This is the url we gave in the route
            success: function(res){ // What to do if we succeed
                //var response = JSON.stringify(res);
                console.log(res);
                var depHTML = "";
                var settings = "";
                $("#operator_groups_details").attr("style","display:table;");
                if(res) {
                    depHTML += '<tr><td><h4>Department Name :</h4></td><td colspan="2" ><h4>'+res.dept_name+'</h4></td></tr><tr><td colspan="3"></td></tr><tr><td><i class="fa fa-desktop" aria-hidden="true"></i>  Teena(8594049580)priority:3</td><td></td><td><a href="?" onclick="xajax_delete(5136,1);return false;"><i class="fa fa-trash-o " aria-hidden="true"></i></a></td></tr><tr><td colspan="3"> Total 1 Operators in DIRECT-C Department</td></tr><tr><td colspan="3" align="right"><button class="btn btn-success btn-sm" onclick="xajax_addoperator(1)">Add Operator</button><button class="btn btn-success btn-sm" onclick="xajax_addopertornum(1)">Add Number</button></td></tr>';
                    settings += '<tr><td colspan="3"> <h4>Settings</h4></td></tr><tr><td>Call Distribution :  </td><td>Call_Hunting</td><td>    <a href="?" onclick="xajax_edit(1,1);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Sticky Agent :  </td><td>No</td><td><a href="?" onclick="xajax_edit(1,2);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Call Recording  :  </td><td>yes</td><td><a href="?" onclick="xajax_edit(1,3);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Dial Time :   </td><td>25(In Seconds)</td><td><a href="?" onclick="xajax_edit(1,4);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Working Hour Start :   </td><td>00:00:00</td><td><a href="?" onclick="xajax_edit(1,5);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr><tr><td>Working Hour End :   </td><td>23:59:59</td><td><a href="?" onclick="xajax_edit(1,6);return false;"><i class="fa fa-pencil-square" aria-hidden="true"></i></a></td></tr>';
                }
               
                $("#department-basic tbody").html(depHTML);
                $("#settings tbody").html(settings);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            }
        });
    }
</script>


@endsection

