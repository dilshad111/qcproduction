<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrugationModulesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrugation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_issue_id');
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('total_sheets_produced')->default(0);
            $table->decimal('avg_speed_mpm', 8, 2)->nullable(); // Meters per minute
            $table->timestamps();
            
            $table->foreign('job_issue_id')->references('id')->on('job_issues')->onDelete('cascade');
        });

        Schema::create('downtime_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('corrugation_log_id');
            $table->string('reason'); // Reel Change, Breakdown, etc.
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->timestamps();

            $table->foreign('corrugation_log_id')->references('id')->on('corrugation_logs')->onDelete('cascade');
        });

        Schema::create('wastage_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('corrugation_log_id');
            $table->string('type'); // Paper, Flute, Sheet
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('kg'); // kg, sheets
            $table->unsignedBigInteger('staff_id')->nullable(); // Wasted by
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('corrugation_log_id')->references('id')->on('corrugation_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wastage_logs');
        Schema::dropIfExists('downtime_logs');
        Schema::dropIfExists('corrugation_logs');
    }
}
