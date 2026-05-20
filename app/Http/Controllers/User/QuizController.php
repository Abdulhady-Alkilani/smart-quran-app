<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneratedQuestion;
use App\Models\Surah;
use App\Models\UserQuizAttempt;
use App\Services\QuizGeneratorService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Surah $surah, QuizGeneratorService $quizService)
    {
        $questions = $surah->generatedQuestions()->inRandomOrder()->take(5)->get();

        if ($questions->count() < 5) {
            $existingCount = $questions->count();
            $ayahs = $surah->ayahs()->inRandomOrder()->take(5 - $existingCount)->get();

            foreach ($ayahs as $ayah) {
                $quizService->generateForAyah($ayah);
            }

            $questions = $surah->generatedQuestions()->inRandomOrder()->take(5)->get();
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
