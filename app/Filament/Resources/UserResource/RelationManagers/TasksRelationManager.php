<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\UserTaskStatusEnum;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('pivot.status')
                    ->label('Status')
                    ->enum(UserTaskStatusEnum::options()),
            ]);
    }
}
