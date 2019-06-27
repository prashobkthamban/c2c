<?php

namespace App\Services;

use App\Services\ICrmService;
use App\CrmCategories;
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

    public function getAllStatus()
    {
        return $crmStatus = CrmStatus::all();
    }
}
