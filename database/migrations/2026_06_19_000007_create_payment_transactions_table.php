<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gateway')->default('midtrans');
            $table->string('order_number')->index();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('gross_amount', 12, 2)->nullable();
            $table->string('fraud_status')->nullable();
            $table->text('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
