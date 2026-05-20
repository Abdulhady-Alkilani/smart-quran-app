<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AyahReviewNotification extends Notification
{
    use Queueable;

    protected string $surahName;
    protected int $ayahNumber;
    protected int $ayahId;
    protected int $dueCount;

    public function __construct(string $surahName, int $ayahNumber, int $ayahId, int $dueCount = 1)
    {
        $this->surahName = $surahName;
        $this->ayahNumber = $ayahNumber;
        $this->ayahId = $ayahId;
        $this->dueCount = $dueCount;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ayah_review',
            'surah_name' => $this->surahName,
            'ayah_number' => $this->ayahNumber,
            'ayah_id' => $this->ayahId,
            'due_count' => $this->dueCount,
            'message_ar' => $this->dueCount > 1
                ? "حان وقت مراجعة سورة {$this->surahName} آية {$this->ayahNumber} (و{$this->dueCount} آيات أخرى)"
                : "حان وقت مراجعة سورة {$this->surahName} آية {$this->ayahNumber}",
            'message_en' => $this->dueCount > 1
                ? "Time to review Surah {$this->surahName} Ayah {$this->ayahNumber} (and {$this->dueCount} more)"
                : "Time to review Surah {$this->surahName} Ayah {$this->ayahNumber}",
            'action_url' => route('reviews.index'),
        ];
    }
}
