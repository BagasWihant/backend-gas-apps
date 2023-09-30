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
        Schema::create('order_produk_details', function (Blueprint $table) {
            $table->id();
            $table->string('orderID',32);
            $table->string('sellerID',15);
            $table->string('produkID',32);
            $table->char('table',2);
            $table->decimal('produkWeight');
            $table->integer('produkPrice');
            $table->integer('qty');
            $table->integer('totalPrice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_produk_details');
    }
};
