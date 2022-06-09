<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceProposalDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_details', function($table) {
            $table->tinyInteger('discount_rate')->default(0);
            $table->float('discount_amount')->default(0);
        });

        Schema::table('proposal_details', function($table) {
            $table->tinyInteger('discount_rate')->default(0);
            $table->float('discount_amount')->default(0);
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
