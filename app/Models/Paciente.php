<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'paciente';
    protected $primaryKey = 'id_paciente';
    public $timestamps = false;

    protected $fillable = ['nombres', 'apellidos', 'dni', 'correo', 'celular', 'contrasena_hash'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_paciente', 'id_paciente');
    }

    public function historiaClinica()
    {
        return $this->hasOne(HistoriaClinica::class, 'id_paciente', 'id_paciente');
    }
}