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
        Schema::create('m_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('m_products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('m_users')->onDelete('restrict');
            $table->enum('type', ['in', 'out'])->comment('in: stock received, out: stock sold');
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->enum('reason', ['purchase', 'return', 'sale', 'adjustment', 'damaged', 'other'])->nullable();
            $table->string('reference_number')->nullable()->comment('PO number, Invoice number, etc.');
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_stock_movements');
    }
};
