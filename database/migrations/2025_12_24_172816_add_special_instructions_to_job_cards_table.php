<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialInstructionsToJobCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->text('corrugation_instruction')->nullable()->after('remarks');
            $table->text('printing_instruction')->nullable()->after('corrugation_instruction');
            $table->text('finishing_instruction')->nullable()->after('printing_instruction');
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
            $table->dropColumn(['corrugation_instruction', 'printing_instruction', 'finishing_instruction']);
        });
    }
}
