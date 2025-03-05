<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ReciboPagoController extends Controller
{
    public function descargarRecibo($id)
    {
        // 🛑 Aseguramos que estamos obteniendo el pago con la factura y el estudiante
        $payment = Payment::with('invoice.student')->find($id);

        if (!$payment) {
            abort(404, 'El pago no fue encontrado.');
        }

        // ✅ Datos adicionales
        $user = Auth::user();
        $usuario = $user ? $user->name : 'Usuario Desconocido';

        $schoolName = 'CEFODIPF';

        // ✅ Manejo del logo en Base64 para evitar problemas en PDF
        $schoolLogoPath = public_path('images/logo.png');
        $schoolLogo = File::exists($schoolLogoPath)
            ? 'data:image/png;base64,' . base64_encode(File::get($schoolLogoPath))
            : null;

        // ✅ Verificar que las variables no son NULL
        Log::info('Datos enviados al PDF:', [
            'factura' => $payment->invoice,
            'pago' => $payment,
            'usuario' => $usuario,
            'schoolName' => $schoolName,
            'schoolLogo' => $schoolLogo,
        ]);

        // ✅ Enviar datos a la vista PDF
        $data = [
            'factura' => $payment->invoice,
            'pago' => $payment,
            'usuario' => $usuario,
            'schoolName' => $schoolName,
            'schoolLogo' => $schoolLogo,
        ];

        // 🔹 Cargar la vista y generar el PDF
        $pdf = Pdf::loadView('pdfs.recibo-pago', $data);
        return $pdf->stream('recibo_pago_' . $payment->id . '.pdf');
    }
}
