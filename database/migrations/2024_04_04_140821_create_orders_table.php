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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 255);
            $table->string('address', 255);
            $table->string('email', 255);
            $table->string('note', 255);
            $table->string('phone', 255);
            $table->integer('total');
            $table->enum('status', ['pending', 'processing', 'delivering', 'canceled', 'delivered'])->default('pending');
            $table->string('payment_method');
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
