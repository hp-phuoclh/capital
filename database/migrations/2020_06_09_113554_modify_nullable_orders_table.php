<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNullableOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->unsignedInteger('province_id')->nullable()->change();
            $table->unsignedInteger('district_id')->nullable()->change();
            $table->unsignedInteger('ward_id')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->string('full_name')->change();
            $table->string('phone')->change();
            $table->unsignedInteger('province_id')->change();
            $table->unsignedInteger('district_id')->change();
            $table->unsignedInteger('ward_id')->change();
            $table->string('address')->change();
        });
    }
}
