<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ICrmService;
use App\CrmCategories;
use App\CrmSubCategories;
use Illuminate\Support\Facades\Validator;
use Auth;
class CrmController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ICrmService $crmService)
    {
        $this->crmService = $crmService;
    }

    public function index() {
    }

    /**
     * Show crm category list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categoryList()
    {        
        $categories = $this->crmService->getAllCategoriesByStatus(1);
        return view('crm.categorylist')->with('crmCategories', $categories);
    }

    public function categoryadd(Request $request)
    {
        if($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'crm_category_name' => 'required',
            ]);
            if($validator->fails()) {
                $messages = $validator->messages(); 
                return view('crm.categoryadd', compact('messages'));
            } else {
                $groupId = Auth::user()->groupid;
                $categoryId = $this->crmService->setCategory($groupId, $request->crm_category_name);
                if($categoryId) {
                    toastr()->success('Crm Category added successfully.');
                    return redirect()->route('category-list');
                }
            }
        }
       return view('crm.categoryadd');
    }
    
    public function categoryedit($categoryId)
    {
        $crmCategory = CrmCategories::find($categoryId);
        return view('crm.categoryedit', compact('crmCategory'));   
    }

    public function categoryupdate($id, Request $request)
    {
        $crmCategory = new CrmCategories();
       
        $editcategory =  CrmCategories::find($id);
       
        $validator = Validator::make($request->all(), [
           'crm_category_name' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            return view('crm.categoryedit', compact('messages','editcategory'));
        } else {
            $crmCategory = [
                'crm_category_name' => $request->get('crm_category_name'),
            ];
            $editcategory->fill($crmCategory)->save();
            toastr()->success('Category update successfully.');
             return redirect()->route('category-list');
        }
        
    }

    public function categorydelete($categoryId)
    {
       $categoryDelete = $this->crmService->updateCategoryStatus($categoryId, 0);
       if($categoryDelete) {
            toastr()->success('Crm Category deleted successfully.');
            return redirect()->route('category-list');
        }
    }

    /**
     * Show crm sub category list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subCategoryList()
    {
        $subCategories = $this->crmService->getAllSubCategoriesByStatus(1);
        return view('crm.subcategorylist')->with('crmSubCategories', $subCategories);
    }
    
    
    public function subcategoryadd(Request $request)
    {
        $crmCategory = CrmCategories :: where('crm_category_active',1)->pluck('crm_category_name','id');
        if($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'crm_sub_category_name' => 'required',
                'crm_category_id' => 'required',
            ]);
            if($validator->fails()) {
                $messages = $validator->messages(); 
                return view('crm.subcategoryadd', compact('messages'));
            } else {
                $groupId = Auth::user()->groupid;
                $categoryId = $this->crmService->setSubCategory($groupId, $request);
                if($categoryId) {
                    toastr()->success('Crm subCategory added successfully.');
                    return redirect()->route('sub-category-list');
                }
            }
        }
       return view('crm.subcategoryadd',compact('crmCategory'));
    }
    
    public function subcategoryedit($categoryId)
    {
        $crmCategory = CrmCategories :: where('crm_category_active',1)->pluck('crm_category_name','id');
        $crmSubCategory = CrmSubCategories::find($categoryId);
        return view('crm.subcategoryedit', compact('crmCategory','crmSubCategory'));   
    }

    public function subcategoryupdate($id, Request $request)
    {
        $crmCategory = CrmCategories :: where('crm_category_active',1)->pluck('crm_category_name','id');
        $crmSubCategory = new CrmSubCategories();
        $editsubcategory =  CrmSubCategories::find($id);
       
        $validator = Validator::make($request->all(), [
            'crm_sub_category_name' => 'required',
            'crm_category_id' => 'required',
        ]);

        if($validator->fails()) {
            $messages = $validator->messages();
            return view('crm.categoryedit', compact('messages','crmCategory','editcategory'));
        } else {
            $crmSubCategory = [
                'crm_category_id' => $request->get('crm_category_id'),
                'crm_sub_category_name' => $request->get('crm_sub_category_name'),
            ];
            $editsubcategory->fill($crmSubCategory)->save();
            toastr()->success('Sub Category update successfully.');
             return redirect()->route('sub-category-list');
        }
        
    }


    public function subcategorydelete($subCategoryId)
    {
       $categoryDelete = $this->crmService->updateSubCategoryStatus($subCategoryId, 0);
       if($categoryDelete) {
            toastr()->success('Crm Sub Category deleted successfully.');
            return redirect()->route('sub-category-list');
        }
    }

    /**
     * Show crm status list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusList()
    {
        $crmStatus = $this->crmService->getAllCrmStatusByStatus(1);
        return view('crm.statuslist')->with('crmStatus', $crmStatus);
    }

    public function statusadd(Request $request)
    {
        if($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'crm_status_name' => 'required',
            ]);
            if($validator->fails()) {
                $messages = $validator->messages(); 
                return view('crm.statusadd', compact('messages'));
            } else {
                $groupId = Auth::user()->groupid;
                $categoryId = $this->crmService->setCrmStatus($groupId, $request->crm_status_name);
                if($categoryId) {
                    toastr()->success('Crm Status added successfully.');
                    return redirect()->route('status-list');
                }
            }
        }
       return view('crm.statusadd');
    }

    public function statusdelete($statusId)
    {
       $categoryDelete = $this->crmService->updateCrmStatusStatus($statusId, 0);
       if($categoryDelete) {
            toastr()->success('Crm Status deleted successfully.');
            return redirect()->route('status-list');
        }
    }
}
