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
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('product_code')->nullable();
            $table->integer('product_owner')->nullable();
            $table->integer('create_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('long_description')->nullable();
            $table->text('image')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('shipping_charge')->nullable();
            $table->text('slug')->nullable();
            $table->string('origin')->nullable();
            $table->tinyInteger('is_featured')->default(0)->nullable();
            $table->tinyInteger('is_trending')->default(0)->nullable();
            $table->tinyInteger('best_selling')->default(0)->nullable();
            $table->tinyInteger('is_popular')->default(0)->nullable();
            $table->tinyInteger('product_status')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('products');
    }
};
