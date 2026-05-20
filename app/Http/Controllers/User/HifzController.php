<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ayah;
use App\Models\RecitationAttempt;
use App\Models\UserMemorizationProgress;
use App\Services\SpacedRepetitionService;
use App\Services\SpeechToTextService;
use App\Services\TextMatchingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HifzController extends Controller
{
    public function __construct(
        private SpeechToTextService $speechService,
        private TextMatchingService $textMatching,
        private SpacedRepetitionService $srsService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $dueReviews = $user->memorizationProgress()
            ->with('ayah.surah')
            ->whereNotNull('next_review_date')
            ->whereDate('next_review_date', '<=', Carbon::today())
            ->orderBy('next_review_date', 'asc')
            ->get();

        $overdueCount = $user->memorizationProgress()
            ->whereNotNull('next_review_date')
            ->whereDate('next_review_date', '<', Carbon::today())
            ->count();

        $todayCount = $user->memorizationProgress()
            ->whereNotNull('next_review_date')
            ->whereDate('next_review_date', Carbon::today())
            ->count();

        $upcomingCount = $user->memorizationProgress()
            ->whereNotNull('next_review_date')
            ->whereDate('next_review_date', '>', Carbon::today())
            ->whereDate('next_review_date', '<=', Carbon::today()->addDays(7))
            ->count();

        $surahFilter = $request->get('surah');
        if ($surahFilter) {
            $dueReviews = $dueReviews->filter(function ($item) use ($surahFilter) {
                return $item->ayah->surah_id == $surahFilter;
            });
        }

        $surahs = $user->memorizationProgress()
            ->with('ayah.surah')
            ->get()
            ->pluck('ayah.surah')
            ->unique('id')
            ->sortBy('number')
            ->values();

        $firstDue = $dueReviews->first();
        $firstDueRoute = $firstDue ? route('hifz.recite', $firstDue->ayah) : null;

        $totalDue = $dueReviews->count();

        return view('user.hifz.index', compact(
            'dueReviews',
            'totalDue',
            'overdueCount',
            'todayCount',
            'upcomingCount',
            'surahs',
            'surahFilter',
            'firstDueRoute'
        ));
    }

    public function recite(Ayah $ayah)
    {
        $ayah->load('surah');

        return view('user.hifz.recite', compact('ayah'));
    }

    public function submit(Request $request, Ayah $ayah)
    {
        $request->validate([
            'audio' => 'required|file',
        ]);

        $user = $request->user();
        $path = $request->file('audio')->store('recitations', 'public');

        $attempt = RecitationAttempt::create([
            'user_id' => $user->id,
            'ayah_id' => $ayah->id,
            'audio_file_path' => $path,
        ]);

        try {
            $audioFullPath = storage_path('app/public/' . $path);
            $transcribedText = $this->speechService->transcribe($audioFullPath);

            if (!$transcribedText) {
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
                    ->where('ayah_id', $ayah->id)
                    ->first();

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
                    'reference_text' => $ayah->text_imlaei,
                    'reference_uthmani' => $ayah->text_uthmani,
                    'word_diff' => $matchResult['word_diff'],
                    'pass_threshold' => 90,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في معالجة الصوت: ' . $e->getMessage(),
            ], 500);
        }
    }
}
