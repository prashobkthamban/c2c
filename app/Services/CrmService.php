<?php

namespace App\Services;

use App\Services\ICrmService;
use App\CrmCategories;
use App\CrmSubCategories;
use App\CrmStatus;
use Carbon;
use DB;
use Auth;
use Config;

class CrmService implements ICrmService
{
    public function getAllCategories()
    {
        return $categories = CrmCategories::all();
    }

    public function getAllCategoriesByStatus($category_status)
    {
        return $categories = CrmCategories::where('crm_category_active', $category_status)->get();
    }

    public function setCategory($group_id, $crm_category_name)
    {
        return CrmCategories::insertGetId([
            'group_id' => $group_id,
            'crm_category_name' => $crm_category_name,
            'crm_category_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function setSubCategory($group_id, $request)
    {
        return CrmSubCategories::insertGetId([
            'group_id' => $group_id,
            'crm_category_id' => $request->crm_category_id,
            'crm_sub_category_name' => $request->crm_sub_category_name,
            'crm_sub_category_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function updateCategoryStatus($category_id, $category_status)
    {
        $crmCategory =  CrmCategories::find($category_id);
        $crmCategory->crm_category_active = $category_status;
        $crmCategory->update();
        return $category_id;
    }

    public function getAllSubCategories()
    {
        return $subCategories = CrmSubCategories::join('crm_category', 'crm_category.id', '=', 'crm_sub_category.crm_category_id')->get();
    }

    public function getAllSubCategoriesByStatus($sub_category_status)
    {
        return $subCategories = CrmSubCategories::select('crm_sub_category.id as subCategoryId', 'crm_sub_category.*', 'crm_category.*')->join('crm_category', 'crm_category.id', '=', 'crm_sub_category.crm_category_id')->where('crm_sub_category_active', $sub_category_status)->get();
    }

    public function updateSubCategoryStatus($sub_category_id, $sub_category_status)
    {
        $crmSubCategory =  CrmSubCategories::find($sub_category_id);
        $crmSubCategory->crm_sub_category_active = $sub_category_status;
        $crmSubCategory->update();
        return $sub_category_id;
    }

    public function getAllCrmStatus()
    {
        return $crmStatus = CrmStatus::all();
    }

    public function getAllCrmStatusByStatus($crm_status)
    {
        return $crmStatus = CrmStatus::where('crm_status_active', $crm_status)->get();
    }

    public function updateCrmStatusStatus($status_id, $crm_status_status)
    {
        $crmStatus =  CrmStatus::find($status_id);
        $crmStatus->crm_status_active = $crm_status_status;
        $crmStatus->update();
        return $status_id;
    }

    public function setCrmStatus($group_id, $crm_status_name)
    {
        return CrmStatus::insertGetId([
            'group_id' => $group_id,
            'crm_status_name' => $crm_status_name,
            'crm_status_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
}
