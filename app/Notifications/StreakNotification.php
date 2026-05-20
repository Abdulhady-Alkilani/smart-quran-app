<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StreakNotification extends Notification
{
    use Queueable;

    protected int $streakDays;

    public function __construct(int $streakDays)
    {
        $this->streakDays = $streakDays;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'streak',
            'streak_days' => $this->streakDays,
            'message_ar' => "أحسنت! واصلت المراجعة لمدة {$this->streakDays} يوم متتالي",
            'message_en' => "Great job! You've maintained a {$this->streakDays}-day review streak",
            'action_url' => route('reviews.index'),
        ];
    }
}
