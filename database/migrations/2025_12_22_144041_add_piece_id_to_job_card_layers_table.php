<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_card_layers', function (Blueprint $table) {
            $table->foreignId('piece_id')->nullable()->after('job_card_id')->constrained('job_card_pieces')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('job_card_layers', function (Blueprint $table) {
            $table->dropForeign(['piece_id']);
            $table->dropColumn('piece_id');
        });
    }
};
