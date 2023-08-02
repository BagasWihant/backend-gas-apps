<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_market';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_markets', function (Blueprint $table) {
            $table->bigInteger('user_id_main');
            $table->string('user_id_market',12)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_markets');
    }
};
