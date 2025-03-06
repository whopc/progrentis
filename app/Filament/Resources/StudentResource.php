<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Invoice;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';


    protected static ?string $navigationGroup = 'Gestión Académica'; // Agrupar en el menú
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),

                TextInput::make('apellido')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(255),

                Select::make('grade_id')
                    ->label('Grado')
                    ->relationship('grade', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('section_id')
                    ->label('Sección')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Nombre')->sortable()->searchable(),
                TextColumn::make('apellido')->label('Apellido')->sortable()->searchable(),
                TextColumn::make('grade.name')->label('Grado')->sortable(),
                TextColumn::make('section.name')->label('Sección')->sortable(),
                TextColumn::make('invoice.monto_pagado')
                    ->label('Monto Pagado')
                    ->sortable()
                    ->money('DOP'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grade_id')
                    ->label('Filtrar por Grado')
                    ->relationship('grade', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('section_id')
                    ->label('Filtrar por Sección')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function afterCreate(CreateRecord $page, Student $record): void
    {
        // Crear el Invoice automáticamente si no existe ya uno con el monto definido
        Invoice::firstOrCreate([
            'student_id' => $record->id,
            'monto_total' => 2200.00,
        ], [
            'monto_pagado' => 0.00,
            'estado' => 'pendiente',
            'fecha_emision' => Carbon::now(),
        ]);
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
