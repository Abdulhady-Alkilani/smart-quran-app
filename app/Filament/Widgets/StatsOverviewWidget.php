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
            Stat::make('إجمالي المستخدمين', User::count())
                ->description('طالب ومدير')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('آيات محفوظة', UserMemorizationProgress::where('status', 'memorized')->count())
                ->description('من أصل 6,236 آية')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),
            Stat::make('محاولات التسميع اليوم', RecitationAttempt::whereDate('created_at', Carbon::today())->count())
                ->description('تسميعات اليوم')
                ->descriptionIcon('heroicon-m-microphone')
                ->color('warning'),
        ];
    }
}
