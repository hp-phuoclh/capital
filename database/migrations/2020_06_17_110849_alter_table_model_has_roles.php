<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableModelHasRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Doctrine\DBAL\Types\Type::hasType("uuid")) {
            \Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        Schema::table('model_has_roles', function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid($columnNames['model_morph_key'])->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->uuid($columnNames['model_morph_key'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!\Doctrine\DBAL\Types\Type::hasType("uuid")) {
            \Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        Schema::table('model_has_roles', function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->bigIncrements($columnNames['model_morph_key'])->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->bigIncrements($columnNames['model_morph_key'])->change();
        });
    }
}
