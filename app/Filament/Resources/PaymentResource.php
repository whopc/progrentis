<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-m-banknotes';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Gestión de Pagos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Estudiante')
                    ->relationship('student', 'nombre')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('invoice_id')
                    ->label('Factura')
                    ->relationship('invoice', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('monto')
                    ->label('Monto')
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->default(now())
                    ->required(),

                Forms\Components\Select::make('metodo_pago')
                    ->label('Método de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                        'transferencia' => 'Transferencia',
                    ])
                    ->required(),

                Forms\Components\Select::make('estado')
                    ->label('Estado del Pago')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'completado' => 'Completado',
                        'rechazado' => 'Rechazado',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('student.nombre')
                    ->label('Estudiante')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice.id')
                    ->label('Factura')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date(),

                Tables\Columns\TextColumn::make('metodo_pago')
                    ->label('Método de Pago')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'completado',
                        'danger' => 'rechazado',
                    ])
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado del Pago')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'completado' => 'Completado',
                        'rechazado' => 'Rechazado',
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
