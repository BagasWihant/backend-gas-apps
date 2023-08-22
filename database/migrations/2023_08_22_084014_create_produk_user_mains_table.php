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
        Schema::create('produk_user_mains', function (Blueprint $table) {
            $table->string('produk_id',32)->primary();
            $table->string('user_id_market',12)->index();
            $table->char('jenis',1)->comment('1 fashion | 2 harian');

            $table->string('name');
            $table->text('desc');
            $table->string('kategori',30)->comment('');
            $table->char('gender',1)->nullable();
            $table->char('kondisi',1)->nullable();
            $table->date('expired')->nullable();
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
        Schema::dropIfExists('produk_user_mains');
    }
};
