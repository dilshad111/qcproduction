<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_card_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards')->onDelete('cascade');
            $table->integer('piece_number');
            $table->string('piece_name')->nullable(); // e.g., "Lid", "Base", "Piece 1"
            $table->decimal('deckle_size', 10, 2)->nullable();
            $table->decimal('sheet_length', 10, 2)->nullable();
            $table->integer('ply_type')->nullable()->comment('3, 5, 7');
            $table->string('die_sketch_path')->nullable();
            $table->timestamps();
            
            // Ensure unique piece numbers per job card
            $table->unique(['job_card_id', 'piece_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_card_pieces');
    }
};
