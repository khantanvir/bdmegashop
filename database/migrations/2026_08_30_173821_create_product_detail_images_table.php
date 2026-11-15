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
        Schema::create('product_detail_images', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->text('image')->nullable();
            $table->integer('create_by')->nullable();
            $table->text('alt')->nullable();
            $table->text('meta')->nullable();
            $table->text('slug')->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('product_detail_images');
    }
};
