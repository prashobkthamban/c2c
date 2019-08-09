<?php

use Illuminate\Database\Seeder;

class CrmStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('crm_status')->truncate();
        \App\CrmStatus::create(['crm_status_name' => 'ATTENDED', 'crm_status_active' => 1]);
        \App\CrmStatus::create(['crm_status_name' => 'UNATTENDED', 'crm_status_active' => 1]);
        \App\CrmStatus::create(['crm_status_name' => 'PROSPECTS', 'crm_status_active' => 1]);
        \App\CrmStatus::create(['crm_status_name' => 'DISQUALIFIED', 'crm_status_active' => 1]);
        \App\CrmStatus::create(['crm_status_name' => 'NOT INTERESTED', 'crm_status_active' => 1]);
    }
}
