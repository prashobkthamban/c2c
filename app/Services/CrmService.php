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

    public function getAllSubCategories()
    {
        return $subCategories = CrmSubCategories::join('crm_category', 'crm_category.id', '=', 'crm_sub_category.crm_category_id')->get();
    }

    public function getAllStatus()
    {
        return $crmStatus = CrmStatus::all();
    }
}
