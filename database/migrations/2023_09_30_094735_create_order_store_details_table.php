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
        Schema::create('order_store_details', function (Blueprint $table) {
            $table->string('orderID',32);
            $table->string('sellerID',15);
            $table->integer('ongkir');
            $table->string('catatan',100);
            $table->integer('diskon')->default(0);

            $table->primary(['orderID','sellerID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_store_details');
    }
};
