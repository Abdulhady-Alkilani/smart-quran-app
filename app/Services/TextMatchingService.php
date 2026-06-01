<?php

namespace App\Services;

class TextMatchingService
{
    public function match(string $transcribed, string $reference): array
    {
        $transcribedNorm = $this->normalize($transcribed);
        $referenceNorm = $this->normalize($reference);

        // التعامل مع الحالات الحدية
        if (empty($transcribedNorm) || empty($referenceNorm)) {
            return [
                'similarity_score' => 0,
                'mistakes_count' => mb_strlen($referenceNorm) ?: 1,
                'is_passed' => false,
                'transcribed_normalized' => $transcribedNorm,
                'reference_normalized' => $referenceNorm,
                'word_diff' => [],
            ];
        }

        // مقارنة كلمة بكلمة (الأساس الحقيقي للتقييم)
        $wordDiff = $this->compareWords($transcribedNorm, $referenceNorm);

        // حساب النسبة من تحليل الكلمات مباشرة
        $wordScore = $this->calculateWordScore($wordDiff);

        // حساب تشابه نصي إضافي كمعيار ثانوي
        $textSimilarity = $this->calculateTextSimilarity($transcribedNorm, $referenceNorm);

        // النسبة النهائية: الأولوية لتحليل الكلمات (80%) مع التشابه النصي (20%)
        $similarity = ($wordScore * 0.8) + ($textSimilarity * 0.2);

        // حساب الأخطاء الفعلية من word_diff
        $mistakesCount = $this->countMistakes($wordDiff);

        return [
            'similarity_score' => round($similarity, 2),
            'mistakes_count' => $mistakesCount,
            'is_passed' => $similarity >= 90,
            'transcribed_normalized' => $transcribedNorm,
            'reference_normalized' => $referenceNorm,
            'word_diff' => $wordDiff,
        ];
    }

    /**
     * حساب نسبة التشابه من تحليل الكلمات
     */
    private function calculateWordScore(array $wordDiff): float
    {
        if (empty($wordDiff)) return 0;

        $totalWeight = 0;
        $correctWeight = 0;

        foreach ($wordDiff as $word) {
            switch ($word['status']) {
                case 'correct':
                    $totalWeight += 1;
                    $correctWeight += 1;
                    break;
                case 'partial':
                    $totalWeight += 1;
                    $correctWeight += 0.7; // كلمة قريبة تحصل على 70%
                    break;
                case 'wrong':
                    $totalWeight += 1;
                    $correctWeight += 0; // كلمة خاطئة
                    break;
                case 'missing':
                    $totalWeight += 1;
                    $correctWeight += 0; // كلمة ناقصة
                    break;
                case 'extra':
                    // الكلمات الزائدة تخصم قليلاً لكن لا تضاف للمجموع الكلي
                    $totalWeight += 0.3;
                    $correctWeight += 0;
                    break;
            }
        }

        return $totalWeight > 0 ? ($correctWeight / $totalWeight) * 100 : 0;
    }

    /**
     * حساب تشابه نصي (similar_text) بدون اعتماد على levenshtein المحدودة
     */
    private function calculateTextSimilarity(string $transcribed, string $reference): float
    {
        $similarity = 0.0;
        similar_text($transcribed, $reference, $similarity);
        return $similarity;
    }

    /**
     * حساب عدد الأخطاء الفعلية من word_diff
     */
    private function countMistakes(array $wordDiff): int
    {
        $mistakes = 0;
        foreach ($wordDiff as $word) {
            if (in_array($word['status'], ['wrong', 'missing', 'extra'])) {
                $mistakes++;
            } elseif ($word['status'] === 'partial') {
                // الكلمة القريبة تُعتبر نصف خطأ، لكن نعدها خطأ واحداً عند العرض
                $mistakes++;
            }
        }
        return $mistakes;
    }

    /**
     * مقارنة كلمة بكلمة بين النص المُسجَّل والنص المرجعي مع المحاذاة (Alignment)
     */
    private function compareWords(string $transcribed, string $reference): array
    {
        $refWords = preg_split('/\s+/', $reference, -1, PREG_SPLIT_NO_EMPTY);
        $transWords = preg_split('/\s+/', $transcribed, -1, PREG_SPLIT_NO_EMPTY);
        
        $n = count($refWords);
        $m = count($transWords);
        
        if ($n === 0 && $m === 0) return [];

        // DP matrix
        $dp = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));
        
        for ($i = 0; $i <= $n; $i++) $dp[$i][0] = $i;
        for ($j = 0; $j <= $m; $j++) $dp[0][$j] = $j;
        
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $m; $j++) {
                $refWord = $refWords[$i - 1];
                $transWord = $transWords[$j - 1];
                
                $cost = 1;
                if ($refWord === $transWord) {
                    $cost = 0;
                } else {
                    $sim = $this->wordSimilarity($refWord, $transWord);
                    if ($sim >= 65) $cost = 0.5; // partial match
                }
                
                $dp[$i][$j] = min(
                    $dp[$i - 1][$j] + 1,      // deletion (missing in trans)
                    $dp[$i][$j - 1] + 1,      // insertion (extra in trans)
                    $dp[$i - 1][$j - 1] + $cost // substitution
                );
            }
        }
        
        // Backtrack
        $i = $n;
        $j = $m;
        $result = [];
        
        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0) {
                $refWord = $refWords[$i - 1];
                $transWord = $transWords[$j - 1];
                $cost = 1;
                $isPartial = false;
                
                if ($refWord === $transWord) {
                    $cost = 0;
                } else {
                    $sim = $this->wordSimilarity($refWord, $transWord);
                    if ($sim >= 65) {
                        $cost = 0.5;
                        $isPartial = true;
                    }
                }
                
                if ($dp[$i][$j] == $dp[$i - 1][$j - 1] + $cost) {
                    $status = $cost == 0 ? 'correct' : ($isPartial ? 'partial' : 'wrong');
                    array_unshift($result, [
                        'status' => $status,
                        'expected' => $refWord,
                        'got' => $transWord,
                    ]);
                    $i--;
                    $j--;
                    continue;
                }
            }
            
            if ($i > 0 && ($j === 0 || $dp[$i][$j] == $dp[$i - 1][$j] + 1)) {
                array_unshift($result, [
                    'status' => 'missing',
                    'expected' => $refWords[$i - 1],
                    'got' => '',
                ]);
                $i--;
            } else if ($j > 0 && ($i === 0 || $dp[$i][$j] == $dp[$i][$j - 1] + 1)) {
                array_unshift($result, [
                    'status' => 'extra',
                    'expected' => '',
                    'got' => $transWords[$j - 1],
                ]);
                $j--;
            }
        }
        
        return $result;
    }

    /**
     * حساب تشابه كلمتين بأكثر من طريقة وأخذ الأفضل
     */
    private function wordSimilarity(string $word1, string $word2): float
    {
        // similar_text
        $sim1 = 0.0;
        similar_text($word1, $word2, $sim1);

        // Levenshtein-based (آمن لأن الكلمات قصيرة)
        $maxLen = max(mb_strlen($word1), mb_strlen($word2));
        if ($maxLen > 0 && $maxLen <= 255) {
            $lev = levenshtein($word1, $word2);
            $sim2 = (1 - ($lev / $maxLen)) * 100;
        } else {
            $sim2 = $sim1;
        }

        // أعلى قيمة
        return max($sim1, $sim2);
    }

    private function normalize(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u', '', $text);
        $text = str_replace(['ٱ', 'إ', 'أ', 'آ'], 'ا', $text);
        $text = str_replace('ة', 'ه', $text);
        $text = str_replace('ى', 'ي', $text);
        // Remove Arabic-Indic and Latin digits
        $text = preg_replace('/[٠-٩0-9١-٩]/u', '', $text);
        $text = preg_replace('/[^\p{Arabic}\s]/u', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
    }
}
