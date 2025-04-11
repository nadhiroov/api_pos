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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('branch_id')->constrained();
            $table->string('name')->nullable(false);
            $table->string('sku', 30)->nullable(false)->unique();
            $table->string('unit', 20)->nullable(true);
            $table->string('image')->default(null);
            $table->text('description')->default(null);
            $table->decimal('price', 15, 2)->nullable(false);
            $table->integer('stock')->default(0);
            $table->string('barcode')->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
