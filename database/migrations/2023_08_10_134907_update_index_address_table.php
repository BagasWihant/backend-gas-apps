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
        Schema::table('addresses', function (Blueprint $table) {
            $table->index('user_id_market');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex('addresses_user_id_market_index');
        });
        //
    }
};