<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        $tableName = "espacio";
        $columnName = "estado";

        if ($driver === "mysql" || $driver === "mariadb") {
            $enumNewValues = "'libre', 'ocupado', 'inactivo'";
            DB::statement(
                "ALTER TABLE {$tableName} CHANGE COLUMN {$columnName} {$columnName} ENUM({$enumNewValues}) NOT NULL DEFAULT 'libre'",
            );
        } elseif ($driver === "pgsql") {
            $enumTypeName = $tableName . "_" . $columnName;

            DB::statement(
                "ALTER TYPE {$enumTypeName} ADD VALUE 'inactivo' AFTER 'ocupado'",
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        $tableName = "espacio";
        $columnName = "estado";

        if ($driver === "mysql" || $driver === "mariadb") {
            $enumOldValues = "'libre', 'ocupado'";

            DB::statement(
                "UPDATE {$tableName} SET {$columnName} = 'libre' WHERE {$columnName} = 'inactivo'",
            );
        } elseif ($driver === "pgsql") {
            DB::statement(
                "UPDATE {$tableName} SET {$columnName} = 'libre' WHERE {$columnName} = 'inactivo'",
            );
        }
    }
};
