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
        Schema::create('store_details', function (Blueprint $table) {
            $table->string('store_id',12)->primary();
            $table->string('alamat');
            $table->string('phone',15);
            $table->string('email',50);
            $table->string('lon',20)->nullable();
            $table->string('lat',20)->nullable();
            $table->string('back_img')->nullable();
            $table->string('ktp_img');
            $table->string('self_img');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_details');
    }
};
