<?php

namespace App\Filament\Pages;

use App\Models\Student;
use App\Models\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ReporteSaldosEstudiantes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.reporte-saldos-estudiantes';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?int $navigationGroupId = 3;

    public ?int $section_id = null;

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('section_id')
                ->label('Selecciona la Sección')
                ->options(Section::pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn () => $this->refresh()),
        ]);
    }

    public function getData(): array
    {
        $students = Student::when($this->section_id, fn (Builder $query) =>
        $query->where('section_id', $this->section_id))
            ->get()
            ->map(function ($student) {
                $totalPaid = $student->payments()->sum('monto');
                $balance = $student->tuition_fee - $totalPaid;
                $status = $balance == 0 ? 'Pagado' : ($totalPaid > 0 ? 'Abonado' : 'Pendiente');

                return [
                    'name' => $student->name,
                    'totalPaid' => $totalPaid,
                    'balance' => $balance,
                    'status' => $status,
                ];
            });

        return [
            'students' => $students,
        ];
    }
}
