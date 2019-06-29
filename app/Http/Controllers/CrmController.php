<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ICrmService;
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
