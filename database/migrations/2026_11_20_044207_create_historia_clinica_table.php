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
        Schema::create('historia_clinica', function (Blueprint $table) {
            $table->increments('id_historia');
            $table->unsignedInteger('id_paciente')->unique();
            $table->string('numero_hc', 12)->unique();
            $table->date('fecha_apertura');
            $table->text('observaciones')->nullable();

            $table->foreign('id_paciente')->references('id_paciente')->on('paciente')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_clinica');
    }
};
