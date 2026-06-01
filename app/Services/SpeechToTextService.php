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

            set_time_limit(120); // زيادة وقت التنفيذ لتجنب خطأ Timeout

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-litellm-api-key' => $apiKey,
            ])
            ->timeout(45)
            ->post($apiUrl, [
                'model' => config('ai.model', 'gemini-3-flash-preview'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت نظام تفريغ صوتي حرفي. مهمتك الوحيدة هي كتابة ما تسمعه بالضبط. ممنوع عليك تصحيح أي خطأ أو تعديل أي كلمة. أنت لست مراجعاً قرآنياً بل مسجل صوت فقط.',
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'فرّغ هذا المقطع الصوتي حرفياً. اكتب كل كلمة كما نُطقت بالضبط بدون أي تصحيح. القواعد: 1) اكتب بالإملاء البسيط بدون تشكيل. 2) لا تصحح أي خطأ نطق. 3) لا تكمل أي جملة ناقصة. 4) لا تضف أي كلمة لم تُنطق. 5) إذا سمعت كلمة خاطئة اكتبها خاطئة كما هي. مثال: إذا نطق "العلمين" اكتب "العلمين" ولا تكتب "العالمين". أعد فقط النص المسموع.',
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
                'temperature' => 0.0,
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');
                if ($text) {
                    $text = $this->cleanTranscription($text);
                    Log::info('Transcription result', ['raw' => $response->json('choices.0.message.content'), 'cleaned' => $text]);
                    return $text ?: null;
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
     * تنظيف النص المفرّغ من الذكاء الاصطناعي
     */
    private function cleanTranscription(string $text): string
    {
        $text = trim($text);
        // إزالة التشكيل
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u', '', $text);
        // توحيد الألف
        $text = str_replace(['ٱ', 'إ', 'أ', 'آ'], 'ا', $text);
        // إزالة أي أحرف غير عربية وغير مسافات
        $text = preg_replace('/[^\p{Arabic}\s]/u', '', $text);
        // تنظيف المسافات
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
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
