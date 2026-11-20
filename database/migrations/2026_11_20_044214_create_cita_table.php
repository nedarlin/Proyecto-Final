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
        Schema::create('cita', function (Blueprint $table) {
            $table->increments('id_cita');
            $table->unsignedInteger('id_paciente');
            $table->unsignedInteger('id_odontologo');
            $table->unsignedInteger('id_servicio');
            $table->date('fecha');
            $table->time('hora');
            $table->string('estado', 20)->default('Programada');
            $table->string('motivo_cancelacion', 150)->nullable();

            $table->foreign('id_paciente')->references('id_paciente')->on('paciente')->restrictOnDelete();
            $table->foreign('id_odontologo')->references('id_odontologo')->on('odontologo_especialista')->restrictOnDelete();
            $table->foreign('id_servicio')->references('id_servicio')->on('servicio')->restrictOnDelete();

            $table->unique(['id_odontologo', 'fecha', 'hora']);
            $table->index(['fecha', 'id_odontologo'], 'idx_cita_fecha');
        });

        // CHECK del documento: solo estos 4 valores son válidos
        DB::statement("ALTER TABLE cita ADD CONSTRAINT chk_cita_estado
                    CHECK (estado IN ('Programada', 'En atención', 'Atendida', 'Cancelada'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cita');
    }
};
