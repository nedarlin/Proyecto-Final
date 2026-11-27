<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrador', function (Blueprint $table) {
            $table->increments('id_administrador');
            $table->string('nombres', 80);
            $table->string('correo', 100)->unique();
            $table->string('contrasena_hash', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrador');
    }
};
