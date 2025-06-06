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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->text('desc');
            $table->double('price');
            $table->integer('discount');
            $table->text('detail');
            $table->string('images', 500);
            $table->string('thumbnail', 500);
            $table->enum('status',['pending', 'public']);
            $table->string('slug', 255);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('category_products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
