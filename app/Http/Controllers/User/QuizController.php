<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneratedQuestion;
use App\Models\Surah;
use App\Models\UserQuizAttempt;
use App\Services\McqQuizService;
use App\Services\QuizGeneratorService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Surah $surah)
    {
        $questions = [];

        // Q1: Revelation Type
        $correctType = $surah->revelation_type === 'Meccan' ? 'مكية' : 'مدنية';
        $options = ['مكية', 'مدنية'];
        $questions[] = [
            'id' => 'q_type_' . $surah->id,
            'question_text' => 'هل سورة ' . $surah->name_ar . ' مكية أم مدنية؟',
            'shown_text' => null,
            'correct_answer' => $correctType,
            'options' => $options,
            'ayah_number' => null,
        ];

        // Q2: Total Ayahs
        $correctCount = (string)$surah->total_ayahs;
        $options = [$correctCount];
        while (count($options) < 4) {
            $rand = (string)max(1, $surah->total_ayahs + rand(-5, 10));
            if (!in_array($rand, $options)) $options[] = $rand;
        }
        shuffle($options);
        $questions[] = [
            'id' => 'q_count_' . $surah->id,
            'question_text' => 'كم عدد آيات سورة ' . $surah->name_ar . '؟',
            'shown_text' => null,
            'correct_answer' => $correctCount,
            'options' => $options,
            'ayah_number' => null,
        ];

        // Ayah based questions
        $ayahs = $surah->ayahs()->where('number_in_surah', '>', 0)->inRandomOrder()->take(3)->get();
        foreach ($ayahs as $ayah) {
            $type = rand(0, 1); // 0: Next Ayah, 1: Complete
            $isLastAyah = $ayah->number_in_surah == $surah->total_ayahs;

            if ($type == 0 && !$isLastAyah) {
                // Next Ayah Question
                $nextAyah = $surah->ayahs()->where('number_in_surah', $ayah->number_in_surah + 1)->first();
                if ($nextAyah) {
                    $options = [$nextAyah->text_uthmani];
                    $wrongAyahs = \App\Models\Ayah::where('surah_id', '!=', $surah->id)
                        ->where('number_in_surah', '>', 1)
                        ->inRandomOrder()
                        ->take(3)->get();
                    foreach ($wrongAyahs as $w) $options[] = $w->text_uthmani;
                    shuffle($options);

                    $questions[] = [
                        'id' => 'q_next_' . $ayah->id,
                        'question_text' => 'ما هي الآية التي تلي هذه الآية؟',
                        'shown_text' => $ayah->text_uthmani,
                        'correct_answer' => $nextAyah->text_uthmani,
                        'options' => $options,
                        'ayah_number' => $ayah->number_in_surah,
                    ];
                    continue;
                }
            }

            // Complete Ayah Question
            $words = explode(' ', $ayah->text_uthmani);
            if (count($words) >= 4) {
                $splitAt = (int) ceil(count($words) / 2);
                $shownPart = implode(' ', array_slice($words, 0, $splitAt));
                $correctPart = implode(' ', array_slice($words, $splitAt));

                $wrongAyahs = \App\Models\Ayah::where('surah_id', '!=', $surah->id)
                    ->inRandomOrder()->take(3)->get();
                $options = [$correctPart];
                foreach ($wrongAyahs as $w) {
                    $wWords = explode(' ', $w->text_uthmani);
                    $wSplit = (int) ceil(count($wWords) / 2);
                    $options[] = implode(' ', array_slice($wWords, $wSplit));
                }
                shuffle($options);

                $questions[] = [
                    'id' => 'q_comp_' . $ayah->id,
                    'question_text' => 'أكمل الآية التالية:',
                    'shown_text' => $shownPart . ' ...',
                    'correct_answer' => $correctPart,
                    'options' => $options,
                    'ayah_number' => $ayah->number_in_surah,
                ];
            }
        }

        return view('user.quiz.show', compact('surah', 'questions'));
    }

    public function submit(Request $request, GeneratedQuestion $question)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $isCorrect = trim($request->answer) === trim($question->correct_answer);

        UserQuizAttempt::create([
            'user_id' => $request->user()->id,
            'question_id' => $question->id,
            'user_answer' => $request->answer,
            'is_correct' => $isCorrect,
        ]);

        return back()->with('result', [
            'isCorrect' => $isCorrect,
            'correctAnswer' => $question->correct_answer,
        ]);
    }

    public function mcqQuiz(Surah $surah)
    {
        $mcqService = new McqQuizService();
        $questions = $mcqService->generate($surah);

        if (empty($questions)) {
            return back()->with('error', 'لا توجد آيات كافية في هذه السورة لإنشاء اختبار التحدي.');
        }

        return view('user.quiz.mcq', compact('surah', 'questions'));
    }

    /**
     * Complete the Ayah quiz - locally generated, no AI needed
     */
    public function completeAyah(Surah $surah)
    {
        $ayahs = $surah->ayahs()->where('number_in_surah', '>', 1)->inRandomOrder()->take(5)->get();

        if ($ayahs->count() < 3) {
            return back()->with('error', 'لا توجد آيات كافية في هذه السورة لإنشاء اختبار إكمال الآية.');
        }

        $questions = [];
        foreach ($ayahs as $ayah) {
            $text = $ayah->text_uthmani;
            $words = explode(' ', $text);
            $totalWords = count($words);

            if ($totalWords < 4) continue;

            // Show first half, hide second half
            $splitAt = (int) ceil($totalWords / 2);
            $shownPart = implode(' ', array_slice($words, 0, $splitAt));
            $correctPart = implode(' ', array_slice($words, $splitAt));

            // Get 3 wrong options from other ayahs in same surah
            $wrongAyahs = $surah->ayahs()
                ->where('id', '!=', $ayah->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $options = [$correctPart];
            foreach ($wrongAyahs as $wrongAyah) {
                $wWords = explode(' ', $wrongAyah->text_uthmani);
                $wTotal = count($wWords);
                $wSplit = (int) ceil($wTotal / 2);
                $options[] = implode(' ', array_slice($wWords, $wSplit));
            }

            // Fill up if needed
            while (count($options) < 4) {
                $options[] = '...';
            }

            shuffle($options);

            $questions[] = [
                'id' => $ayah->id,
                'shown_text' => $shownPart . ' ...',
                'correct_answer' => $correctPart,
                'options' => array_slice($options, 0, 4),
                'surah_name' => $surah->name_ar,
                'ayah_number' => $ayah->number_in_surah,
            ];
        }

        return view('user.quiz.complete', compact('surah', 'questions'));
    }
}
