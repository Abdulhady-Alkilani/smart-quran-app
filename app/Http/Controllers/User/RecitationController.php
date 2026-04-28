<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ayah;
use App\Models\RecitationAttempt;
use App\Models\UserMemorizationProgress;
use App\Services\SpacedRepetitionService;
use App\Services\SpeechToTextService;
use App\Services\TextMatchingService;
use Illuminate\Http\Request;

class RecitationController extends Controller
{
    public function __construct(
        private SpeechToTextService $speechService,
        private TextMatchingService $textMatching,
        private SpacedRepetitionService $srsService,
    ) {}

    public function create(Ayah $ayah)
    {
        $ayah->load('surah');

        return view('user.recitation.create', compact('ayah'));
    }

    public function store(Request $request, Ayah $ayah)
    {
        $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,webm,ogg,m4a',
        ]);

        $user = $request->user();
        $path = $request->file('audio')->store('recitations', 'local');

        $attempt = RecitationAttempt::create([
            'user_id' => $user->id,
            'ayah_id' => $ayah->id,
            'audio_file_path' => $path,
        ]);

        try {
            $audioFullPath = storage_path('app/'.$path);
            $transcribedText = $this->speechService->transcribe($audioFullPath);

            if (! $transcribedText) {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في تحويل الصوت إلى نص',
                ], 500);
            }

            $matchResult = $this->textMatching->match($transcribedText, $ayah->text_imlaei);

            $attempt->update([
                'transcribed_text' => $transcribedText,
                'similarity_score' => $matchResult['similarity_score'],
                'mistakes_count' => $matchResult['mistakes_count'],
                'is_passed' => $matchResult['is_passed'],
            ]);

            if ($attempt->is_passed) {
                $progress = UserMemorizationProgress::where('user_id', $user->id)
                    ->where('ayah_id', $ayah->id)->first();

                if ($progress) {
                    $srsData = $this->srsService->calculateNextReview($progress, $matchResult['similarity_score']);
                    $progress->update($srsData);
                } else {
                    UserMemorizationProgress::create([
                        'user_id' => $user->id,
                        'ayah_id' => $ayah->id,
                        'status' => 'learning',
                        'repetition_count' => 1,
                        'easiness_factor' => 2.5,
                        'interval_days' => 1,
                        'last_review_date' => now(),
                        'next_review_date' => now()->addDay(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'result' => [
                    'similarity_score' => number_format($matchResult['similarity_score'], 1),
                    'mistakes_count' => $matchResult['mistakes_count'],
                    'is_passed' => $matchResult['is_passed'],
                    'transcribed_text' => $transcribedText,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في معالجة الصوت: '.$e->getMessage(),
            ], 500);
        }
    }
}
