<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Invoice;
use Illuminate\Support\Carbon;

class InvoiceSeeder extends Seeder
{
//    public function run(): void
//    {
//        // 🔍 Buscar TODOS los estudiantes ya registrados en la base de datos
//        $students = Student::all();
//
//        // 🔄 Crear un Invoice para cada estudiante encontrado
//        foreach ($students as $student) {
//            Invoice::firstOrCreate([
//                'student_id' => $student->id, // Relación con el estudiante
//            ], [
//                'monto_total' => 2200.00,
//                'monto_pagado' => 0.00,
//                'estado' => 'pendiente',
//                'fecha_emision' => Carbon::now(),
//            ]);
//        }
//    }
//}
    public function run(): void
    {
        // 🔍 Buscar TODOS los estudiantes ya registrados en la base de datos
        $students = Student::all();

        // 🔄 Crear un Invoice para cada estudiante que NO tenga ya uno con el mismo monto
        foreach ($students as $student) {
            $existingInvoice = Invoice::where('student_id', $student->id)
                ->where('monto_total', 2200.00)
                ->exists();

            if (!$existingInvoice) {
                Invoice::create([
                    'student_id' => $student->id, // Relación con el estudiante
                    'monto_total' => 2200.00,
                    'monto_pagado' => 0.00,
                    'estado' => 'pendiente',
                    'fecha_emision' => Carbon::now(),
                ]);
            }
        }
    }
}
