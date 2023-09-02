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
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id',12);
            $table->string('seller_id',12);
            $table->char('is_user',1)->comment('1=produk oleh user')->nullable();
            $table->string('produk_id',32);
            $table->char('table_id',1)->comment('posisi produk tabel');
            $table->bigInteger('variasi_id');
            $table->integer('harga_diskon');
            $table->integer('harga');
            $table->string('catatan')->nullable();
            $table->integer('qty')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjangs');
    }
};
