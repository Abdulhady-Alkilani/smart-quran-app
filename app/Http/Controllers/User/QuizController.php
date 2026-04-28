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
}
