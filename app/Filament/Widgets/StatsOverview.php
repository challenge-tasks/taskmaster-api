<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $usersCount = Cache::remember('users_count', now()->addDay(), fn() => User::query()->count());
        $tasksCount = Cache::remember('tasks_count', now()->addDay(), fn() => Task::query()->count());

        return [
            Card::make('Active users', $usersCount)
                ->icon('heroicon-o-lightning-bolt'),
            Card::make('Total tasks', $tasksCount)
                ->icon('heroicon-o-fire')
        ];
    }
}
