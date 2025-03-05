<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{


    protected $fillable = [
        'student_id',
        'monto_total',
        'monto_pagado',
        'estado',
        'fecha_emision',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
