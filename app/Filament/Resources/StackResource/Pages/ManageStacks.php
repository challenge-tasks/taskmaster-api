<?php

namespace App\Filament\Resources\StackResource\Pages;

use App\Filament\Resources\StackResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStacks extends ManageRecords
{
    protected static string $resource = StackResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
