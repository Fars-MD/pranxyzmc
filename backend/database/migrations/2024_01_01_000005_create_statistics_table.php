<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('total_customer')->default(0);
            $table->integer('total_transaction')->default(0);
            $table->integer('total_product')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
