<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who made the movement
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer']);
            $table->integer('quantity'); // positive for in, negative for out
            $table->integer('quantity_before'); // stock level before movement
            $table->integer('quantity_after'); // stock level after movement
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->string('reference_number')->nullable(); // PO number, invoice, etc.
            $table->string('reason')->nullable(); // reason for movement
            $table->text('notes')->nullable();
            $table->timestamp('movement_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
