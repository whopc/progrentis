<?php
namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Grade;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;

class ReportePagosCurso extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?int $navigationGroupId = 3;
    protected static string $view = 'filament.pages.reporte-pagos-curso';

    public ?int $grade_id = null;

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('grade_id')
                ->label('Selecciona el Curso')
                ->options(Grade::pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn () => $this->refresh()),
        ]);
    }

    public function getData(): array
    {
        $students = Student::when($this->grade_id, fn (Builder $query) =>
        $query->where('grade_id', $this->grade_id))
            ->get()
            ->map(function ($student) {
                $totalPaid = Payment::where('student_id', $student->id)->sum('monto');
                $status = $totalPaid >= $student->tuition_fee ? 'Pagado' : 'Pendiente';
                return [
                    'name' => $student->name,
                    'totalPaid' => $totalPaid,
                    'status' => $status,
                ];
            });

        return [
            'students' => $students,
        ];
    }
    public function getViewData(): array
    {
        return $this->getData(); // Retorna los datos que necesitas en la vista
    }
}
