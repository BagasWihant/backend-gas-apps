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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('orderID',50)->primary();
            $table->string('buyer_id',15);
            $table->bigInteger('alamat_id');
            $table->integer('voucher_id')->nullable();

            $table->integer('total_berat')->comment('Gram');
            $table->string('kode_resi',25);

            $table->char('tipe_pengiriman',1)->comment('0:ambil | 1:antar');
            $table->char('tipe_pembayaran',1);
            $table->char('status_transaksi',1);
            $table->char('status_pembayaran',1);

            $table->integer('total_diskon')->nullable();
            $table->integer('total_ongkir');
            $table->integer('biaya_service');
            $table->integer('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
