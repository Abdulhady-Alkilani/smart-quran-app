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

        // حساب التشابه بطريقتين وأخذ الأفضل
        $similarity1 = 0.0;
        similar_text($transcribedNorm, $referenceNorm, $similarity1);

        $maxLen = max(mb_strlen($transcribedNorm), mb_strlen($referenceNorm));
        $levenshtein = levenshtein(
            mb_substr($transcribedNorm, 0, 255),
            mb_substr($referenceNorm, 0, 255)
        );
        $similarity2 = $maxLen > 0 ? (1 - ($levenshtein / $maxLen)) * 100 : 0;

        $similarity = ($similarity1 * 0.6 + $similarity2 * 0.4);
        $mistakesCount = $maxLen > 0 ? (int) round($levenshtein / max(1, $maxLen / 10)) : 0;

        // مقارنة كلمة بكلمة
        $wordDiff = $this->compareWords($transcribedNorm, $referenceNorm);

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
     * مقارنة كلمة بكلمة بين النص المُسجَّل والنص المرجعي
     */
    private function compareWords(string $transcribed, string $reference): array
    {
        $refWords = preg_split('/\s+/', $reference);
        $transWords = preg_split('/\s+/', $transcribed);
        $result = [];
        $transCount = count($transWords);

        foreach ($refWords as $i => $refWord) {
            if ($i < $transCount) {
                $transWord = $transWords[$i];
                if ($refWord === $transWord) {
                    $result[] = [
                        'status' => 'correct',
                        'expected' => $refWord,
                        'got' => $transWord,
                    ];
                } else {
                    // حساب التشابه على مستوى الكلمة
                    $wordSim = 0.0;
                    similar_text($transWord, $refWord, $wordSim);
                    $result[] = [
                        'status' => $wordSim >= 70 ? 'partial' : 'wrong',
                        'expected' => $refWord,
                        'got' => $transWord,
                    ];
                }
            } else {
                // كلمة مفقودة - لم يقلها الطالب
                $result[] = [
                    'status' => 'missing',
                    'expected' => $refWord,
                    'got' => '',
                ];
            }
        }

        // كلمات زائدة قالها الطالب ولم تكن في الآية
        for ($i = count($refWords); $i < $transCount; $i++) {
            $result[] = [
                'status' => 'extra',
                'expected' => '',
                'got' => $transWords[$i],
            ];
        }

        return $result;
    }

    private function normalize(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u', '', $text);
        $text = str_replace(['ٱ', 'إ', 'أ', 'آ'], 'ا', $text);
        $text = str_replace('ة', 'ه', $text);
        $text = str_replace('ى', 'ي', $text);
        $text = preg_replace('/[^\p{Arabic}\s]/u', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
    }
}
