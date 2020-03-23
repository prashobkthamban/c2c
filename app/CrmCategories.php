<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmCategories extends Model
{
    protected $table = 'crm_category';
    protected $primaryKey = 'id';
    protected $fillable = ['group_id', 'crm_category_name'];
}
