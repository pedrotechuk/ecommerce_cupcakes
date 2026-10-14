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
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable();

            $table->string('shipping_street');
            $table->string('shipping_number');
            $table->string('shipping_city');

            $table->enum('payment_method', ['card', 'pix']);
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled',
            ])->default('pending');

            $table->decimal('subtotal', 8, 2);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->decimal('total', 8, 2);

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
