<?php

namespace App\Services;

use App\Models\Ayah;
use App\Models\GeneratedQuestion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuizGeneratorService
{
    /**
     * Generate a quiz question for a given Ayah using AI
     */
    public function generateForAyah(Ayah $ayah): ?GeneratedQuestion
    {
        $apiUrl = config('ai.api_url');
        $apiKey = config('ai.api_key');

        if (! $apiUrl || ! $apiKey) {
            return $this->generateMockQuestion($ayah);
        }

        try {
            $prompt = "أنشئ سؤال اختيار من متعدد باللغة العربية عن الآية التالية:\n";
            $prompt .= "الآية: {$ayah->text_uthmani}\n";
            $prompt .= "سورة: {$ayah->surah->name_ar} - آية رقم {$ayah->number_in_surah}\n";
            $prompt .= "أنواع الأسئلة الممكنة: (ما السورة التي تحتوي هذه الآية؟ / ما الآية التي تلي هذه الآية؟ / ما معنى كلمة ... في الآية؟ / اختر ترتيب هذه الآية في السورة)\n";
            $prompt .= 'أعد الإجابة بصيغة JSON فقط بدون أي نص إضافي هكذا: {"question": "...", "options": ["أ) ...", "ب) ...", "ج) ...", "د) ..."], "correct": "الإجابة الصحيحة الكاملة مع الحرف"}';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-litellm-api-key' => $apiKey,
            ])
            ->timeout(30)
            ->post($apiUrl, [
                'model' => config('ai.model', 'gemini-3-flash-preview'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت معلم قرآن متخصص في إنشاء أسئلة اختبارية عن القرآن الكريم. أجب دائماً بصيغة JSON فقط.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 1024,
                'temperature' => 0.8,
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');

                // Clean JSON from markdown code blocks if present
                $text = preg_replace('/```json\s*/', '', $text);
                $text = preg_replace('/```\s*/', '', $text);
                $text = trim($text);

                $data = json_decode($text, true);

                if ($data && isset($data['question'], $data['options'], $data['correct'])) {
                    return GeneratedQuestion::create([
                        'surah_id' => $ayah->surah_id,
                        'ayah_id' => $ayah->id,
                        'question_text' => $data['question'],
                        'options' => $data['options'],
                        'correct_answer' => $data['correct'],
                    ]);
                }

                Log::warning('Quiz AI response invalid JSON', ['raw' => $text]);
            } else {
                Log::error('Quiz AI request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Quiz generation failed: ' . $e->getMessage());
        }

        return $this->generateMockQuestion($ayah);
    }

    private function generateMockQuestion(Ayah $ayah): GeneratedQuestion
    {
        $options = [
            "الآية {$ayah->number_in_surah} من سورة {$ayah->surah->name_ar}",
            'الآية ' . ($ayah->number_in_surah + 1) . " من سورة {$ayah->surah->name_ar}",
            "الآية {$ayah->number_in_surah} من سورة البقرة",
            'الآية 1 من سورة الفاتحة',
        ];

        return GeneratedQuestion::create([
            'surah_id' => $ayah->surah_id,
            'ayah_id' => $ayah->id,
            'question_text' => "ما هي الآية التالية: \"{$ayah->text_uthmani}\"؟",
            'options' => $options,
            'correct_answer' => $options[0],
        ]);
    }
}
