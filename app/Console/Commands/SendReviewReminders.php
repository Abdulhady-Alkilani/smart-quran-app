<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserMemorizationProgress;
use App\Notifications\AchievementNotification;
use App\Notifications\AyahReviewNotification;
use App\Notifications\ReviewReminderNotification;
use App\Notifications\StreakNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReviewReminders extends Command
{
    protected $signature = 'notifications:review-reminders';

    protected $description = 'Send review reminder notifications to users with due reviews';

    public function handle(): int
    {
        $users = User::whereHas('memorizationProgress')->get();
        $sentCount = 0;

        foreach ($users as $user) {
            $dueItems = $user->memorizationProgress()
                ->with('ayah.surah')
                ->whereNotNull('next_review_date')
                ->whereDate('next_review_date', '<=', Carbon::today())
                ->get();

            $dueCount = $dueItems->count();

            if ($dueCount > 0) {
                $firstDue = $dueItems->first();
                $overdueCount = $dueItems->filter(
                    fn($p) => $p->next_review_date->lt(Carbon::today())
                )->count();

                $alreadyNotifiedToday = $user->notifications()
                    ->where('type', ReviewReminderNotification::class)
                    ->whereDate('created_at', Carbon::today())
                    ->exists();

                if (!$alreadyNotifiedToday) {
                    $user->notify(new ReviewReminderNotification($dueCount, $overdueCount));
                    $sentCount++;
                }

                $todayAyahs = $dueItems->filter(
                    fn($p) => $p->next_review_date->isToday()
                );

                foreach ($todayAyahs as $item) {
                    $notifKey = 'ayah_review_' . $item->ayah_id . '_' . Carbon::today()->format('Y-m-d');

                    $alreadyNotifiedAboutAyah = $user->notifications()
                        ->where('type', AyahReviewNotification::class)
                        ->whereDate('created_at', Carbon::today())
                        ->whereJsonContains('data->ayah_id', $item->ayah_id)
                        ->exists();

                    if (!$alreadyNotifiedAboutAyah) {
                        $user->notify(new AyahReviewNotification(
                            $item->ayah->surah->name_ar,
                            $item->ayah->number_in_surah,
                            $item->ayah_id,
                            $dueCount - 1
                        ));
                        $sentCount++;
                    }
                }
            }

            $this->checkStreak($user);
            $this->checkAchievements($user);
        }

        $this->info("Sent {$sentCount} review reminder notifications.");
        return self::SUCCESS;
    }

    private function checkStreak(User $user): void
    {
        $streak = $this->calculateStreak($user);
        $milestones = [3, 7, 14, 30, 60, 90, 180, 365];

        if (in_array($streak, $milestones)) {
            $alreadyNotified = $user->notifications()
                ->where('type', StreakNotification::class)
                ->whereJsonContains('data->streak_days', $streak)
                ->exists();

            if (!$alreadyNotified) {
                $user->notify(new StreakNotification($streak));
            }
        }
    }

    private function calculateStreak(User $user): int
    {
        $streak = 0;
        $date = Carbon::today();

        while (true) {
            $hasActivity = $user->recitationAttempts()
                ->whereDate('created_at', $date)
                ->exists();

            if (!$hasActivity) {
                $hasReview = $user->memorizationProgress()
                    ->whereDate('last_review_date', $date)
                    ->exists();

                if (!$hasReview) {
                    break;
                }
            }

            $streak++;
            $date->subDay();
        }

        return $streak;
    }

    private function checkAchievements(User $user): void
    {
        $memorizedCount = $user->memorizationProgress()
            ->where('status', 'memorized')
            ->count();

        $milestones = [
            1 => [
                'ar' => 'حفظت أول آية! بداية رحلة عظيمة',
                'en' => 'You memorized your first ayah! The start of a great journey',
            ],
            10 => [
                'ar' => 'حفظت 10 آيات! استمر في الطريق',
                'en' => 'You memorized 10 ayahs! Keep going',
            ],
            50 => [
                'ar' => 'حفظت 50 آية! إنجاز رائع',
                'en' => 'You memorized 50 ayahs! Amazing achievement',
            ],
            100 => [
                'ar' => 'حفظت 100 آية! ما شاء الله',
                'en' => 'You memorized 100 ayahs! Mashallah',
            ],
            200 => [
                'ar' => 'حفظت 200 آية! ربع الطريق',
                'en' => 'You memorized 200 ayahs! Quarter of the way',
            ],
            500 => [
                'ar' => 'حفظت 500 آية! نصف الطريق تقريباً',
                'en' => 'You memorized 500 ayahs! Almost halfway',
            ],
            1000 => [
                'ar' => 'حفظت 1000 آية! إنجاز استثنائي',
                'en' => 'You memorized 1000 ayahs! Exceptional achievement',
            ],
        ];

        if (isset($milestones[$memorizedCount])) {
            $alreadyNotified = $user->notifications()
                ->where('type', AchievementNotification::class)
                ->whereJsonContains('data->achievement_type', 'ayahs_memorized')
                ->whereJsonContains('data->value', $memorizedCount)
                ->exists();

            if (!$alreadyNotified) {
                $user->notify(new AchievementNotification(
                    'ayahs_memorized',
                    $memorizedCount,
                    $milestones[$memorizedCount]['ar'],
                    $milestones[$memorizedCount]['en']
                ));
            }
        }
    }
}
