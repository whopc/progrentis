<div class="p-4 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-2">Reporte de Pagos por Sección</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($selectedGrade)
            <div>
                <span class="font-semibold">Grado seleccionado:</span> {{ $selectedGrade }}
            </div>
        @endif

        @if($selectedSections)
            <div>
                <span class="font-semibold">Secciones seleccionadas:</span> {{ $selectedSections }}
            </div>
        @endif
    </div>
</div>
