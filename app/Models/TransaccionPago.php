<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaccionPago extends Model
{
    protected $table = 'transaccion_pago';
    protected $primaryKey = 'id_transaccion';
    public $timestamps = false;

    protected $fillable = ['estado'];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_transaccion', 'id_transaccion');
    }
}