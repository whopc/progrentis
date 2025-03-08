<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Livewire\Component;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Gestión de Pagos';
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('student_id')
                    ->label('Estudiante')
                    ->relationship('student', 'nombre')
                    ->searchable()
                    ->required(),

                TextInput::make('monto_total')
                    ->label('Monto Total')
                    ->numeric()
                    ->default(2200.00)
                    ->disabled()
                    ->required(),

                TextInput::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->numeric()
                    ->default(0.00)
                    ->disabled()
                    ->required(),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado' => 'Pagado',
                        'parcial' => 'Pago Parcial',
                    ])
                    ->default('pendiente')
                    ->disabled()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('student.nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('student.apellido')
                    ->label('Apellidos')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('student.section.name')
                    ->label('Sección')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->student?->section?->name ?? 'Sin Sección'),

                TextColumn::make('monto_total')
                    ->label('Monto Total')
                    ->money('DOP')
                    ->sortable(),

                TextColumn::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->money('DOP')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->sortable(),
            ])
            ->actions([
                Action::make('registrarPago')
                    ->visible(fn ($record) => $record->estado !== 'pagado')
                    ->label('Registrar Pago')
                    ->icon('heroicon-m-banknotes')
                    ->modalHeading('Registrar Pago')
                    ->modalIcon('heroicon-m-banknotes') // Icono en la cabecera del modal
                    ->modalSubmitActionLabel('Registrar Pago') // Cambia el texto del botón
                    ->form(fn (Invoice $record) => [
                        TextInput::make('monto')
                            ->label('Monto a Pagar')
                            ->numeric()
                            ->default(fn ($record) => max(1, $record->monto_total - $record->monto_pagado))
                            ->required()
                            ->minValue(1)
                            ->maxValue($record->monto_total - $record->monto_pagado)
                            ->helperText("Monto restante a pagar: " . number_format($record->monto_total - $record->monto_pagado, 2) . " DOP")
                            ->reactive(),

                        Select::make('metodo_pago')
                            ->label('Método de Pago')
                            ->options([
                                'efectivo' => 'Efectivo',
                                'transferencia' => 'Transferencia',
                                'tarjeta' => 'Tarjeta',
                            ])
                            ->default('efectivo')
                            ->reactive(),
                    ])

                    ->action(function (array $data, Invoice $record, Component $livewire) {
                        $payment = DB::transaction(function () use ($data, $record) {
                            $montoNuevo = $data['monto'];
                            $montoPagado = $record->monto_pagado;
                            $montoTotal = $record->monto_total;

                            if (($montoPagado + $montoNuevo) > $montoTotal) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('El monto pagado no puede superar el monto total de la factura.')
                                    ->danger()
                                    ->send();
                                return null;
                            }

                            $estadoPago = match ($data['metodo_pago']) {
                                'transferencia' => 'pendiente',
                                default => 'completado',
                            };

                            // **Crear el pago**
                            $payment = Payment::create([
                                'student_id' => $record->student_id,
                                'invoice_id' => $record->id,
                                'monto' => $montoNuevo,
                                'fecha_pago' => now(),
                                'metodo_pago' => $data['metodo_pago'],
                                'estado' => $estadoPago,
                            ]);

                            // **Actualizar factura**
                            $record->monto_pagado += $montoNuevo;
                            $record->estado = ($record->monto_pagado >= $montoTotal) ? 'pagado' : 'parcial';
                            $record->save();

                            return $payment;
                        });

                        if ($payment) {
                            Notification::make()
                                ->title('Éxito')
                                ->body('Pago registrado correctamente.')
                                ->success()
                                ->send();

                            // ✅ Cerrar el modal después del pago
                            $livewire->dispatch('close-modal');

                            // 🔄 Recargar la tabla para reflejar cambios
                            $livewire->dispatch('refreshTable');

                            // ✅ Redirigir a la descarga del recibo después de cerrar el modal
                            $livewire->dispatch('abrirRecibo', route('recibo-pago', ['id' => $payment->id]));
                        }
                    })

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
        ];
    }
}
