<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Category;

use File;

use Excel;
/*use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\ToModel;
*/
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->products = new Product();
    }

    public function index(){

        $result = Product::getReport();

        $products = DB::table('products')
            ->join('category', 'category.id', '=', 'products.category_id')
            ->select('products.*','category.name as cat_name')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $category = new Product();
        $category_data =  $this->select_category_data();
        return view('products.product_index',compact('products','category_data','result'));
    }

    public function select_category_data()
    {
        /*$this->db->where('parent_id', 0);
        $parent =$this->db->get('category');*/
        $parent = Category::where('parent_id',0)
            ->get();
        //$categories = $parent->result();
        $i=0;
        foreach($parent as $p_cat){

            $parent[$i]->sub = $this->sub_categories($p_cat->id);
            $i++;
        }
        return $parent;
    }

    public function sub_categories($id){

        /*$this->db->select('*');
        $this->db->from('category');
        $this->db->where('parent_id', $id);

        $child = $this->db->get();
        $categories = $child->result();*/
        $child = Category::where('parent_id',$id)
            ->get();
        $i=0;
        foreach($child as $p_cat){

            $child[$i]->sub = $this->sub_categories($p_cat->id);
            $i++;
        }
        return $child;       
    }

    public function store(Request $request)
    {
        //dd($request->all());exit;
        $products = DB::table('products')
            ->select('*')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $pro = new Product();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'uom' => 'required',
            'landing_cost' => 'required',
            'selling_cost' => 'required'
        ]);
        
        if($validator->fails()) {
            $messages = $validator->messages();
            return view('products.product_index', compact('messages','products'));
        } else {
            
            //echo "else";exit;
            $file = $request->file('p_image');
                
                if ($request->hasFile("p_image")) 
                {
                    $file = $request->file("p_image");
                    $file->move("product_images/",$file->getClientOriginalName());
                    $image_name = $file->getClientOriginalName();
                }
                else
                {
                    $image_name = '';
                }

            $add_product = new Product([
                'name' => $request->get('name'),
                'category_id' => $request->get('category_id'),
                'image'=> $image_name,
                'unit_of_measurement'=> $request->get('uom'),
                'landing_cost'=> $request->get('landing_cost'),
                'selling_cost'=> $request->get('selling_cost'),
                'description'=> $request->get('description')
            ]);

            //dd($add_product);exit;
            $add_product->save();
            toastr()->success('Product added successfully.');
            return redirect()->route('productIndex');
        }
    }

    public function edit(Request $request)
    {
        //print_r($request->get('myid'));
        $user = DB::table('products')->where('id', $request->get('myid'))->first();
        //print_r($user);
        echo json_encode($user);
        //exit;
    }

    public function update(Request $request)
    {
        //print_r($request->all());exit;
        $id = $request->get('id');
        $pro = new Product();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'uom' => 'required',
            'landing_cost' => 'required',
            'selling_cost' => 'required'
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            return view('products.product_index', compact('messages','products'));
        } else {

             $file = $request->file('p_image');
                
                if ($request->hasFile("p_image")) {
                    
                    unlink(public_path('product_images/'.$request->old_image));
                    $file = $request->file("p_image");
                    $file->move("product_images/",$file->getClientOriginalName());
                }

                if (empty($request->file('p_image'))) {
                    
                    $image = $request->get('old_image');
                }else{
                    
                   $image = $file->getClientOriginalName();
                }
                
                $edit_product = Product::find($id);
    
                $edit_product->name = $request->name;
                $edit_product->category_id = $request->category_id;
                $edit_product->image = $image ? $image : '';
                $edit_product->unit_of_measurement = $request->uom;
                $edit_product->landing_cost = $request->landing_cost;
                $edit_product->selling_cost = $request->selling_cost;
                $edit_product->description = $request->description;

               /* print_r($edit_product);exit();*/

            $edit_product->save();
            toastr()->success('Product Updated successfully.');
            return redirect()->route('productIndex');
        }

    }

    public function destroy($id)
    {
        DB::table('products')->where('id',$id)->delete();
        toastr()->success('Product delete successfully.');
        return redirect()->route('productIndex');
    }

    public function addproductcsv(Request $request)
    {
          $file = $_FILES['products_file']['tmp_name'];
            //$handle = fopen($file, "r");
            $row = 1;
            if (($handle = fopen($file, "r")) !== FALSE) {
                $newdata = '';
              while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row == 1)
                {
                    $row++;
                }else
                {
                    $num = count($data);

                    /*echo '<pre>';
                    print_r($data);exit;*/


                    $add_data = new Product([
                        'name' => $data[0],
                        'category_id' => $data[1],
                        'unit_of_measurement' => $data[2],
                        'selling_cost' => $data[3],
                        'landing_cost' => $data[4],
                        'description' => $data[5],
                    ]);
                    
                    $add_data->save();
                }
                
              }
              fclose($handle);
            }

        toastr()->success('Product Inserted successfully.');
        return redirect()->route('productIndex');
    }

}




















?>