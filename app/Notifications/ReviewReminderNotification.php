<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewReminderNotification extends Notification
{
    use Queueable;

    protected int $dueCount;
    protected int $overdueCount;

    public function __construct(int $dueCount, int $overdueCount = 0)
    {
        $this->dueCount = $dueCount;
        $this->overdueCount = $overdueCount;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'review_reminder',
            'due_count' => $this->dueCount,
            'overdue_count' => $this->overdueCount,
            'message_ar' => $this->overdueCount > 0
                ? "لديك {$this->overdueCount} آية متأخرة و{$this->dueCount} آية مستحقة للمراجعة اليوم"
                : "لديك {$this->dueCount} آية مستحقة للمراجعة اليوم",
            'message_en' => $this->overdueCount > 0
                ? "You have {$this->overdueCount} overdue and {$this->dueCount} ayahs due for review today"
                : "You have {$this->dueCount} ayahs due for review today",
            'action_url' => route('reviews.index'),
        ];
    }
}
