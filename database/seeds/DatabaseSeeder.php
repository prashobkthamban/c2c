<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(AccountTableSeeder::class);
        $this->call(CrmCategoryTableSeeder::class);
        $this->call(CrmSubCategoryTableSeeder::class);
        $this->call(CrmStatusTableSeeder::class);
    }
}
