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
        Schema::create('transaccion_pago', function (Blueprint $table) {
            $table->increments('id_transaccion');
            $table->string('estado', 20);
        });

        DB::statement("ALTER TABLE transaccion_pago ADD CONSTRAINT chk_transaccion_estado
                    CHECK (estado IN ('Aprobada', 'Rechazada'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccion_pago');
    }
};
