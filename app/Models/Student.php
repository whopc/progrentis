<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellido',
        'grade_id',
        'section_id',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    // Relación: Un estudiante tiene un solo Invoice (Factura)
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Relación: Un estudiante puede tener muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
