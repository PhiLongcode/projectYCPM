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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_title',255);
            $table->string('content_post',5000);
            $table->string('thumbnail',255);
            $table->string('slug',255);
            $table->string('status',255);
            $table->string('post_cat',255);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
    }
};
