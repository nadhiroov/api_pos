<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 1) Drop index unik lama pada sku
            $table->dropUnique('products_sku_unique');
            // 2) Buat index unik gabungan branch_id + sku
            $table->unique(['branch_id', 'sku'], 'products_branch_sku_unique');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 1) Drop index gabungan
            $table->dropUnique('products_branch_sku_unique');
            // 2) Kembalikan unique hanya pada sku
            $table->unique('sku', 'products_sku_unique');
        });
    }
};
