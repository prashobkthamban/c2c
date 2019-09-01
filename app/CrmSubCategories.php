<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmSubCategories extends Model
{
    protected $table = 'crm_sub_category';
    protected $primaryKey = 'id';    
    protected $fillable = ['group_id', 'crm_sub_category_name', 'crm_category_id'];
}
