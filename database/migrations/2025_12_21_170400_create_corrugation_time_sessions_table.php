<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('corrugation_time_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corrugation_log_id')->constrained()->onDelete('cascade');
            $table->dateTime('session_start');
            $table->dateTime('session_end')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('corrugation_time_sessions');
    }
};
