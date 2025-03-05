<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\On;

class CuadrePagosReport extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $title = 'Cuadre de Pagos';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?int $navigationGroupId = 3;
    protected static string $view = 'filament.pages.cuadre-pagos-report';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public array $totals = [];
    public float $totalGeneral = 0;

    public function mount()
    {
        $this->startDate = now()->toDateString(); // Fecha de hoy
        $this->endDate = now()->toDateString();   // Fecha de hoy
        $this->loadData();
    }


    public function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('startDate')
                ->label('Desde')
                ->default($this->startDate)
                ->reactive()
                ->afterStateUpdated(fn () => $this->loadData()),

            DatePicker::make('endDate')
                ->label('Hasta')
                ->default($this->endDate)
                ->reactive()
                ->afterStateUpdated(fn () => $this->loadData()),
        ]);
    }

    #[On('updateStartDate')]
    public function updateStartDate()
    {
        $this->loadData();
    }

    #[On('updateEndDate')]
    public function updateEndDate()
    {
        $this->loadData();
    }

    public function loadData()
    {
        if (!$this->startDate || !$this->endDate) {
            return;
        }

        $this->totals = Payment::whereBetween('fecha_pago', [$this->startDate, $this->endDate])
            ->selectRaw('metodo_pago, SUM(monto) as total')
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();

        $this->totalGeneral = array_sum($this->totals);
    }
}

