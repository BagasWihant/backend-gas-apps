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
        Schema::create('produk_fashion_mains', function (Blueprint $table) {
            $table->string('produk_id',32)->primary();
            $table->string('name');
            $table->text('desc');
            $table->string('kategori',30)->comment('');
            $table->char('gender',1)->comment('L/P');
            $table->char('kondisi',1)->comment('1=BARU 0=BEKAS');
            $table->string('variasi')->comment('variasi1;variasi2');
            $table->string('berat',6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_fashion_mains');
    }
};
