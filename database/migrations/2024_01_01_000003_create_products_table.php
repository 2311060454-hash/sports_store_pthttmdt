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
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->string('name')->index();
            $table->string('slug')->unique()->index();
            $table->string('sku')->unique()->index();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->boolean('featured')->default(false)->index();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sold')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
