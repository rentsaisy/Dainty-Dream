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
        Schema::create('t_incoming_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('m_products')->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained('m_suppliers')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('m_users')->onDelete('restrict');
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index('supplier_id');
            $table->index('user_id');
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_incoming_transactions');
    }
};
