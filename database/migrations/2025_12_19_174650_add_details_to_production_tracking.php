<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToProductionTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_tracking', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->date('date')->nullable();
            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('set null');
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
            //
        });
    }
}
