<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_issue_number_setups', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->default('JI-');
            $table->integer('current_number')->default(0);
            $table->integer('padding')->default(5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_issue_number_setups');
    }
};
