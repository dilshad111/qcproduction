<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMastersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('email')->nullable();
            $table->json('optional_fields')->nullable();
            $table->timestamps();
        });

        Schema::create('carton_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('standard_code')->nullable(); // FEFCO code
            $table->timestamps();
        });

        Schema::create('inks', function (Blueprint $table) {
            $table->id();
            $table->string('color_name');
            $table->string('color_code')->nullable();
            $table->timestamps();
        });

        Schema::create('machine_speeds', function (Blueprint $table) {
            $table->id();
            $table->integer('speed_3ply')->default(0);
            $table->integer('speed_5ply')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('machine_speeds');
        Schema::dropIfExists('inks');
        Schema::dropIfExists('carton_types');
        Schema::dropIfExists('customers');
    }
}
