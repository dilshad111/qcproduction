<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForCorrugationUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrugation_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id_2')->nullable()->after('machine_id');
        });

        Schema::table('production_reels', function (Blueprint $table) {
            $table->string('usage_type')->nullable()->after('reel_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrugation_logs', function (Blueprint $table) {
            $table->dropColumn('machine_id_2');
        });

        Schema::table('production_reels', function (Blueprint $table) {
            $table->dropColumn('usage_type');
        });
    }
}
