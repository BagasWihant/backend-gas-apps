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
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropColumn('is_user');
        });
        Schema::table('produk_masters', function (Blueprint $table) {
            $table->dropColumn('is_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->char('is_user',1)->comment('1=produk tabel user | 0=produk toko')->nullable();
        });
        Schema::table('produk_masters', function (Blueprint $table) {
            $table->char('is_user',2)->nullable()->comment('99=user | null=toko');
        });
    }
};
