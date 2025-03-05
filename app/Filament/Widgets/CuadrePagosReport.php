<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Livewire\Component;

class CuadrePagosReport extends BaseWidget
{
    use InteractsWithForms;

    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getCards(): array
    {
        $start = $this->startDate ?: now()->startOfDay();
        $end = $this->endDate ?: now()->endOfDay();

        $totals = Payment::whereBetween('fecha_pago', [$start, $end])
            ->selectRaw('metodo_pago, SUM(monto) as total')
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago');

        return [
            Card::make('Total en Efectivo', '$' . number_format($totals['efectivo'] ?? 0, 2))
                ->color('success'),
            Card::make('Total en Transferencias', '$' . number_format($totals['transferencia'] ?? 0, 2))
                ->color('info'),
            Card::make('Total en Tarjeta', '$' . number_format($totals['tarjeta'] ?? 0, 2))
                ->color('warning'),
            Card::make('Total General', '$' . number_format(array_sum($totals->toArray()), 2))
                ->color('primary'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('startDate')
                ->label('Desde')
                ->default(now()->startOfDay())
                ->reactive()
                ->afterStateUpdated(fn () => $this->refresh()),

            DatePicker::make('endDate')
                ->label('Hasta')
                ->default(now()->endOfDay())
                ->reactive()
                ->afterStateUpdated(fn () => $this->refresh()),
        ]);
    }
}
