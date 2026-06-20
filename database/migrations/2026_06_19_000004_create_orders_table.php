<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('destination_id')->nullable();
            $table->string('destination_label')->nullable();
            $table->unsignedInteger('shipping_weight')->default(1000);
            $table->string('courier_code')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('courier_service')->nullable();
            $table->string('courier_etd')->nullable();
            $table->string('payment_method');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_token')->nullable();
            $table->string('payment_redirect_url')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('gateway_payload')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
