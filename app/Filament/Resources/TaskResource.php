<?php

namespace App\Filament\Resources;

use App\Enums\DifficultyEnum;
use App\Enums\TaskStatusEnum;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use App\Services\ImageService;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Livewire\TemporaryUploadedFile;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Main info')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('slug')
                                ->maxLength(255),

                            Forms\Components\Textarea::make('summary')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('status')
                                ->options(TaskStatusEnum::options()),

                            Forms\Components\Select::make('difficulty')
                                ->required()
                                ->options(DifficultyEnum::options()),

                            Forms\Components\FileUpload::make('image')
                                ->required()
                                ->maxSize(1024 * 5)
                                ->disk('public_uploads')
                                ->directory('tasks')
                                ->saveUploadedFileUsing(function (Forms\Components\BaseFileUpload $component, TemporaryUploadedFile $file) {
                                    return (new ImageService())->uploadAsWebp($file, $component->getDirectory());
                                }),
                        ]),

                    Forms\Components\Wizard\Step::make('Additional')
                        ->schema([
                            Forms\Components\Fieldset::make('Details')
                                ->relationship('details')
                                ->schema([
                                    Forms\Components\RichEditor::make('description')
                                        ->columnSpan(2)
                                ]),

                            Forms\Components\Select::make('stacks')
                                ->label('Technologies stack')
                                ->multiple()
                                ->relationship('stacks', 'name'),

                           Forms\Components\Select::make('tags')
                               ->label('Tags')
                               ->multiple()
                               ->relationship('tags', 'name'),

                            Forms\Components\Repeater::make('Additional images')
                                ->relationship('images')
                                ->schema([
                                    Forms\Components\FileUpload::make('image')
                                        ->maxSize(1024 * 5)
                                        ->disk('public_uploads')
                                        ->directory('tasks/additional')
                                        ->saveUploadedFileUsing(function (Forms\Components\BaseFileUpload $component, TemporaryUploadedFile $file) {
                                            return (new ImageService())->uploadAsWebp($file, $component->getDirectory());
                                        }),
                                ])
                                ->createItemButtonLabel('Add image')
                                ->defaultItems(0),
                        ])
                ])
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\ImageColumn::make('image')
                    ->disk('public_uploads'),

                Tables\Columns\TextColumn::make('status')
                    ->enum(TaskStatusEnum::options()),

                Tables\Columns\TextColumn::make('difficulty')
                    ->enum(DifficultyEnum::options()),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatusEnum::options()),

                Tables\Filters\SelectFilter::make('difficulty')
                    ->options(DifficultyEnum::options()),

                Tables\Filters\SelectFilter::make('stacks')
                    ->relationship('stacks', 'name')
                    ->multiple()
                    ->optionsLimit(5)
                    ->searchable(),

                Tables\Filters\SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->optionsLimit(5)
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
