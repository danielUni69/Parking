<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('placa_formatos', function (Blueprint $table) {
            $table->id();
            $table->string('pais', 50);
            $table->string('code', 5);
            $table->string('regex');
            $table->string('ejemplo')->nullable();
            $table->string('bandera_icon')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('placa_formatos');
    }
};
