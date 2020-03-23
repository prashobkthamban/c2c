@extends('layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.time.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/css/bootstrap-timepicker.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>Category</h1>   
</div>

 <div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title mb-3">Add Category</div>
                {!! Form::open(['action' => 'CategoryController@store', 'method' => 'post']) !!}
                <form method="post">
                    <div class="row">
				    	<div class="col-md-6 form-group mb-3">
				            <label for="firstName1">Name*</label>
				            <input type="text" name="name" class="form-control" id="name" placeholder="Enter your Name" />  
				            <p class="text-danger">{!! !empty($messages) ? $messages->first('name', ':message') : '' !!}</p>
				        </div>
				       <?php 
				       //print_r($category_data);
                            function fetch_menu($data){

                                foreach($data as $menu){

                                    echo "<option value='".$menu->id."'>".$menu->name."</option>";

                                    if(!empty($menu->sub)){

                                        fetch_sub_menu($menu->sub,$menu->child_level);
                                    }

                                }

                            }

                            function fetch_sub_menu($sub_menu,$level){
                                $dash = '';
                                for ($i=0; $i < ($level+1); $i++) { 
                                        $dash .= '-';
                                    }
                                foreach($sub_menu as $menu){

                                    
                                    echo "<option value='".$menu->id."'>".$dash.$menu->name."</option>";
                                    
                                    if(!empty($menu->sub)){

                                        fetch_sub_menu($menu->sub,$menu->child_level);
                                    }       

                                }

                            }

                        ?>
				        <div class="col-md-6">
				            <label for="">Select Parent Category</label>
				            <select class="form-control" name="parent_id">
				                <option value="0">None</option>
				                <?php
                                    fetch_menu($category_data);
                                ?>
				            </select>
				            <p class="text-danger">{!! !empty($messages) ? $messages->first('parent_id', ':message') : '' !!}</p>
				        </div>
				        <div class="col-md-12">
				            <button class="btn btn-primary">Submit</button>
				        </div>
				    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
 </div>
 <?php 
//print_r($category_data);
    function fetch_menu1($data){

        foreach($data as $menu){

        	echo "<tr><td>".$menu->id."</td>";
            echo "<td>".$menu->name."</td>";
            echo "<td>".$menu->child_level."</td></tr>";

            if(!empty($menu->sub)){

                fetch_sub_menu1($menu->sub,$menu->child_level);
            }

        }

    }

    function fetch_sub_menu1($sub_menu,$level){
        $dash = '';
        for ($i=0; $i < ($level+1); $i++) { 
                $dash .= '-';
            }
        foreach($sub_menu as $menu){

            echo "<tr><td>".$menu->id."</td>";
            echo "<td>".$dash.$menu->name."</td>";
            echo "<td>".$menu->child_level."</td></tr>";
            
            if(!empty($menu->sub)){

                fetch_sub_menu1($menu->sub,$menu->child_level);
            }       

        }

    }

?>
<div class="row">
    <div class="col-md-12">
    	<table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:50%;">
		   <thead>
		        <tr>
		        	<th>ID</th>
		            <th>Name</th>
		            <th>Level</th>
		        </tr>
		    </thead>                                
		    <tbody>
		       <?php 
		       		fetch_menu1($category_data);
		       ?>
		    </tbody>
		    <tfoot>
		        <tr>
		        	<th>ID</th>
		            <th>Name</th>
		            <th>Level</th>
		        </tr>
		    </tfoot>
		</table>
    </div>
</div>
@endsection