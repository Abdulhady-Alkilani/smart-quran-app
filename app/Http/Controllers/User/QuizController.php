<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use App\Models\GeneratedQuestion;
use App\Models\UserQuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // عرض الأسئلة المتوفرة لسورة معينة
    public function show(Surah $surah)
    {
        $questions = $surah->generatedQuestions()->inRandomOrder()->take(5)->get();
        return view('user.quiz.show', compact('surah', 'questions'));
    }

    // استلام إجابة المستخدم وتقييمها
    public function submit(Request $request, GeneratedQuestion $question)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);

        $userAnswer = $request->answer;

        // التحقق من الإجابة (مقارنة نصية بسيطة، يمكن تطويرها لـ Levenshtein)
        $isCorrect = trim($userAnswer) === trim($question->correct_answer);

        UserQuizAttempt::create([
            'user_id' => $request->user()->id,
            'question_id' => $question->id,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect
        ]);

        return back()->with('result', [
            'isCorrect' => $isCorrect,
            'correctAnswer' => $question->correct_answer
        ]);
    }
}
