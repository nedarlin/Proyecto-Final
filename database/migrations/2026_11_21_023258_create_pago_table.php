<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->increments('id_pago');
            $table->unsignedInteger('id_cita')->unique();
            $table->unsignedInteger('id_transaccion');
            $table->decimal('monto', 8, 2);
            $table->string('metodo_pago', 20);
            $table->string('estado_pago', 20)->default('Pagado');
            $table->string('nro_comprobante', 20)->unique();

            $table->foreign('id_cita')->references('id_cita')->on('cita')->restrictOnDelete();
            $table->foreign('id_transaccion')->references('id_transaccion')->on('transaccion_pago')->restrictOnDelete();
        });

        DB::statement("ALTER TABLE pago ADD CONSTRAINT chk_pago_monto CHECK (monto > 0)");
        DB::statement("ALTER TABLE pago ADD CONSTRAINT chk_pago_estado
                    CHECK (estado_pago IN ('Pendiente', 'En validación', 'Pagado', 'Rechazado'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
