<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'cita';
    protected $primaryKey = 'id_cita';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente', 'id_odontologo', 'id_servicio',
        'fecha', 'hora', 'estado', 'motivo_cancelacion',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function odontologo()
    {
        return $this->belongsTo(OdontologoEspecialista::class, 'id_odontologo', 'id_odontologo');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }
}