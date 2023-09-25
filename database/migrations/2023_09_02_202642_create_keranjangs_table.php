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
            $table->string('user_id',12)->index();
            $table->string('seller_id',12);
            $table->char('is_user',1)->comment('1=produk tabel user | 0=produk toko')->nullable();
            $table->string('produk_id',32);
            $table->char('table_id',2)->comment('posisi produk tabel');
            // $table->string('var_1',30)->nullable();
            // $table->string('var_2',30)->nullable();
            $table->bigInteger('var_id')->nullable();
            $table->integer('harga_diskon')->nullable()->comment('hanya tampilan');
            $table->integer('harga');
            $table->string('catatan')->nullable();
            $table->integer('qty')->default(1);
            $table->timestamps();
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
