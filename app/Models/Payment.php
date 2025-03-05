<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = [
        'student_id',
        'invoice_id',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'estado',
    ];
    protected $casts = [
        'fecha_pago' => 'date', // Convierte la fecha automáticamente a un objeto Carbon
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($payment) {
            self::actualizarEstadoFactura($payment->invoice_id);
        });

        static::deleted(function ($payment) {
            self::actualizarEstadoFactura($payment->invoice_id);
        });
    }

    private static function actualizarEstadoFactura($invoiceId)
    {
        $factura = Invoice::find($invoiceId);
        if (!$factura) return;

        // Calcular el monto total pagado
        $montoPagado = Payment::where('invoice_id', $invoiceId)->sum('monto');
        $factura->monto_pagado = $montoPagado;

        // Actualizar estado de la factura
        if ($montoPagado == 0) {
            $factura->estado = 'pendiente';
        } elseif ($montoPagado >= $factura->monto_total) {
            $factura->estado = 'pagado';
        } else {
            $factura->estado = 'parcial';
        }

        $factura->save();
    }

}
