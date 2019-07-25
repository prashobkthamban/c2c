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

    public function deleteCategory($category_id)
    {
        return CrmCategories::destroy($category_id);
    }

    public function getAllSubCategories()
    {
        return $subCategories = CrmSubCategories::join('crm_category', 'crm_category.id', '=', 'crm_sub_category.crm_category_id')->get();
    }

    public function getAllStatus()
    {
        return $crmStatus = CrmStatus::all();
    }
}
