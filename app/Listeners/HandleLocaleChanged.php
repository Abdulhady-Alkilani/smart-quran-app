<?php

namespace App\Listeners;

use BezhanSalleh\FilamentLanguageSwitch\Events\LocaleChanged;
use Illuminate\Support\Facades\App;

class HandleLocaleChanged
{
    public function handle(LocaleChanged $event): void
    {
        App::setLocale($event->locale);
        session()->put('locale', $event->locale);
    }
}
