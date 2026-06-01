<?php

namespace App\Services;

use App\Models\Ayah;
use App\Models\Surah;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class McqQuizService
{
    public function generate(Surah $surah): array
    {
        $apiUrl = config('ai.api_url');
        $apiKey = config('ai.api_key');

        if ($apiUrl && $apiKey) {
            $questions = $this->generateViaAI($surah, $apiUrl, $apiKey);
            if (!empty($questions)) {
                return $questions;
            }
        }

        return $this->generateLocal($surah);
    }

    private function generateViaAI(Surah $surah, string $apiUrl, string $apiKey): array
    {
        $sampleAyahs = $surah->ayahs()
            ->where('number_in_surah', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        if ($sampleAyahs->count() < 3) {
            return [];
        }

        $ayahsText = '';
        foreach ($sampleAyahs as $a) {
            $ayahsText .= "آية {$a->number_in_surah}: {$a->text_uthmani}\n";
        }

        $prompt = <<<PROMPT
أنت خبير في علوم القرآن الكريم وحافظ متقن ومختص في اختبار حفظة القرآن.

مطلوب منك توليد اختبار من متعدد (Multiple Choice Quiz) مكون من 5 أسئلة باللغة العربية لاختبار حفظ المستخدم لسورة {$surah->name_ar}.

القيود والشروط الصارمة:
1. يمنع منعاً باتاً طرح أي سؤال يطلب "رقم الآية" أو "عدد آيات السورة".
2. يجب أن تتدرج مستويات الصعوبة (سهل، متوسط، صعب، تحدي).
3. يجب أن يكون لكل سؤال 4 خيارات.

أنواع الأسئلة المطلوبة (استخدمها لتنويع الاختبار):
- [سهل - إكمال الكلمة]: أعطِ آية ينقصها كلمة واحدة، واطلب اختيار الكلمة الصحيحة.
- [متوسط - الآية التالية]: اذكر آية، واطلب اختيار الآية التي تليها مباشرة.
- [متوسط - معاني المفردات]: اسأل عن معنى كلمة غريبة في آية معينة.
- [صعب - خواتيم الآيات]: اذكر بداية آية، واطلب اختيار الخاتمة الصحيحة.
- [صعب - المتشابهات اللفظية]: اذكر آية تتشابه مع آية أخرى في سورة مختلفة، واطلب تحديد السورة الصحيحة.
- [تحدي - الآية السابقة]: اذكر آية، واطلب اختيار الآية التي تسبقها.

إليك بعض آيات سورة {$surah->name_ar}:
{$ayahsText}

أعد الإجابة بصيغة JSON فقط (بدون أي نصوص إضافية أو Markdown) كالتالي:
[
  {
    "difficulty": "صعب",
    "type": "المتشابهات اللفظية",
    "question": "نص السؤال هنا",
    "options": ["خيار 1", "خيار 2", "خيار 3", "خيار 4"],
    "correct_answer": "الخيار الصحيح نصاً",
    "explanation": "شرح قصير للإجابة"
  }
]
PROMPT;

        try {
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
                        'content' => 'أنت خبير في علوم القرآن الكريم وحافظ متقن ومختص في اختبار حفظة القرآن. أجب دائماً بصيغة JSON فقط بدون أي نص إضافي أو Markdown.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 4096,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');

                $text = preg_replace('/```json\s*/', '', $text);
                $text = preg_replace('/```\s*/', '', $text);
                $text = trim($text);

                $data = json_decode($text, true);

                if (is_array($data) && count($data) >= 3) {
                    $questions = [];
                    foreach ($data as $i => $q) {
                        if (!isset($q['question'], $q['options'], $q['correct_answer'])) {
                            continue;
                        }
                        $questions[] = [
                            'id' => 'mcq_' . $surah->id . '_' . $i,
                            'difficulty' => $q['difficulty'] ?? 'متوسط',
                            'type' => $q['type'] ?? 'عام',
                            'question_text' => $q['question'],
                            'shown_text' => null,
                            'correct_answer' => $q['correct_answer'],
                            'options' => $q['options'],
                            'explanation' => $q['explanation'] ?? null,
                        ];
                    }

                    if (count($questions) >= 3) {
                        return $questions;
                    }
                }

                Log::warning('MCQ Quiz AI response invalid', ['raw' => $text]);
            } else {
                Log::error('MCQ Quiz AI request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('MCQ Quiz generation failed: ' . $e->getMessage());
        }

        return [];
    }

    private function generateLocal(Surah $surah): array
    {
        $questions = [];

        $questions[] = $this->generateCompleteWord($surah);

        $questions[] = $this->generateNextAyah($surah);

        if ($questions[1] !== null) {
            $questions[] = $this->generatePreviousAyah($surah);
        }

        $questions[] = $this->generateAyahEndings($surah);

        $questions[] = $this->generateSimilarVerses($surah);

        $questions = array_filter($questions);
        $questions = array_values($questions);

        if (count($questions) < 3) {
            $questions[] = $this->generateVocabulary($surah);
            $questions = array_filter($questions);
            $questions = array_values($questions);
        }

        foreach ($questions as $i => &$q) {
            $q['id'] = 'mcq_local_' . $surah->id . '_' . $i;
        }
        unset($q);

        return $questions;
    }

    private function generateCompleteWord(Surah $surah): ?array
    {
        $ayah = $surah->ayahs()
            ->where('number_in_surah', '>', 0)
            ->inRandomOrder()
            ->first();

        if (!$ayah) return null;

        $words = explode(' ', $ayah->text_uthmani);
        $totalWords = count($words);
        if ($totalWords < 4) return null;

        $targetIndex = rand(1, $totalWords - 2);
        $correctWord = $words[$targetIndex];

        $shownWords = $words;
        $shownWords[$targetIndex] = '______';
        $shownText = implode(' ', $shownWords);

        $wrongWords = \App\Models\Ayah::where('surah_id', $surah->id)
            ->where('id', '!=', $ayah->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        $options = [$correctWord];
        foreach ($wrongWords as $w) {
            $wWords = explode(' ', $w->text_uthmani);
            $randWord = $wWords[array_rand($wWords)];
            if ($randWord !== $correctWord && !in_array($randWord, $options)) {
                $options[] = $randWord;
            }
            if (count($options) >= 4) break;
        }

        while (count($options) < 4) {
            $offset = rand(1, 3);
            $idx = $targetIndex + $offset;
            if ($idx < $totalWords && $words[$idx] !== $correctWord && !in_array($words[$idx], $options)) {
                $options[] = $words[$idx];
            } else {
                $options[] = '...';
            }
        }

        shuffle($options);

        return [
            'difficulty' => 'سهل',
            'type' => 'إكمال الكلمة',
            'question_text' => 'ما هي الكلمة الناقصة في الآية التالية من سورة ' . $surah->name_ar . '؟',
            'shown_text' => $shownText,
            'correct_answer' => $correctWord,
            'options' => array_slice($options, 0, 4),
            'explanation' => 'الكلمة الناقصة هي: ' . $correctWord . ' — الآية: ' . $ayah->text_uthmani,
        ];
    }

    private function generateNextAyah(Surah $surah): ?array
    {
        $maxNum = $surah->total_ayahs;
        $randomNum = rand(1, max(1, $maxNum - 1));

        $ayah = $surah->ayahs()->where('number_in_surah', $randomNum)->first();
        $nextAyah = $surah->ayahs()->where('number_in_surah', $randomNum + 1)->first();

        if (!$ayah || !$nextAyah) return null;

        $options = [$nextAyah->text_uthmani];
        $wrongAyahs = \App\Models\Ayah::where('surah_id', '!=', $surah->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        foreach ($wrongAyahs as $w) {
            if (!in_array($w->text_uthmani, $options)) {
                $options[] = $w->text_uthmani;
            }
            if (count($options) >= 4) break;
        }

        shuffle($options);

        return [
            'difficulty' => 'متوسط',
            'type' => 'الآية التالية',
            'question_text' => 'ما هي الآية التي تلي هذه الآية مباشرة في سورة ' . $surah->name_ar . '؟',
            'shown_text' => $ayah->text_uthmani,
            'correct_answer' => $nextAyah->text_uthmani,
            'options' => array_slice($options, 0, 4),
            'explanation' => 'الآية التالية هي: ' . $nextAyah->text_uthmani,
        ];
    }

    private function generatePreviousAyah(Surah $surah): ?array
    {
        $randomNum = rand(2, max(2, $surah->total_ayahs));

        $ayah = $surah->ayahs()->where('number_in_surah', $randomNum)->first();
        $prevAyah = $surah->ayahs()->where('number_in_surah', $randomNum - 1)->first();

        if (!$ayah || !$prevAyah) return null;

        $options = [$prevAyah->text_uthmani];
        $wrongAyahs = \App\Models\Ayah::where('surah_id', '!=', $surah->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        foreach ($wrongAyahs as $w) {
            if (!in_array($w->text_uthmani, $options)) {
                $options[] = $w->text_uthmani;
            }
            if (count($options) >= 4) break;
        }

        shuffle($options);

        return [
            'difficulty' => 'تحدي',
            'type' => 'الآية السابقة',
            'question_text' => 'ما هي الآية التي تسبق هذه الآية مباشرة في سورة ' . $surah->name_ar . '؟',
            'shown_text' => $ayah->text_uthmani,
            'correct_answer' => $prevAyah->text_uthmani,
            'options' => array_slice($options, 0, 4),
            'explanation' => 'الآية السابقة هي: ' . $prevAyah->text_uthmani,
        ];
    }

    private function generateAyahEndings(Surah $surah): ?array
    {
        $ayah = $surah->ayahs()
            ->where('number_in_surah', '>', 0)
            ->inRandomOrder()
            ->first();

        if (!$ayah) return null;

        $words = explode(' ', $ayah->text_uthmani);
        $totalWords = count($words);
        if ($totalWords < 3) return null;

        $endCut = min(3, max(1, (int) floor($totalWords * 0.3)));
        $correctEnding = implode(' ', array_slice($words, -$endCut));
        $shownBeginning = implode(' ', array_slice($words, 0, $totalWords - $endCut)) . ' ...';

        $otherEndings = \App\Models\Ayah::where('surah_id', $surah->id)
            ->where('id', '!=', $ayah->id)
            ->inRandomOrder()
            ->take(10)
            ->get();

        $options = [$correctEnding];
        foreach ($otherEndings as $w) {
            $wWords = explode(' ', $w->text_uthmani);
            $wEnd = implode(' ', array_slice($wWords, -$endCut));
            if ($wEnd !== $correctEnding && !in_array($wEnd, $options)) {
                $options[] = $wEnd;
            }
            if (count($options) >= 4) break;
        }

        while (count($options) < 4) {
            $options[] = '...';
        }

        shuffle($options);

        return [
            'difficulty' => 'صعب',
            'type' => 'خواتيم الآيات',
            'question_text' => 'بماذا تختم هذه الآية من سورة ' . $surah->name_ar . '؟',
            'shown_text' => $shownBeginning,
            'correct_answer' => $correctEnding,
            'options' => array_slice($options, 0, 4),
            'explanation' => 'خاتمة الآية: ' . $correctEnding . ' — الآية كاملة: ' . $ayah->text_uthmani,
        ];
    }

    private function generateSimilarVerses(Surah $surah): ?array
    {
        $ayah = $surah->ayahs()
            ->where('number_in_surah', '>', 0)
            ->inRandomOrder()
            ->first();

        if (!$ayah) return null;

        $correctSurah = $surah->name_ar;
        $otherSurahs = Surah::where('id', '!=', $surah->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        $options = [$correctSurah];
        foreach ($otherSurahs as $s) {
            $options[] = $s->name_ar;
        }

        shuffle($options);

        return [
            'difficulty' => 'صعب',
            'type' => 'المتشابهات اللفظية',
            'question_text' => 'في أي سورة توجد هذه الآية؟',
            'shown_text' => $ayah->text_uthmani,
            'correct_answer' => $correctSurah,
            'options' => array_slice($options, 0, 4),
            'explanation' => 'هذه الآية من سورة ' . $correctSurah . ' (الآية ' . $ayah->number_in_surah . ')',
        ];
    }

    private function generateVocabulary(Surah $surah): ?array
    {
        $ayah = $surah->ayahs()
            ->where('number_in_surah', '>', 0)
            ->inRandomOrder()
            ->first();

        if (!$ayah) return null;

        $options = [
            'معنى صحيح (تقريبي)',
            'معنى خاطئ 1',
            'معنى خاطئ 2',
            'معنى خاطئ 3',
        ];

        return [
            'difficulty' => 'متوسط',
            'type' => 'معاني المفردات',
            'question_text' => 'ما معنى كلمة في آية من سورة ' . $surah->name_ar . '؟',
            'shown_text' => $ayah->text_uthmani,
            'correct_answer' => $options[0],
            'options' => $options,
            'explanation' => 'سؤال معاني المفردات - يُفضل تفعيل مزود الذكاء الاصطناعي للحصول على أسئلة أدق.',
        ];
    }
}
