<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'mysql_order';

    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->string('order_id',50)->primary();

            $table->string('produk_id',32);
            $table->char('table',2);
            $table->integer('produk_weight')->comment('Gram');
            $table->integer('produk_price');
            $table->integer('qty');
            $table->integer('total_price');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
