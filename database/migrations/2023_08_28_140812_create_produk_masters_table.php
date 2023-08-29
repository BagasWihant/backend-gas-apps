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
        Schema::create('produk_masters', function (Blueprint $table) {
            $table->string('produk_id', 32)->primary();
            $table->string('name');
            $table->float('rating', 3, 1)->default(0);
            $table->string('img', 50);
            $table->integer('harga');
            $table->integer('diskon_harga')->nullable();
            $table->integer('terjual')->nullable();
            $table->string('key_filter')->fulltext('key');
            $table->timestamps();


            $table->index(['name','key_filter','terjual']);
            $table->index(['name','key_filter','harga']);
            $table->index(['name','key_filter','created_at']);
            $table->index(['name','key_filter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_masters');
    }
};