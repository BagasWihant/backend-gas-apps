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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_id',12)->unique();
            $table->string('store_name',50);
            $table->string('store_lokasi',50)->comment('lokasi toko')->nullable();
            $table->char('store_type',1)->comment('1 individu | 2 perusahaan');
            $table->char('store_kategori',1)->comment('1 fashion | 2 harian');
            $table->string('foto_profil')->nullable();
            $table->decimal('rating',3,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
