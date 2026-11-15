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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('price')->nullable();
            $table->string('discount')->nullable();
            $table->string('selling_price')->nullable();
            $table->string('shipping_charge')->nullable();
            $table->string('total_stock_in')->nullable();
            $table->string('total_stock_out')->nullable();
            $table->text('stock_note')->nullable();
            $table->integer('create_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('product_details');
    }
};
