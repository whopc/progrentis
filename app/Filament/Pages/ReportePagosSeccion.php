<?php

namespace App\Filament\Pages;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\Invoice;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ReportePagosSeccion extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    // Definir esta propiedad correctamente
    protected static string $view = 'filament.pages.reporte-pagos-seccion';

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Reporte de Pagos por Sección';
    protected static ?string $title = 'Reporte de Pagos por Sección';
    protected static ?int $navigationSort = 100;
    protected static ?string $navigationGroup = 'Reportes';

    public ?array $data = [];
    public $selectedGrade = null;
    public $selectedSections = [];
    public $estadisticas = [
        'total' => 0,
        'pagados' => 0,
        'parciales' => 0,
        'pendientes' => 0,
        'no_facturados' => 0,
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('selectedGrade')
                            ->label('Grado')
                            ->options(Grade::pluck('name', 'id'))
                            ->placeholder('Seleccione un grado (opcional)')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedSections = [];
                                $this->refreshTable();
                            }),

                        Select::make('selectedSections')
                            ->label('Secciones')
                            ->multiple()
                            ->options(function (callable $get) {
                                if ($get('selectedGrade')) {
                                    return Section::where('grade_id', $get('selectedGrade'))
                                        ->pluck('name', 'id');
                                }
                                return Section::pluck('name', 'id');
                            })
                            ->placeholder('Seleccione una o varias secciones')
                            ->live()
                            ->afterStateUpdated(function () {
                                $this->refreshTable();
                            }),
                    ])
                    ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('apellido')
                    ->label('Apellidos')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section.name')
                    ->label('Sección')
                    ->sortable(),
                TextColumn::make('invoice.estado')
                    ->label('Estado Pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pagado' => 'success',
                        'parcial' => 'warning',
                        'pendiente' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($record) => $record->invoice ? $record->invoice->estado : 'No facturado'),
                TextColumn::make('invoice.monto_pagado')
                    ->label('Monto Pagado')
                    ->money('DOP')
                    ->formatStateUsing(fn ($record) => $record->invoice ? $record->invoice->monto_pagado : 0),
                TextColumn::make('invoice.monto_total')
                    ->label('Monto Total')
                    ->money('DOP')
                    ->formatStateUsing(fn ($record) => $record->invoice ? $record->invoice->monto_total : 0),
            ])
            ->defaultSort('id', 'asc')
            ->emptyStateHeading('No hay estudiantes para mostrar')
            ->emptyStateDescription('Seleccione un grado o secciones para ver el reporte');
    }

    public function getTableQuery(): Builder
    {
        $query = Student::query()->with(['section', 'invoice']);

        // Aplicar filtros según secciones seleccionadas o grado
        if (!empty($this->selectedSections)) {
            $query->whereIn('section_id', $this->selectedSections);
        } elseif ($this->selectedGrade) {
            $sectionIds = Section::where('grade_id', $this->selectedGrade)->pluck('id')->toArray();
            $query->whereIn('section_id', $sectionIds);
        }
//        else {
//            // Sin filtros, mostrar una tabla vacía
//            $query->whereRaw('1 = 0');
//        }

        // Calcular estadísticas
        $this->calcularEstadisticas($query->get());

        return $query;
    }

    private function calcularEstadisticas($students)
    {
        // Reiniciar estadísticas
        $this->estadisticas = [
            'total' => 0,
            'pagados' => 0,
            'parciales' => 0,
            'pendientes' => 0,
            'no_facturados' => 0,
        ];

        foreach ($students as $student) {
            $this->estadisticas['total']++;

            if (!$student->invoice) {
                $this->estadisticas['no_facturados']++;
                continue;
            }

            switch ($student->invoice->estado) {
                case 'pagado':
                    $this->estadisticas['pagados']++;
                    break;
                case 'parcial':
                    $this->estadisticas['parciales']++;
                    break;
                case 'pendiente':
                    $this->estadisticas['pendientes']++;
                    break;
            }
        }
    }
    public function exportarPDF(Request $request)
    {
        $this->selectedGrade = $request->input('selectedGrade');
        $this->selectedSections = $request->input('selectedSections', []);

        // Obtener los estudiantes filtrados usando la lógica de getTableQuery
        $students = Student::query()
            ->with(['section.grade', 'invoice'])
            ->when(!empty($this->selectedSections), function ($query) {
                $query->whereIn('section_id', $this->selectedSections);
            })
            ->when($this->selectedGrade && empty($this->selectedSections), function ($query) {
                $sectionIds = Section::where('grade_id', $this->selectedGrade)->pluck('id');
                $query->whereIn('section_id', $sectionIds);
            })
            ->get();

        // Agrupar por grado y sección
        $agrupados = $students->groupBy(fn($student) => $student->section->grade->name ?? 'Sin Grado')
            ->map(fn($grupo) => $grupo->groupBy(fn($student) => $student->section->name ?? 'Sin Sección'));

        // Generar nombre del archivo
        $filename = $this->generarNombreArchivo();

        // Renderizar PDF
        $pdf = Pdf::loadView('exports.reporte-pagos', [
            'agrupados' => $agrupados,
        ]);

        return response()->streamDownload(fn () => print($pdf->stream()), $filename);
    }
    private function generarNombreArchivo(): string
    {
        if ($this->selectedGrade) {
            $grade = \App\Models\Grade::find($this->selectedGrade)?->name ?? 'Grado';

            if (count($this->selectedSections) === 1) {
                $section = \App\Models\Section::find($this->selectedSections[0])?->name ?? 'Seccion';
                return Str::slug("{$grade}-Seccion-{$section}") . ".pdf";
            }

            if (count($this->selectedSections) > 1) {
                return Str::slug("{$grade}-Varias_Secciones") . ".pdf";
            }

            return Str::slug($grade) . ".pdf";
        }

        return Carbon::now()->format('Y-m-d') . "-reporte_pagos.pdf";
    }

    public function refreshTable()
    {
        $this->resetTable();
    }
}
