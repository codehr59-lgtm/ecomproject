<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('weight');
            $table->integer('price');
            $table->integer('old_price')->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('badge')->nullable(); // best|new|preorder
            $table->decimal('rating', 2, 1)->default(4.8);
            $table->integer('reviews')->default(120);
            $table->text('blurb')->nullable();
            $table->boolean('certified')->default(false);
            $table->integer('stock')->default(100);
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
