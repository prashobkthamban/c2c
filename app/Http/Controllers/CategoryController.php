<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        if(!Auth::check()){
            return redirect('login');
        }
        $this->category = new Category();
    }

    public function index(){

        $result = Category::getReport();

    	$categories = DB::table('category')
            ->select('*')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $category = new Category();
        $category_data =  $this->select_category_data();
        return view('settings.category',compact('categories','category_data','result'));
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

    public function addCategory()
    {
        /*$did = new Dids();
        $prigateway = $did->get_prigateway();*/
        $category_data =  $this->select_category_data();
        return view('settings.add_category',compact('category_data'));
    }

    public function store(Request $request){
        
        //dd($request->all());exit();
        $category = new Category();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'parent_id' => 'required',
        ]);

        if($validator->fails()) {

            $messages = $validator->messages();
            $category_data =  $this->select_category_data();
            return view('settings.add_category', compact('messages','category_data'));
        } else {
  			$child_level = Category::select('child_level')
  				->where('id',$request->get('parent_id'))
  				->get();

  			//print_r($child_level[0]['child_level']);exit();
            $did = new Category([
                'name' => $request->get('name'),
                'parent_id' => $request->get('parent_id'),
                'child_level' => $child_level[0]['child_level']+1,
            ]);
            //dd($did);exit;
            $did->save();
            toastr()->success('Category added successfully.');
            return redirect()->route('categoryIndex');
        }
    }
}




















?>