<?php

namespace App\Services;

Interface ICrmService
{
    public function getAllCategories();
    public function getAllCategoriesByStatus($category_status);
    public function setCategory($group_id, $crm_category_name);
    public function updateCategoryStatus($category_id, $category_status);
    public function getAllSubCategories();
    public function getAllSubCategoriesByStatus($sub_category_status);
    public function updateSubCategoryStatus($sub_category_id, $sub_category_status);
    public function getAllCrmStatus();
    public function getAllCrmStatusByStatus($crm_status);
    public function setCrmStatus($group_id, $crm_status_name);
    public function updateCrmStatusStatus($status_id, $crm_status_status);
}
