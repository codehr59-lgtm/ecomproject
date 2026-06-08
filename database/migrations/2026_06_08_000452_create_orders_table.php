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
            $table->string('number')->unique();
            $table->foreignId('user_id')->nullable()->nullOnDelete()->constrained('users');
            $table->string('status')->default('pending');       // pending|confirmed|processing|shipped|delivered|cancelled
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('address_line');
            $table->string('city');
            $table->string('thana')->nullable();
            $table->text('notes')->nullable();
            $table->integer('subtotal');
            $table->integer('delivery');
            $table->integer('discount')->default(0);
            $table->integer('total');
            $table->string('coupon_code')->nullable();
            $table->string('payment_method');                   // cod|sslcommerz|bkash|nagad|rocket
            $table->string('payment_status')->default('unpaid'); // unpaid|paid|failed
            $table->string('courier')->nullable();
            $table->string('courier_tracking')->nullable();
            $table->timestamp('placed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
