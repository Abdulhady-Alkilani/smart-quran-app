<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpeechToTextService
{
    public function transcribe(string $audioPath, string $language = 'ar'): ?string
    {
        $geminiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        $openaiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');

        if ($openaiKey && $openaiKey !== 'your-openai-api-key-here') {
            return $this->transcribeViaWhisper($audioPath, $language, $openaiKey);
        }

        if ($geminiKey && $geminiKey !== 'your-gemini-api-key-here') {
            return $this->transcribeViaGemini($audioPath, $geminiKey);
        }

        return $this->mockTranscription();
    }

    private function transcribeViaWhisper(string $audioPath, string $language, string $apiKey): ?string
    {
        try {
            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->attach('file', file_get_contents($audioPath), 'audio.webm')
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => 'whisper-1',
                    'language' => $language,
                ]);

            if ($response->successful()) {
                return $response->json('text');
            }
        } catch (\Exception $e) {
            Log::error('Whisper transcription failed: '.$e->getMessage());
        }

        return null;
    }

    private function transcribeViaGemini(string $audioPath, string $apiKey): ?string
    {
        try {
            $audioData = base64_encode(file_get_contents($audioPath));
            $mimeType = mime_content_type($audioPath) ?: 'audio/webm';

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['inline_data' => ['mime_type' => $mimeType, 'data' => $audioData]],
                                ['text' => 'أعد كتابة ما تسمعه في هذا المقطع الصوتي بالضبط. اكتب النص العربي فقط بدون أي شرح إضافي.'],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }
        } catch (\Exception $e) {
            Log::error('Gemini transcription failed: '.$e->getMessage());
        }

        return null;
    }

    private function mockTranscription(): string
    {
        return 'بسم الله الرحمن الرحيم';
    }
}
