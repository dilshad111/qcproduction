<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('po_number')->nullable();
            $table->integer('order_qty_cartons');
            $table->integer('required_sheet_qty');
            $table->string('status')->default('Pending'); // Pending, In Production, Completed, Dispatched
            $table->timestamps();
        });

        Schema::create('production_reels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_issue_id')->constrained('job_issues')->onDelete('cascade');
            $table->string('reel_number');
            $table->decimal('weight', 10, 2);
            $table->timestamps();
        });

        Schema::create('production_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_issue_id')->constrained('job_issues')->onDelete('cascade');
            $table->string('process_stage'); // Printing, Slotting, DieCut, QC
            $table->string('status')->default('Pending');
            $table->boolean('qc_approved')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_issue_id')->constrained('job_issues');
            $table->date('dispatch_date');
            $table->integer('qty_dispatched');
            $table->string('vehicle_no')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dispatches');
        Schema::dropIfExists('production_tracking');
        Schema::dropIfExists('production_reels');
        Schema::dropIfExists('job_issues');
    }
}
