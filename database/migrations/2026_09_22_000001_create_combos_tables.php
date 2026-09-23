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
        // 1. combos table
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('badge')->nullable(); // e.g. "Save 15%", "Mega Deal"
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('price'); // Combo offer price (discounted)
            $table->unsignedInteger('original_price')->nullable(); // Sum of individual items
            $table->integer('stock')->default(100);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        // 2. combo_items table
        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained('combos')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('custom_name')->nullable();
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        // 3. Add combo_id to order_items if not present
        if (Schema::hasTable('order_items') && ! Schema::hasColumn('order_items', 'combo_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('combo_id')->nullable()->after('product_id')->constrained('combos')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'combo_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['combo_id']);
                $table->dropColumn('combo_id');
            });
        }

        Schema::dropIfExists('combo_items');
        Schema::dropIfExists('combos');
    }
};
