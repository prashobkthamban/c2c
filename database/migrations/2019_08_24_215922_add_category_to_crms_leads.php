<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryToCrmsLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('crm_leads', function($table) {
            $table->integer('category_id');
            $table->integer('sub_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_leads', function($table) {
            $table->dropColumn('category_id');
            $table->dropColumn('sub_category_id');
        });
    }
}
