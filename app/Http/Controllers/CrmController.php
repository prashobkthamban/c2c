<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ICrmService;
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
        $categories = $this->crmService->getAllCategories();
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

    /**
     * Show crm sub category list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subCategoryList()
    {
        $subCategories = $this->crmService->getAllSubCategories();
        return view('crm.subcategorylist')->with('crmSubCategories', $subCategories);
    }

    /**
     * Show crm status list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function statusList()
    {
        $crmStatus = $this->crmService->getAllStatus();
        return view('crm.statuslist')->with('crmStatus', $crmStatus);
    }
}
