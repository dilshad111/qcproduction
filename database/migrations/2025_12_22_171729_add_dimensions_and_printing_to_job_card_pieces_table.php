<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->decimal('length', 10, 2)->nullable()->after('piece_name');
            $table->decimal('width', 10, 2)->nullable()->after('length');
            $table->decimal('height', 10, 2)->nullable()->after('width');
            $table->integer('print_colors')->default(0)->after('ups');
        });
    }

    public function down()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->dropColumn(['length', 'width', 'height', 'print_colors']);
        });
    }
};
