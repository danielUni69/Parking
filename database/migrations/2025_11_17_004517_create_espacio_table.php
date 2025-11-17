<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("espacio", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("piso_id")
                ->constrained("pisos")
                ->cascadeOnDelete();
            $table
                ->foreignId("tipo_espacio_id")
                ->constrained("tipo_espacios")
                ->cascadeOnDelete();
            $table->string("codigo", 20); // P1-03
            $table->enum("estado", ["libre", "ocupado"])->default("libre");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("espacio");
    }
};
