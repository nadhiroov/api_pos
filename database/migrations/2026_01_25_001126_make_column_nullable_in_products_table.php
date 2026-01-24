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
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit', 20)->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('barcode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit', 20)->nullable(false)->change();
            $table->string('image')->default(null)->change();
            $table->text('description')->default(null)->change();
            $table->string('barcode')->default(null)->change();
        });
    }
};
