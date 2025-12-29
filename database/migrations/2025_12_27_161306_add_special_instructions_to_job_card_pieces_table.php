<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialInstructionsToJobCardPiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->text('corrugation_instruction')->nullable();
            $table->text('printing_instruction')->nullable();
            $table->text('finishing_instruction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->dropColumn(['corrugation_instruction', 'printing_instruction', 'finishing_instruction']);
        });
    }
}
