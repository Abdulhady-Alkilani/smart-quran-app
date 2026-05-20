<?php

namespace App\Filament\Widgets;

use App\Models\RecitationAttempt;
use App\Models\User;
use App\Models\UserMemorizationProgress;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('filament.users'), User::count())
                ->description(__('filament.users'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make(__('filament.memorized_ayahs'), UserMemorizationProgress::where('status', 'memorized')->count())
                ->description('6,236 ' . __('filament.total_ayahs'))
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),
            Stat::make(__('filament.recitation_attempts') . ' - ' . __('filament.due_today'), RecitationAttempt::whereDate('created_at', Carbon::today())->count())
                ->description(__('filament.due_today'))
                ->descriptionIcon('heroicon-m-microphone')
                ->color('warning'),
        ];
    }
}
