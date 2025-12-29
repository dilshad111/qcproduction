<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVersioningToJobCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('job_no');
            $table->boolean('is_active')->default(true)->after('version');
            $table->text('revision_note')->nullable()->after('finishing_instruction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->dropColumn(['version', 'is_active', 'revision_note']);
        });
    }
}
