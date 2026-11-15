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
            $table->string('order_number')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_status')->nullable();//unpaid, paid, partially_paid, failed, refunded
            $table->string('payment_method')->nullable();//cod, card, bkash, bank_transfer, etc.
            $table->string('transaction_id')->nullable();
            $table->integer('order_processing_by')->nullable();//user id who process the order
            $table->string('processing_percentage')->default(25)->nullable();
            $table->string('processing_amount')->nullable();//benefit amount (percent selling_price-purchase_price) * .35
            $table->string('is_paid_processing_amount')->default('No')->nullable();//to person is amount paid or not
            $table->integer('referral_user_id')->nullable();//who refers the project
            $table->string('referral_percentage')->nullable();
            $table->string('referral_amount')->nullable();
            $table->string('is_referral_amount_paid')->default('No')->nullable();//Yes, No
            $table->string('currency')->default('BDT')->nullable();
            $table->string('bkash_number')->nullable();
            $table->string('bkash_reference_number')->nullable();
            $table->string('delivery_charge')->nullable();
            $table->string('delivery_charge_pay_by')->nullable();//bkash,bank,cash
            $table->string('delivery_charge_reference_number')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('total')->nullable();
            $table->string('shipping_method')->nullable();//pathao,steadfast,sundarban,myself,other
            $table->string('shipping_reference_number')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('order_status')->nullable();//pending,Handed to Courier, On the Way, delivered
            $table->text('note')->nullable();
            $table->text('admin_note')->nullable();
            $table->integer('create_by')->nullable();
            $table->integer('update_by')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('orders');
    }
};
