<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobNumberSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_number_setups', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->default('JC-'); // e.g., "JC-QC-"
            $table->integer('current_number')->default(0); // e.g., 1
            $table->integer('padding')->default(5); // e.g., 5 for "00001"
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
        Schema::dropIfExists('job_number_setups');
    }
}
