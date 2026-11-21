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
        Schema::create('evaluacion_diagnostico', function (Blueprint $table) {
            $table->increments('id_evaluacion');
            $table->unsignedInteger('id_historia');
            $table->unsignedInteger('id_cita')->unique();
            $table->string('motivo_consulta', 200);
            $table->text('hallazgos_clinicos')->nullable();
            $table->text('diagnostico');
            $table->string('tratamiento_sugerido', 200)->nullable();
            $table->string('tomografia_3d', 255)->nullable();

            $table->foreign('id_historia')->references('id_historia')->on('historia_clinica')->restrictOnDelete();
            $table->foreign('id_cita')->references('id_cita')->on('cita')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion_diagnostico');
    }
};
