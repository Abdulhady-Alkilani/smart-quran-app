<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RecitationAttempt;
use App\Models\UserMemorizationProgress;
use App\Models\GeneratedQuestion;
use App\Models\UserQuizAttempt;
use App\Models\Surah;
use App\Models\Ayah;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::whereHas('roles', function($q) {
            $q->where('name', 'student');
        })->get();

        if ($students->isEmpty()) {
            return;
        }

        // We assume Surah and Ayah have at least some data, or we fallback to 1 to avoid breaking
        $surahId = Surah::first()->id ?? 1;
        $ayahId = Ayah::first()->id ?? 1;

        // 1. Create a Generated Question
        $question = GeneratedQuestion::firstOrCreate(
            ['surah_id' => $surahId, 'ayah_id' => $ayahId],
            [
                'question_text' => 'أكمل الآية الكريمة التالية...',
                'options' => json_encode(['الخيار الأول', 'الخيار الثاني', 'الخيار الثالث', 'الخيار الرابع'], JSON_UNESCAPED_UNICODE),
                'correct_answer' => 'الخيار الأول',
            ]
        );

        foreach ($students as $student) {
            // 2. User Memorization Progress
            UserMemorizationProgress::create([
                'user_id' => $student->id,
                'ayah_id' => $ayahId,
                'status' => 'memorized',
                'repetition_count' => rand(1, 5),
                'easiness_factor' => rand(25, 30) / 10,
                'interval_days' => rand(1, 7),
                'last_review_date' => now()->subDays(rand(1, 5)),
                'next_review_date' => now()->addDays(rand(1, 5)),
            ]);

            UserMemorizationProgress::create([
                'user_id' => $student->id,
                'ayah_id' => $ayahId + 1, // just another ayah
                'status' => 'learning',
                'repetition_count' => rand(1, 2),
                'easiness_factor' => 2.5,
                'interval_days' => 1,
                'last_review_date' => now(),
                'next_review_date' => now()->addDays(1),
            ]);

            // 3. Recitation Attempts
            RecitationAttempt::create([
                'user_id' => $student->id,
                'ayah_id' => $ayahId,
                'audio_file_path' => 'audio/dummy_record_1.mp3',
                'transcribed_text' => 'نص التلاوة المتعرف عليه',
                'similarity_score' => rand(70, 99),
                'mistakes_count' => rand(0, 3),
                'is_passed' => true,
            ]);

            RecitationAttempt::create([
                'user_id' => $student->id,
                'ayah_id' => $ayahId + 1,
                'audio_file_path' => 'audio/dummy_record_2.mp3',
                'transcribed_text' => 'نص آخر به بعض الأخطاء',
                'similarity_score' => rand(40, 69),
                'mistakes_count' => rand(4, 10),
                'is_passed' => false,
            ]);

            // 4. Quiz Attempts
            UserQuizAttempt::create([
                'user_id' => $student->id,
                'question_id' => $question->id,
                'user_answer' => 'الخيار الأول',
                'is_correct' => true,
            ]);
            
            UserQuizAttempt::create([
                'user_id' => $student->id,
                'question_id' => $question->id,
                'user_answer' => 'الخيار الثاني',
                'is_correct' => false,
            ]);
        }
    }
}
