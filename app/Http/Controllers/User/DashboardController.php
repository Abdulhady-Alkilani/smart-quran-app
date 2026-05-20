<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RecitationAttempt;
use App\Models\UserMemorizationProgress;
use App\Models\UserQuizAttempt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Prevent admin from accessing student dashboard
        if ($user->roles()->where('name', 'admin')->exists()) {
            return redirect('/admin');
        }

        // 1. حساب عدد الآيات المحفوظة
        $memorizedCount = $user->memorizationProgress()
            ->where('status', 'memorized')
            ->count();

        // 2. عدد الآيات قيد التعلم
        $learningCount = $user->memorizationProgress()
            ->where('status', 'learning')
            ->count();

        // 3. جلب الآيات التي حان موعد مراجعتها اليوم (خوارزمية SRS)
        $dueReviews = $user->memorizationProgress()
            ->with('ayah.surah')
            ->whereDate('next_review_date', '<=', Carbon::today())
            ->get();

        // 4. جلب آخر محاولات التسميع
        $recentAttempts = $user->recitationAttempts()
            ->with('ayah.surah')
            ->latest()
            ->take(10)
            ->get();

        // 5. إحصائيات الرسوم البيانية

        // --- Line Chart: نشاط الحفظ اليومي لآخر 30 يوماً ---
        $dailyActivity = [];
        $dailyLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyLabels[] = $date->format('m/d');
            $dailyActivity[] = $user->recitationAttempts()
                ->whereDate('created_at', $date)
                ->count();
        }

        // --- Doughnut Chart: توزيع حالات الحفظ ---
        $totalAyahs = 6236;
        $statusDistribution = [
            'memorized' => $memorizedCount,
            'learning' => $learningCount,
            'new' => $totalAyahs - $memorizedCount - $learningCount,
        ];

        // --- Bar Chart: درجات آخر 10 محاولات تسميع ---
        $scoreLabels = [];
        $scoreData = [];
        $scoreColors = [];
        foreach ($recentAttempts->reverse() as $attempt) {
            $scoreLabels[] = $attempt->ayah->surah->name_ar . ' ' . $attempt->ayah->number_in_surah;
            $scoreData[] = round($attempt->similarity_score ?? 0, 1);
            $scoreColors[] = ($attempt->is_passed ?? false) ? 'rgba(27, 94, 32, 0.8)' : 'rgba(239, 68, 68, 0.6)';
        }

        // --- إحصائيات إضافية ---
        $totalAttempts = $user->recitationAttempts()->count();
        $passedAttempts = $user->recitationAttempts()->where('is_passed', true)->count();
        $successRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100) : 0;

        $quizTotal = $user->quizAttempts()->count();
        $quizCorrect = $user->quizAttempts()->where('is_correct', true)->count();
        $quizRate = $quizTotal > 0 ? round(($quizCorrect / $quizTotal) * 100) : 0;

        // عدد السور التي بدأ حفظها
        $startedSurahs = $user->memorizationProgress()
            ->with('ayah')
            ->get()
            ->pluck('ayah.surah_id')
            ->unique()
            ->count();

        $chartData = [
            'dailyLabels' => $dailyLabels,
            'dailyActivity' => $dailyActivity,
            'statusDistribution' => $statusDistribution,
            'scoreLabels' => $scoreLabels,
            'scoreData' => $scoreData,
            'scoreColors' => $scoreColors,
        ];

        return view('user.dashboard', compact(
            'memorizedCount', 'learningCount', 'dueReviews', 'recentAttempts',
            'chartData', 'successRate', 'quizRate', 'startedSurahs', 'totalAttempts'
        ));
    }
}
