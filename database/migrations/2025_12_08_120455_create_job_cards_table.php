<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();
            $table->string('job_no')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('carton_type_id')->constrained('carton_types');
            
            $table->string('item_name');
            $table->string('item_code')->nullable();
            
            // Dimensions
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            
            $table->decimal('deckle_size', 10, 2)->nullable();
            $table->decimal('sheet_length', 10, 2)->nullable();
            $table->integer('ups')->default(1);
            
            $table->integer('ply_type')->comment('3, 5, 7');
            
            // Printing
            $table->integer('print_colors')->default(0);
            $table->json('printing_data')->nullable(); // Store ink IDs or details
            
            // Pasting
            $table->string('pasting_type')->nullable(); // Glue / Staple
            $table->string('staple_details')->nullable();
            
            // Special
            $table->json('special_details')->nullable(); // Honeycomb / Separator details
            
            // Process
            $table->string('process_type')->nullable(); // Rotary / Die Cut
            $table->integer('packing_bundle_qty')->nullable();
            
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('job_card_layers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards')->onDelete('cascade');
            $table->integer('layer_order');
            $table->string('type'); // Outer, Flute, Inner, Middle
            $table->string('paper_name')->nullable();
            $table->string('gsm')->nullable();
            $table->string('flute_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_card_layers');
        Schema::dropIfExists('job_cards');
    }
}
