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
        Schema::create('cat_posts', function (Blueprint $table) {
            $table->id();
            $table->string('cat_post_name',255);
            $table->string('slug',255);
            $table->string('status',255);
            $table->unsignedBigInteger('post_cat_id');
            $table->foreign('post_cat_id')-> references('id')->on('pages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_posts');
    }
};
