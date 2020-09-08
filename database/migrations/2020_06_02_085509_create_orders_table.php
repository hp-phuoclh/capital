<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_id');
            $table->unsignedInteger('shipping_option_id');
            $table->text('note')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_type')->nullable();
            $table->boolean('paid_flg')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->integer('total');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('address_id')->references('id')->on('address');
            $table->foreign('shipping_option_id')->references('id')->on('shipping_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            Schema::dropIfExists('orders');
        });
    }
}