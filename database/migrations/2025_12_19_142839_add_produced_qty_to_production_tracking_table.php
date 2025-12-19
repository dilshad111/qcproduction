<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProducedQtyToProductionTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_tracking', function (Blueprint $table) {
            $table->integer('produced_qty')->default(0)->after('process_stage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_tracking', function (Blueprint $table) {
            $table->dropColumn('produced_qty');
        });
    }
}
