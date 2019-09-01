<?php

use Illuminate\Database\Seeder;

class CrmSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('crm_sub_category')->truncate();
        \App\CrmSubCategories::create(['group_id'=>1 ,'crm_category_id' => 1 ,'crm_sub_category_name' => 'SUN FLOWER PROJECT', 'crm_sub_category_active' => 1]);
        \App\CrmSubCategories::create(['group_id'=>1 ,'crm_category_id' => 1 ,'crm_sub_category_name' => 'SWATI RESIDENTIAL', 'crm_sub_category_active' => 1]);
    }
}
