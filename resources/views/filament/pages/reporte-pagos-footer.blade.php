<div class="p-4 bg-white rounded-lg shadow mt-4">
    <h3 class="text-lg font-semibold mb-2">Resumen de Pagos</h3>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-gray-100 p-3 rounded-lg text-center">
            <span class="text-lg font-bold">{{ $estadisticas['total'] }}</span>
            <p class="text-sm text-gray-600">Total Estudiantes</p>
        </div>

        <div class="bg-green-100 p-3 rounded-lg text-center">
            <span class="text-lg font-bold text-green-700">{{ $estadisticas['pagados'] }}</span>
            <p class="text-sm text-green-600">Pagados</p>
        </div>

        <div class="bg-yellow-100 p-3 rounded-lg text-center">
            <span class="text-lg font-bold text-yellow-700">{{ $estadisticas['parciales'] }}</span>
            <p class="text-sm text-yellow-600">Pago Parcial</p>
        </div>

        <div class="bg-red-100 p-3 rounded-lg text-center">
            <span class="text-lg font-bold text-red-700">{{ $estadisticas['pendientes'] }}</span>
            <p class="text-sm text-red-600">Pendientes</p>
        </div>

        <div class="bg-gray-100 p-3 rounded-lg text-center">
            <span class="text-lg font-bold text-gray-700">{{ $estadisticas['no_facturados'] }}</span>
            <p class="text-sm text-gray-600">No Facturados</p>
        </div>
    </div>

    <div class="mt-4 text-right">
        <button
            type="button"
            onclick="window.print()"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"
        >
            Imprimir Reporte
        </button>
    </div>
</div>

<style>
    @media print {
        .filament-main-topbar,
        .filament-sidebar,
        .filament-main-footer,
        button,
        .filament-dropdown,
        .choices__inner,
        .choices {
            display: none !important;
        }

        body {
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    }
</style>
