<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OdontologoEspecialista extends Model
{
    protected $table = 'odontologo_especialista';
    protected $primaryKey = 'id_odontologo';
    public $timestamps = false;

    protected $fillable = ['nombres', 'colegiatura', 'especialidad', 'correo', 'contrasena_hash'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_odontologo', 'id_odontologo');
    }
}