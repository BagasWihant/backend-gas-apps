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
        Schema::create('produk_kebutuhan_pokok_variasis', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id',32);
            $table->string('var_1',30)->nullable();
            $table->string('var_2',30)->nullable();
            $table->integer('harga');
            $table->integer('stok');

            $table->index(['produk_id','var_1']);
            $table->index(['produk_id','var_2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_kebutuhan_pokok_variasis');
    }
};
