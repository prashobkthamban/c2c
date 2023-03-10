<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_sub_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('crm_category_id')->unsigned();
            $table->string('crm_sub_category_name');
            $table->integer('crm_sub_category_active')->default(1);
            $table->timestamps();
            $table->foreign('crm_category_id')->references('id')->on('crm_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_sub_category');
    }
}
