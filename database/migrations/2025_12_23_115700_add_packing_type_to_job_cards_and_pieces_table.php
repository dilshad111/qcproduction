<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackingTypeToJobCardsAndPiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->string('packing_type')->nullable()->after('packing_bundle_qty');
        });

        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->string('packing_type')->nullable()->after('packing_bundle_qty');
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_cards', function (Blueprint $table) {
            $table->dropColumn('packing_type');
        });

        Schema::table('job_card_pieces', function (Blueprint $table) {
            $table->dropColumn('packing_type');
        });
    }
}
