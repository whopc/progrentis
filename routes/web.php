<?php

use App\Http\Controllers\ReciboPagoController;
use Illuminate\Support\Facades\Route;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

// Route::get('/', function () {
//     return view('welcome');
// });
//Route::get('/recibo-pago/{id}', [ReciboPagoController::class, 'descargarRecibo'])->name('recibo-pago');
//Route::get('/recibo/{id}', function ($id) {
//    $payment = Payment::findOrFail($id);
//
//    // Generar el PDF con la vista `recibo-pago`
//    $pdf = Pdf::loadView('pdfs.recibo-pago', compact('payment'));
//
//
//    return $pdf->download('Recibo_Pago_'.$payment->id.'.pdf');
//})->name('recibo-pago');
Route::get('/recibo-pago/{id}', [ReciboPagoController::class, 'descargarRecibo'])->name('recibo-pago');
