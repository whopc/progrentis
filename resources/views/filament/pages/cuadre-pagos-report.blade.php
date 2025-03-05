<x-filament::page>
    <h2 class="text-lg font-bold">Cuadre de Pagos</h2>

    <!-- Formulario de selección de fechas -->
    <div class="flex space-x-4 mt-4">
        <div>
            <label for="startDate" class="block font-semibold">Desde:</label>
            <input wire:model.lazy="startDate"
                   wire:change="$dispatch('updateStartDate')"
                   type="date" id="startDate"
                   class="border rounded p-2 bg-gray-200 text-black">
        </div>
        <div>
            <label for="endDate" class="block font-semibold">Hasta:</label>
            <input wire:model.lazy="endDate"
                   wire:change="$dispatch('updateEndDate')"
                   type="date" id="endDate"
                   class="border rounded p-2 bg-gray-200 text-black">
        </div>
    </div>

    <!-- Cuadre de pagos -->
    <div id="report-content"> <!-- Contenedor para impresión -->
        <h2 class="text-lg font-bold mt-4">Cuadre de Pagos</h2>

        <!-- Mostrar fechas seleccionadas en el reporte -->
        <p class="text-sm mt-2">Desde: <strong>{{ $this->startDate }}</strong> - Hasta: <strong>{{ $this->endDate }}</strong></p>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="p-4 bg-green-100 border rounded">
                <h3 class="font-semibold">Efectivo</h3>
                <p class="text-xl">${{ number_format($this->totals['efectivo'] ?? 0, 2) }}</p>
            </div>

            <div class="p-4 bg-blue-100 border rounded">
                <h3 class="font-semibold">Transferencia</h3>
                <p class="text-xl">${{ number_format($this->totals['transferencia'] ?? 0, 2) }}</p>
            </div>

            <div class="p-4 bg-yellow-100 border rounded">
                <h3 class="font-semibold">Tarjeta</h3>
                <p class="text-xl">${{ number_format($this->totals['tarjeta'] ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Total General -->
        <div class="p-4 mt-4 bg-gray-800 text-white border rounded">
            <h3 class="font-semibold">Total General</h3>
            <p class="text-2xl">${{ number_format($this->totalGeneral, 2) }}</p>
        </div>
    </div>

    <!-- Botón de impresión -->
    <button onclick="printReport()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
        Imprimir Reporte
    </button>

    <!-- Script de impresión -->
    <script>
        function printReport() {
            var printContents = document.getElementById('report-content').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</x-filament::page>
