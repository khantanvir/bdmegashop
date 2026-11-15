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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('color_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('sku')->nullable();
            $table->string('product_image')->nullable();
            $table->text('attributes')->nullable();//(JSON, e.g. {"size":"M","color":"Red"})
            $table->string('unit_price')->nullable();
            $table->string('original_price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('line_total')->nullable();
            $table->string('order_product_status')->nullable();//active, cancelled, returned, refunded
            $table->string('returned_quantity')->nullable();//full, partial
            $table->text('note')->nullable();
            $table->integer('create_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('order_products');
    }
};
