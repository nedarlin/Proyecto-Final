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
        Schema::create('paciente', function (Blueprint $table) {
            $table->increments('id_paciente');
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->char('dni', 8)->unique();
            $table->string('correo', 100)->unique();
            $table->char('celular', 9);
            $table->string('contrasena_hash', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente');
    }
};
