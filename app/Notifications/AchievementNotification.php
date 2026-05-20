<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AchievementNotification extends Notification
{
    use Queueable;

    protected string $achievementType;
    protected int $value;
    protected string $messageAr;
    protected string $messageEn;

    public function __construct(string $achievementType, int $value, string $messageAr, string $messageEn)
    {
        $this->achievementType = $achievementType;
        $this->value = $value;
        $this->messageAr = $messageAr;
        $this->messageEn = $messageEn;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'achievement',
            'achievement_type' => $this->achievementType,
            'value' => $this->value,
            'message_ar' => $this->messageAr,
            'message_en' => $this->messageEn,
            'action_url' => route('dashboard'),
        ];
    }
}
