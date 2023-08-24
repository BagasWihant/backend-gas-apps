<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'mysql_market';

    public function up(): void
    {
        Schema::table('produk_buah_sayur_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_bumbu_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_fashion_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_kebutuhan_pokok_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_kosmetik_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_makan_minum_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_mandi_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });

        Schema::table('produk_user_mains', function (Blueprint $table) {
            $table->renameColumn('desc', 'deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_buah_sayur_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_bumbu_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_fashion_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_kebutuhan_pokok_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_kosmetik_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_makan_minum_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_mandi_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });

        Schema::table('produk_user_mains', function (Blueprint $table) {
            $table->renameColumn('deskripsi','desc');
        });
    }
};
