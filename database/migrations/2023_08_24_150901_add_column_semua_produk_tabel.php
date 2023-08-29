<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produk_buah_sayur_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_bumbu_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_fashion_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_kebutuhan_pokok_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_kosmetik_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_makan_minum_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_mandi_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });

        Schema::table('produk_user_mains', function (Blueprint $table) {
            $table->float('rating',3,1)->default(0)->after('berat');
        });
    }

    /**
     * Reverse the migrations.
     */
    protected $connection = 'mysql_market';
    public function down(): void
    {
        Schema::table('produk_buah_sayur_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_bumbu_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_fashion_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_kebutuhan_pokok_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_kosmetik_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_makan_minum_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_mandi_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });

        Schema::table('produk_user_mains', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};
