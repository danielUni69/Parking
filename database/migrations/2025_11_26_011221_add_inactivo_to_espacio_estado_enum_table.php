<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();
        $tableName = "espacio";
        $columnName = "estado";

        if ($driver === "mysql" || $driver === "mariadb") {
            // ... (Tu código de MySQL está bien) ...
            $enumNewValues = "'libre', 'ocupado', 'inactivo'";
            DB::statement(
                "ALTER TABLE {$tableName} CHANGE COLUMN {$columnName} {$columnName} ENUM({$enumNewValues}) NOT NULL DEFAULT 'libre'",
            );
        } elseif ($driver === "pgsql") {
            // SOLUCIÓN PARA POSTGRESQL:
            // 1. Eliminamos el constraint viejo que solo permite "libre" y "ocupado"
            DB::statement(
                "ALTER TABLE {$tableName} DROP CONSTRAINT IF EXISTS {$tableName}_{$columnName}_check",
            );

            // 2. Creamos un nuevo constraint que incluye "inactivo"
            DB::statement(
                "ALTER TABLE {$tableName} ADD CONSTRAINT {$tableName}_{$columnName}_check CHECK ({$columnName}::text = ANY (ARRAY['libre'::text, 'ocupado'::text, 'inactivo'::text]))",
            );
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        $tableName = "espacio";
        $columnName = "estado";

        // Primero migramos los datos para que no haya error de inconsistencia
        DB::table($tableName)
            ->where($columnName, "inactivo")
            ->update([$columnName => "libre"]);

        if ($driver === "mysql" || $driver === "mariadb") {
            // ... (Tu código de MySQL está bien) ...
            $enumOldValues = "'libre', 'ocupado'";
            DB::statement(
                "ALTER TABLE {$tableName} CHANGE COLUMN {$columnName} {$columnName} ENUM({$enumOldValues}) NOT NULL DEFAULT 'libre'",
            );
        } elseif ($driver === "pgsql") {
            // REVERTIR EN POSTGRESQL:
            // 1. Borramos el constraint nuevo
            DB::statement(
                "ALTER TABLE {$tableName} DROP CONSTRAINT IF EXISTS {$tableName}_{$columnName}_check",
            );

            // 2. Regresamos al constraint original
            DB::statement(
                "ALTER TABLE {$tableName} ADD CONSTRAINT {$tableName}_{$columnName}_check CHECK ({$columnName}::text = ANY (ARRAY['libre'::text, 'ocupado'::text]))",
            );
        }
    }
};
