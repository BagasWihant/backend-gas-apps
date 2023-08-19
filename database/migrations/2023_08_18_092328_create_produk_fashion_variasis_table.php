<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'mysql_market';

    public function up(): void
    {
        Schema::create('produk_fashion_variasis', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id',32)->unique();
            $table->string('var_1',30)->nullable();
            $table->string('var_2',30)->nullable();
            $table->int('harga',11);
            $table->int('stok',11);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_fashion_variasis');
    }
};
