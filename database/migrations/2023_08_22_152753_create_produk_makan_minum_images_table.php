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
        Schema::create('produk_makan_minum_images', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id',32)->index();
            $table->string('img',100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_makan_minum_images');
    }
};
