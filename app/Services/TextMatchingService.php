<?php

namespace App\Services;

class TextMatchingService
{
    public function match(string $transcribed, string $reference): array
    {
        $transcribed = $this->normalize($transcribed);
        $reference = $this->normalize($reference);

        // التعامل مع الحالات الحدية
        if (empty($transcribed) || empty($reference)) {
            return [
                'similarity_score' => 0,
                'mistakes_count' => mb_strlen($reference) ?: 1,
                'is_passed' => false,
                'transcribed_normalized' => $transcribed,
                'reference_normalized' => $reference,
            ];
        }

        // حساب التشابه بطريقتين وأخذ الأفضل
        $similarity1 = 0.0;
        similar_text($transcribed, $reference, $similarity1);

        // طريقة ثانية: Levenshtein-based similarity
        $maxLen = max(mb_strlen($transcribed), mb_strlen($reference));
        $levenshtein = levenshtein(
            mb_substr($transcribed, 0, 255),
            mb_substr($reference, 0, 255)
        );
        $similarity2 = $maxLen > 0 ? (1 - ($levenshtein / $maxLen)) * 100 : 0;

        // أخذ المتوسط المرجح (similar_text أدق للنصوص العربية الطويلة)
        $similarity = ($similarity1 * 0.6 + $similarity2 * 0.4);

        $mistakesCount = $maxLen > 0 ? (int) round($levenshtein / max(1, $maxLen / 10)) : 0;

        return [
            'similarity_score' => round($similarity, 2),
            'mistakes_count' => $mistakesCount,
            'is_passed' => $similarity >= 90,
            'transcribed_normalized' => $transcribed,
            'reference_normalized' => $reference,
        ];
    }

    private function normalize(string $text): string
    {
        // إزالة HTML
        $text = strip_tags($text);

        // إزالة التشكيل والعلامات الصوتية
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u', '', $text);

        // توحيد الألفات
        $text = str_replace(['ٱ', 'إ', 'أ', 'آ'], 'ا', $text);

        // توحيد التاء المربوطة والألف المقصورة
        $text = str_replace('ة', 'ه', $text);
        $text = str_replace('ى', 'ي', $text);

        // إزالة علامات الترقيم
        $text = preg_replace('/[^\p{Arabic}\s]/u', '', $text);

        // تنظيف المسافات
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
    }
}
