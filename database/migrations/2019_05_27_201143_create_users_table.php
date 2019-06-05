<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_name');
            $table->string('coperate_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status');
            $table->tinyInteger('did');
            $table->tinyInteger('multilanguage');
            $table->string('language');
            $table->tinyInteger('record_call');
            $table->integer('operator_call_count');
            $table->string('sms_api_user');
            $table->string('sms_api_password');
            $table->string('sms_api_sender');
            $table->string('api');
            $table->string('cdr_api_key');
            $table->string('client_ip');
            $table->string('cdr_tag');
            $table->string('chanunavil_calls');
            $table->string('conference_members');
            $table->tinyInteger('android_app');
            $table->tinyInteger('portal_sms');
            $table->string('dial_stratergy');
            $table->tinyInteger('sms_support');
            $table->tinyInteger('push_api_service');
            $table->tinyInteger('pbx_extension');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
