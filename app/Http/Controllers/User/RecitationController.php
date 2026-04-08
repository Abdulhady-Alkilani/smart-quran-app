<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ayah;
use App\Models\RecitationAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // للتواصل مع خادم الـ Python

class RecitationController extends Controller
{
    // عرض واجهة تسجيل الصوت للآية
    public function create(Ayah $ayah)
    {
        return view('user.recitation.create', compact('ayah'));
    }

    // استلام الملف الصوتي من المتصفح ومعالجته
    public function store(Request $request, Ayah $ayah)
    {
        $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,webm',
        ]);

        $user = $request->user();

        // 1. رفع الملف إلى S3 (أو محلياً مؤقتاً)
        $path = $request->file('audio')->store('recitations', 's3');

        // 2. إنشاء سجل المحاولة في قاعدة البيانات
        $attempt = RecitationAttempt::create([
            'user_id' => $user->id,
            'ayah_id' => $ayah->id,
            'audio_file_path' => $path,
        ]);

        // 3. إرسال الطلب إلى خادم الذكاء الاصطناعي (Python/FastAPI)
        // ملاحظة: يُفضل وضع هذا الكود في Job (Queue) لكي لا تنتظر الصفحة طويلاً
        try {
            $response = Http::timeout(60)->post(env('PYTHON_AI_SERVER_URL') . '/analyze-audio', [
                'audio_url' => Storage::disk('s3')->url($path),
                'correct_text' => $ayah->text_imlaei
            ]);

            if ($response->successful()) {
                $result = $response->json();

                // تحديث المحاولة بنتيجة الذكاء الاصطناعي
                $attempt->update([
                    'transcribed_text' => $result['transcribed_text'],
                    'similarity_score' => $result['similarity_score'],
                    'mistakes_count' => $result['mistakes_count'],
                    'is_passed' => $result['similarity_score'] >= 90, // نجاح إذا التطابق 90% أو أكثر
                ]);

                // إذا نجح، نقوم بتحديث خوارزمية التكرار المتباعد (SRS)
                if ($attempt->is_passed) {
                    $this->updateSpacedRepetition($user->id, $ayah->id, $result['similarity_score']);
                }

                return response()->json(['success' => true, 'result' => $attempt]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ في خادم الذكاء الاصطناعي'], 500);
        }
    }

    // دالة مساعدة لتحديث التكرار المتباعد (SuperMemo-2 المبسطة)
    private function updateSpacedRepetition($userId, $ayahId, $score)
    {
        $progress = \App\Models\UserMemorizationProgress::where('user_id', $userId)
                        ->where('ayah_id', $ayahId)->first();

        if ($progress) {
            // منطق SuperMemo مبسط:
            // إذا التطابق ممتاز نزيد الأيام، إذا ضعيف نعيد الأيام للصفر
            $quality = $score >= 95 ? 5 : ($score >= 90 ? 4 : 3);

            $newFactor = max(1.3, $progress->easiness_factor + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02)));

            if ($progress->repetition_count == 0) {
                $interval = 1;
            } elseif ($progress->repetition_count == 1) {
                $interval = 6;
            } else {
                $interval = round($progress->interval_days * $newFactor);
            }

            $progress->update([
                'repetition_count' => $progress->repetition_count + 1,
                'easiness_factor' => $newFactor,
                'interval_days' => $interval,
                'last_review_date' => now(),
                'next_review_date' => now()->addDays($interval),
                'status' => 'memorized'
            ]);
        }
    }
}
