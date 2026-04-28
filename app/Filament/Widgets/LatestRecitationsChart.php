<?php

namespace App\Filament\Widgets;

use App\Models\RecitationAttempt;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class LatestRecitationsChart extends ChartWidget
{
    protected static ?string $heading = 'محاولات التسميع (آخر 7 أيام)';

    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'date' => $date->format('Y-m-d'),
                'count' => RecitationAttempt::whereDate('created_at', $date)->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'محاولات التسميع',
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#1B5E20',
                    'backgroundColor' => 'rgba(27, 94, 32, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('d/m'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
