<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_cita', 'id_transaccion', 'monto', 'metodo_pago', 'estado_pago', 'nro_comprobante',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'id_cita');
    }

    public function transaccion()
    {
        return $this->belongsTo(TransaccionPago::class, 'id_transaccion', 'id_transaccion');
    }
}