<?php

namespace App\Services;

Interface ICrmService
{
    public function getAllCategories();
    public function setCategory($group_id, $crm_category_name);
    public function getAllSubCategories();
    public function getAllStatus();
}
