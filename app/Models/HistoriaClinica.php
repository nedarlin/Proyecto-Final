<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    protected $table = 'historia_clinica';
    protected $primaryKey = 'id_historia';
    public $timestamps = false;

    protected $fillable = ['id_paciente', 'numero_hc', 'fecha_apertura', 'observaciones'];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }
    public function evaluaciones()
    {
        return $this->hasMany(EvaluacionDiagnostico::class, 'id_historia', 'id_historia');
    }
}