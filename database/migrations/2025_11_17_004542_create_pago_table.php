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
        Schema::create("pago", function (Blueprint $table) {
            $table->id();
            $table->$table->decimal("monto", 10, 2);
            $table->dateTime("fecha");
            $table
                ->foreignId("ticket_id")
                ->constrained("ticket")
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pago");
    }
};
