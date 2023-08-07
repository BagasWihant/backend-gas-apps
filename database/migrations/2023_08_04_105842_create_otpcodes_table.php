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
        Schema::create('otpcodes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('otp');
            $table->string('tipe',15);
            $table->char('status',1);
            $table->string('valid',30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otpcodes');
    }
};
