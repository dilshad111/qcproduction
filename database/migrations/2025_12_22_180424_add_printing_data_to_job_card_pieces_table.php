<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->json('printing_data')->nullable()->after('print_colors');
        });
    }

    public function down()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->dropColumn('printing_data');
        });
    }
};
