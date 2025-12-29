<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->integer('pieces_count')->default(1)->after('carton_type_id');
        });
    }

    public function down()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->dropColumn('pieces_count');
        });
    }
};
