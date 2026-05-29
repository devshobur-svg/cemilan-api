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
        // Tabel Ringkasan Transaksi (SUDAH DI-UPGRADE MULTI-PAYMENT)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->integer('total_price');
            
            // PERBAIKAN 1: Tambah kolom metode pembayaran (Default: CASH)
            $table->string('payment_method')->default('CASH'); 
            
            // PERBAIKAN 2: Ubah menjadi nullable() karena TRANSFER & QRIS tidak pakai input cash tunai
            $table->integer('cash_received')->nullable();
            $table->integer('cash_change')->nullable();
            
            // PERBAIKAN 3: Tambah kolom catatan (opsional log info bank transfer atau status referensi)
            $table->string('payment_note')->nullable(); 
            
            $table->timestamps();
        });

        // Tabel Detail Item Jajanan yang Dibeli
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('variant_name');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
};