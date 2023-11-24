<?php

namespace App\Filament\Widgets;

use App\Enums\UserTaskStatusEnum;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalUsersCount = Cache::remember('total_users_count', now()->addDay(), fn(): int => User::query()->count());
        $activeUsersCount = Cache::remember('active_users_count', now()->addHour(), fn(): int => User::query()->verified()->count());
        $totalTasksCount = Cache::remember('total_tasks_count', now()->addHour(), fn(): int => Task::query()->count());

        $tasksInProcessCount = Cache::remember('tasks_in_process_count', now()->addHour(), function (): int {
            return TaskUser::query()
                ->where('status', UserTaskStatusEnum::IN_DEVELOPMENT->value)
                ->count();
        });

        $tasksUnderReview = Cache::remember('tasks_under_review_count', now()->addHour(), function (): int {
            return TaskUser::query()
                ->where('status', UserTaskStatusEnum::REVIEWING->value)
                ->count();
        });

        $completedTasksCount = Cache::remember('completed_tasks_count', now()->addHour(), function (): int {
            return TaskUser::query()
                ->where('status', UserTaskStatusEnum::DONE->value)
                ->count();
        });

        return [
            Card::make('Total users', $totalUsersCount)
                ->icon('heroicon-o-users'),

            Card::make('Active users', $activeUsersCount)
                ->icon('heroicon-o-lightning-bolt'),

            Card::make('Total tasks', $totalTasksCount)
                ->icon('heroicon-o-fire'),

            Card::make('Tasks in process', $tasksInProcessCount)
                ->icon('heroicon-o-clock'),

            Card::make('Tasks under review', $tasksUnderReview)
                ->icon('heroicon-o-eye'),

            Card::make('Completed tasks', $completedTasksCount)
                ->icon('heroicon-o-check-circle')
        ];
    }
}
