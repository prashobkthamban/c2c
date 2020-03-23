@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
  <div class="breadcrumb">
        <h1> Product </h1> 
    </div>

    <div class="addProduct" style="position: absolute;display: none;z-index: 11;right: 0;width: 50%;">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Product<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                            {!! Form::open(['action' => 'ProductController@store', 'method' => 'post','enctype' => 'multipart/form-data']) !!}
                            <form method="post">
                                 {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="name">Name*</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your Name" />  
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
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="category_id">Category*</label>
                                       <!--  <input type="text" name="category_id" id="category_id" class="form-control"placeholder="Enter Category" />  -->
                                        <select class="form-control" name="category_id" id="category_id">
                                            <option value="0">None</option>
                                            <?php
                                                fetch_menu($category_data);
                                            ?>
                                        </select> 
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('category_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="uom">Unit of Measurement*</label>
                                        <input type="text" name="uom" id="uom" class="form-control"placeholder="Enter Unit of Measurement" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('uom', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="landing_cost">Landing Cost*</label>
                                        <input type="text" name="landing_cost" id="landing_cost" class="form-control"placeholder="Enter Landing Cost" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('landing_cost', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="selling_cost">Selling Cost*</label>
                                        <input type="text" name="selling_cost" id="selling_cost" class="form-control"placeholder="Enter Selling Cost" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('selling_cost', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="description">Description</label>
                                       <textarea name="description" id="description" class="form-control"></textarea> 
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="p_image">Product Image</label>
                                        <input type="file" name="p_image" id="p_image" class="form-control">
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

    <div class="editProduct" style="position: absolute;display: none;z-index: 11;right: 0;width: 50%;">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Edit Category<a href="" class="btn btn-primary" style="float: right;">Back</a></div>
                            {!! Form::open(['action' => 'ProductController@update', 'method' => 'PATCH','enctype' => 'multipart/form-data']) !!}
                            <form method="post">
                                 {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <input type="hidden" name="id" id="id" class="form-control" />
                                        <label for="name">Name*</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your Name" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('name', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="category_id">Category*</label>
                                        <!-- <input type="text" name="category_id" id="category_id" class="form-control"placeholder="Enter Category" /> -->
                                        <select class="form-control" name="category_id" id="category_id">
                                            <option value="0">None</option>
                                            <?php
                                                fetch_menu($category_data);
                                            ?>
                                        </select>   
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('category_id', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="uom">Unit of Measurement*</label>
                                        <input type="text" name="uom" id="uom" class="form-control"placeholder="Enter Unit of Measurement" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('uom', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="landing_cost">Landing Cost*</label>
                                        <input type="text" name="landing_cost" id="landing_cost" class="form-control"placeholder="Enter Landing Cost" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('landing_cost', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="selling_cost">Selling Cost*</label>
                                        <input type="text" name="selling_cost" id="selling_cost" class="form-control"placeholder="Enter Selling Cost" />  
                                        <p class="text-danger">{!! !empty($messages) ? $messages->first('selling_cost', ':message') : '' !!}</p>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="description">Description</label>
                                       <textarea name="description" id="description" class="form-control"></textarea> 
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="p_image">Product Image</label>
                                        <input type="hidden" name="old_image" id="old_image" class="form-control">
                                        <input type="file" name="p_image" id="p_image" class="form-control">
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

            <div class="separator-breadcrumb border-top"></div>


           <div class="row mb-4">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-body">
                            <a title="Compact Sidebar" id="add_product" href="javascript:void(0)" class="btn btn-primary"> Add Product </a>
                            <div class="table-responsive">
                                <table id="zero_configuration_table" class="display table table-striped table-bordered" style="width:100%">
                                   <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category Name</th>
                                            <th>Unit Of Measurement</th>
                                            <th>Selling Cost</th>
                                            <th>Landing Cost</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        @foreach($products as $product)
                                        <tr>
                                            <td><?php if ($product->image == '') {
                                                echo 'No Image';
                                            }else{ ?>
                                                <img src="{{ url('product_images/'.$product->image) }}" style="height: 50px; width: 50px;"/>
                                            <?php }?></td>
                                            <td>{{$product->name}}</td>
                                            <td>{{$product->cat_name}}</td>
                                            <td>{{$product->unit_of_measurement}}</td>
                                            <td>{{$product->selling_cost}}</td>
                                            <td>{{$product->landing_cost}}</td>
                                            <td>{{$product->description}}</td>
                                            <td>
                                                <a href="javascript:void(0)" class="text-success mr-2 edit_product" id="edit_product" data-id="{{$product->id}}"><i class="nav-icon i-Pen-2 font-weight-bold"></i>
                                                </a>
                                                <a href="{{ route('deleteProduct', $product->id) }}" onclick="return confirm('Are you sure you want to delete this Product?')" class="text-danger mr-2">
                                                    <i class="nav-icon i-Close-Window font-weight-bold"></i>
                                                </a>  
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category ID</th>
                                            <th>Unit Of Measurement</th>
                                            <th>Selling Cost</th>
                                            <th>Landing Cost</th>
                                            <th>Description</th>
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
<script type="text/javascript">
    $('#add_product').click(function(){
        //$(".addProduct").show(1000);
        $(".addProduct").animate({width: 'toggle'}, "slow");
        //$('.addProduct').css('display','block');
    });

    $('.edit_product').click(function(){
        myid = $(this).data('id');
         $(".editProduct").animate({width: 'toggle'}, "slow");
         $(".editProduct #id").val(myid);
         jQuery.ajax({
            type: "POST",
            url: "product/edit",
            dataType: 'text',
            data: {myid:myid},
            success: function(edit_data) 
            {
                //console.log(edit_data);
                var obj = jQuery.parseJSON(edit_data);
                //console.log(obj);
                $(".editProduct #name").val(obj.name);
                $(".editProduct #category_id").val(obj.category_id);
                $(".editProduct #uom").val(obj.unit_of_measurement);
                $(".editProduct #landing_cost").val(parseFloat(obj.landing_cost).toFixed(2));
                $(".editProduct #selling_cost").val(parseFloat(obj.selling_cost).toFixed(2));
                $("editProduct #description").val(obj.description);
                $(".editProduct #old_image").val(obj.image);
                /*$('#amount').val(parseFloat(mul_data).toFixed(2));
                $('.rent_amount_1').val(parseFloat(mul_data).toFixed(2));
                grand_total();
                payment_amount();*/
            }
        });
        // alert($(".editProduct #id").val(2));
    });
</script>
@endsection
