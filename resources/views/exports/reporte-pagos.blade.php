<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pagos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f0f0f0; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>
<h1>Reporte de Pagos por Sección</h1>

@foreach ($agrupados as $grado => $secciones)
    @php
        // PRIMER RECORRIDO: Calcular totales del grado
        $totalGradoMonto = 0;
        $totalGradoEstudiantes = 0;
        $gradoPagados = 0;
        $gradoParciales = 0;
        $gradoPendientes = 0;
        $gradoNoFacturados = 0;

        foreach ($secciones as $estudiantes) {
            foreach ($estudiantes as $student) {
                $estado = $student->invoice->estado ?? 'No facturado';
                $pagado = $student->invoice->monto_pagado ?? 0;

                $totalGradoMonto += $pagado;
                $totalGradoEstudiantes++;

                match ($estado) {
                    'pagado' => $gradoPagados++,
                    'parcial' => $gradoParciales++,
                    'pendiente' => $gradoPendientes++,
                    default => $gradoNoFacturados++,
                };
            }
        }
    @endphp

    <h2>{{ $grado }}</h2>

    <p><strong>Total Pagado en el Grado:</strong> DOP {{ number_format($totalGradoMonto, 2) }}</p>
    <ul>
        <li><strong>Estudiantes:</strong> {{ $totalGradoEstudiantes }}</li>
        <li><strong>Pagados:</strong> {{ $gradoPagados }}</li>
        <li><strong>Parciales:</strong> {{ $gradoParciales }}</li>
        <li><strong>Pendientes:</strong> {{ $gradoPendientes }}</li>
        <li><strong>No facturados:</strong> {{ $gradoNoFacturados }}</li>
    </ul>

    {{-- SEGUNDO RECORRIDO: Mostrar secciones --}}
    @foreach ($secciones as $seccion => $estudiantes)
        <h3>Sección: {{ $seccion }}</h3>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Estado</th>
                <th>Pagado</th>
                <th>Total</th>
                <th>Diferencia</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalSeccionMonto = 0;
                $seccionEstudiantes = 0;
                $seccionPagados = 0;
                $seccionParciales = 0;
                $seccionPendientes = 0;
                $seccionNoFacturados = 0;
            @endphp
            @foreach ($estudiantes as $i => $student)
                @php
                    $estado = $student->invoice->estado ?? 'No facturado';
                    $pagado = $student->invoice->monto_pagado ?? 0;
                    $total = $student->invoice->monto_total ?? 0;
                    $diferencia = $total - $pagado;

                    $totalSeccionMonto += $pagado;
                    $seccionEstudiantes++;

                    match ($estado) {
                        'pagado' => $seccionPagados++,
                        'parcial' => $seccionParciales++,
                        'pendiente' => $seccionPendientes++,
                        default => $seccionNoFacturados++,
                    };
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $student->nombre }}</td>
                    <td>{{ $student->apellido }}</td>
                    <td>{{ ucfirst($estado) }}</td>
                    <td>DOP {{ number_format($pagado, 2) }}</td>
                    <td>DOP {{ number_format($total, 2) }}</td>
                    <td>DOP {{ number_format($diferencia, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <p><strong>Total Pagado en Sección {{ $seccion }}:</strong> DOP {{ number_format($totalSeccionMonto, 2) }}</p>
        <ul>
            <li><strong>Estudiantes:</strong> {{ $seccionEstudiantes }}</li>
            <li><strong>Pagados:</strong> {{ $seccionPagados }}</li>
            <li><strong>Parciales:</strong> {{ $seccionParciales }}</li>
            <li><strong>Pendientes:</strong> {{ $seccionPendientes }}</li>
            <li><strong>No facturados:</strong> {{ $seccionNoFacturados }}</li>
        </ul>

        <div style="page-break-after: always;"></div>
    @endforeach

@endforeach
</body>
</html>
