<?php

use Illuminate\Database\Seeder;

class CrmCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\DB::table('crm_category')->truncate();
        \App\CrmCategories::create(['crm_category_name' => 'RESIDENTIAL', 'crm_category_active' => 1]);
        \App\CrmCategories::create(['crm_category_name' => 'COMMERCIAL', 'crm_category_active' => 1]);
        \App\CrmCategories::create(['crm_category_name' => 'SCHEME PLOTTING', 'crm_category_active' => 1]);
    }
}
