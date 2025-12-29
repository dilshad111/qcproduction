<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlittingCreatingToJobCardPiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->string('slitting_creasing')->nullable()->after('packing_type');
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
            $table->dropColumn('slitting_creasing');
        });
    }
}
