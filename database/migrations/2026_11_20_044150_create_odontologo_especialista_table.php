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
        Schema::create('odontologo_especialista', function (Blueprint $table) {
            $table->increments('id_odontologo');
            $table->string('nombres', 80);
            $table->string('colegiatura', 10)->unique();
            $table->string('especialidad', 50);
            $table->string('correo', 100)->unique();
            $table->string('contrasena_hash', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odontologo_especialista');
    }
};
