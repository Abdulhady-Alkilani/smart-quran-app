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
