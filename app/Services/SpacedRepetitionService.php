<?php

namespace App\Services;

use App\Models\UserMemorizationProgress;

class SpacedRepetitionService
{
    public function calculateNextReview(UserMemorizationProgress $progress, float $similarityScore): array
    {
        $quality = $this->scoreToQuality($similarityScore);

        $currentFactor = $progress->easiness_factor ?? 2.5;
        $newFactor = max(1.3, $currentFactor + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02)));

        $repetitionCount = $progress->repetition_count ?? 0;

        if ($quality < 3) {
            $interval = 1;
            $repetitionCount = 0;
        } elseif ($repetitionCount == 0) {
            $interval = 1;
            $repetitionCount = 1;
        } elseif ($repetitionCount == 1) {
            $interval = 6;
            $repetitionCount = 2;
        } else {
            $interval = round($progress->interval_days * $newFactor);
            $repetitionCount++;
        }

        $status = $similarityScore >= 95 ? 'memorized' : 'learning';

        return [
            'repetition_count' => $repetitionCount,
            'easiness_factor' => round($newFactor, 4),
            'interval_days' => $interval,
            'last_review_date' => now(),
            'next_review_date' => now()->addDays($interval),
            'status' => $status,
        ];
    }

    private function scoreToQuality(float $score): int
    {
        if ($score >= 95) {
            return 5;
        }
        if ($score >= 90) {
            return 4;
        }
        if ($score >= 80) {
            return 3;
        }
        if ($score >= 70) {
            return 2;
        }
        if ($score >= 50) {
            return 1;
        }

        return 0;
    }
}
