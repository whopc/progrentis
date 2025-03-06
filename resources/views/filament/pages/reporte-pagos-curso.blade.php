<x-filament::page>
    <div class="space-y-4">
        <h1 class="text-2xl font-bold">Reporte de Pagos por Curso</h1>

        {{-- Filtro para seleccionar el curso --}}
        <div class="flex space-x-4">
            {{ $this->form }}
        </div>

        {{-- Tabla de reportes --}}
        @if(!empty($students))
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left border">Estudiante</th>
                    <th class="px-4 py-2 text-left border">Monto Pagado</th>
                    <th class="px-4 py-2 text-left border">Estado</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                    <tr class="border-b">
                        <td class="px-4 py-2 border">{{ $student['name'] }}</td>
                        <td class="px-4 py-2 border">RD$ {{ number_format($student['totalPaid'], 2) }}</td>
                        <td class="px-4 py-2 border">
                                <span class="px-2 py-1 text-sm font-semibold text-white rounded
                                    {{ $student['status'] === 'Pagado' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $student['status'] }}
                                </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-600">No hay estudiantes registrados en este curso.</p>
        @endif
    </div>
</x-filament::page>
