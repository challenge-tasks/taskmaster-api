<?php

namespace App\Filament\Resources;

use App\Enums\UserTaskStatusEnum;
use App\Filament\Resources\SolutionResource\Pages;
use App\Models\Solution;
use App\Models\TaskUser;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class SolutionResource extends Resource
{
    protected static ?string $model = Solution::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),

                Forms\Components\Select::make('task_id')
                    ->relationship('task', 'name')
                    ->required(),

                Forms\Components\Textarea::make('file')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.id')
                    ->label('User ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('task.name')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_checked')
                    ->trueIcon('heroicon-o-check-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Download')
                    ->action(function ($record) {
                        $file = public_path('uploads/' . $record->file);

                        return response()->download($file);
                    })
                    ->icon('heroicon-o-download')
                    ->color('warning'),

                Tables\Actions\Action::make('Mark as done')
                    ->action(function ($record) {
                        $record->update([
                            'is_checked' => true
                        ]);

                        TaskUser::query()
                            ->where('task_id', $record->task_id)
                            ->where('user_id', $record->user_id)
                            ->update([
                                'status' => UserTaskStatusEnum::DONE->value
                            ]);
                    })
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn($record) => $record->is_checked)
                    ->after(function () {
                        Notification::make()
                            ->title('Successfully marked as done')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListSolutions::route('/'),
//            'create' => Pages\CreateSolution::route('/create'),
//            'edit' => Pages\EditSolution::route('/{record}/edit'),
        ];
    }
}
