TextMatchingService.php

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


-------------------------------------------------------

SpeechToTextService.php

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpeechToTextService
{
    public function transcribe(string $audioPath, string $language = 'ar'): ?string
    {
        $apiUrl = config('ai.api_url');
        $apiKey = config('ai.api_key');

        if (! $apiUrl || ! $apiKey) {
            Log::warning('AI API not configured, using mock transcription');
            return $this->mockTranscription();
        }

        return $this->transcribeViaLiteLLM($audioPath, $apiUrl, $apiKey);
    }

    private function transcribeViaLiteLLM(string $audioPath, string $apiUrl, string $apiKey): ?string
    {
        try {
            if (! file_exists($audioPath)) {
                Log::error('Audio file not found: ' . $audioPath);
                return null;
            }

            $audioData = base64_encode(file_get_contents($audioPath));
            $mimeType = $this->resolveMimeType($audioPath);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-litellm-api-key' => $apiKey,
            ])
            ->timeout(60)
            ->post($apiUrl, [
                'model' => config('ai.model', 'gemini-3-flash-preview'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'أنت خبير في تفريغ التلاوات القرآنية. استمع لهذا المقطع الصوتي واكتب النص القرآني الذي تسمعه بالرسم العثماني بدقة تامة. أعد النص القرآني فقط بدون أي شرح أو تعليق إضافي.',
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$audioData}",
                                ],
                            ],
                        ],
                    ],
                ],
                'max_tokens' => config('ai.max_tokens', 4096),
                'temperature' => 0.1, // Low temperature for accurate transcription
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');
                if ($text) {
                    // Clean up the response - remove any non-Arabic text
                    $text = trim($text);
                    return $text;
                }
            }

            Log::error('LiteLLM transcription failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('LiteLLM transcription error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Resolve MIME type with extension-based correction for audio files
     */
    private function resolveMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeMap = [
            'weba' => 'audio/webm',
            'webm' => 'audio/webm',
            'ogg'  => 'audio/ogg',
            'oga'  => 'audio/ogg',
            'm4a'  => 'audio/mp4',
            'mp3'  => 'audio/mpeg',
            'wav'  => 'audio/wav',
            'flac' => 'audio/flac',
            'aac'  => 'audio/aac',
        ];

        if (isset($mimeMap[$extension])) {
            return $mimeMap[$extension];
        }

        $detected = mime_content_type($filePath);
        // Fix common misdetection: video/webm should be audio/webm for audio files
        if ($detected === 'video/webm') {
            return 'audio/webm';
        }

        return $detected ?: 'audio/webm';
    }

    private function mockTranscription(): string
    {
        return 'بسم الله الرحمن الرحيم';
    }
}