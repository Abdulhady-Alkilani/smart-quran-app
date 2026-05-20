<?php

namespace App\Providers;

use App\Listeners\HandleLocaleChanged;
use BezhanSalleh\FilamentLanguageSwitch\Events\LocaleChanged;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(
            LocaleChanged::class,
            HandleLocaleChanged::class,
        );

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar', 'en'])
                ->visible(insidePanels: true, outsidePanels: true)
                ->labels([
                    'ar' => 'العربية',
                    'en' => 'English',
                ])
                ->circular();
        });
    }
}
