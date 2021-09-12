<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterLeadReminderAndInvoiceProposalTcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('terms_condition_invoice', function($table) {
            $table->string('name',2000)->change();
        });

        Schema::table('terms_condition_proposal', function($table) {
            $table->string('name',2000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
