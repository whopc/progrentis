<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 22px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
            height: auto;
        }
        .info-section {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .details {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="header">
    @if(isset($schoolLogo) && $schoolLogo)
        <img src="{{ $schoolLogo }}" alt="Logo del Centro" width="300">
    @else
        <p><strong>No hay logo disponible</strong></p>
    @endif
    <h2>{{ $schoolName ?? 'Nombre del Centro' }}</h2>
    <h3>Recibo de Pago</h3>
        <h2>Plataforma Progrentis</h2>

</div>

<div class="info-section">
    <p><strong>Número de Factura:</strong> {{ $pago->id ?? 'Sin número' }}</p>
    <p><strong>Número de Orden:</strong> {{$factura->id ?? 'Sin número' }}</p>
    <p><strong>Fecha de Pago:</strong> {{ isset($pago->fecha_pago) ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'No registrada' }}</p>
    <p><strong>Estudiante:</strong> {{ $factura->student->nombre ?? 'Sin nombre' }} {{ $factura->student->apellido ?? '' }}</p>
    <p><strong>Monto Pagado:</strong> RD$ {{ isset($pago->monto) ? number_format($pago->monto, 2) : 'No registrado' }}</p>
    <p><strong>Método de Pago:</strong> {{ ucfirst($pago->metodo_pago ?? 'No especificado') }}</p>
    <p><strong>Estado de la Factura:</strong> {{ ucfirst($factura->estado ?? 'No registrado') }}</p>
    <p><strong>Monto Pendiente:</strong> RD$ {{ isset($factura->monto_total, $factura->monto_pagado) ? number_format($factura->monto_total - $factura->monto_pagado, 2) : 'No disponible' }}</p>
</div>

<div class="info-section">
    <p><strong>Recibo generado por:</strong> {{ $usuario ?? 'Usuario Desconocido' }}</p>
</div>

<div class="footer">
    <p>Gracias por su pago.</p>
</div>

</body>
</html>
