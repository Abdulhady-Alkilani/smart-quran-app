<?php

namespace App\Services;

use App\Models\Ayah;
use App\Models\GeneratedQuestion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuizGeneratorService
{
    public function generateForAyah(Ayah $ayah): ?GeneratedQuestion
    {
        $geminiKey = env('GEMINI_API_KEY');

        if (! $geminiKey || $geminiKey === 'your-gemini-api-key-here') {
            return $this->generateMockQuestion($ayah);
        }

        try {
            $prompt = "أنشئ سؤال اختيار من متعدد باللغة العربية عن الآية التالية:\n";
            $prompt .= "الآية: {$ayah->text_uthmani}\n";
            $prompt .= "سورة: {$ayah->surah->name_ar} - آية رقم {$ayah->number_in_surah}\n";
            $prompt .= 'أعد الإجابة بصيغة JSON فقط هكذا: {"question": "...", "options": ["أ)...", "ب)...", "ج)...", "د)..."], "correct": "الإجابة الصحيحة الكاملة"}';

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$geminiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
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
            }
        } catch (\Exception $e) {
            Log::error('Quiz generation failed: '.$e->getMessage());
        }

        return $this->generateMockQuestion($ayah);
    }

    private function generateMockQuestion(Ayah $ayah): GeneratedQuestion
    {
        $options = [
            "الآية {$ayah->number_in_surah} من سورة {$ayah->surah->name_ar}",
            'الآية '.($ayah->number_in_surah + 1)." من سورة {$ayah->surah->name_ar}",
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
