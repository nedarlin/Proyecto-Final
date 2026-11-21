<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionDiagnostico extends Model
{
    protected $table = 'evaluacion_diagnostico';
    protected $primaryKey = 'id_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'id_historia', 'id_cita', 'motivo_consulta', 'hallazgos_clinicos',
        'diagnostico', 'tratamiento_sugerido', 'tomografia_3d',
    ];

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'id_historia', 'id_historia');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'id_cita');
    }
}