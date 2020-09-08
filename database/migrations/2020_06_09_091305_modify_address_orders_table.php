<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddressOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');
            $table->string('address')->nullable()->after('shipping_option_id');;
            $table->string('full_name')->nullable()->after('address');;
            $table->string('phone')->nullable()->after('full_name');;

            $table->unsignedInteger('shipping_option_id')->nullable()->change();
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
            $table->dropColumn('full_name');
            $table->dropColumn('phone');

            $table->dropColumn('address');
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('address');

            $table->unsignedInteger('shipping_option_id')->change();
        });
    }
}
