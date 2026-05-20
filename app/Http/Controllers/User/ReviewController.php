<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewController extends Controller
{
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
        $firstDueRoute = $firstDue ? route('recitation.create', $firstDue->ayah) : null;

        $totalDue = $dueReviews->count();

        return view('user.reviews.index', compact(
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

    public function schedule(Request $request)
    {
        $user = $request->user();

        $allProgress = $user->memorizationProgress()
            ->with('ayah.surah')
            ->whereNotNull('next_review_date')
            ->orderBy('next_review_date', 'asc')
            ->get();

        $memorizedCount = $allProgress->where('status', 'memorized')->count();
        $learningCount = $allProgress->where('status', 'learning')->count();
        $totalCount = $allProgress->count();

        $avgEasiness = $allProgress->avg('easiness_factor');
        $avgInterval = $allProgress->avg('interval_days');

        $overdue = $allProgress->filter(fn($p) => $p->next_review_date->lt(Carbon::today()));
        $today = $allProgress->filter(fn($p) => $p->next_review_date->isToday());
        $thisWeek = $allProgress->filter(fn($p) =>
            $p->next_review_date->gt(Carbon::today()) && $p->next_review_date->lte(Carbon::today()->addDays(7))
        );
        $thisMonth = $allProgress->filter(fn($p) =>
            $p->next_review_date->gt(Carbon::today()->addDays(7)) && $p->next_review_date->lte(Carbon::today()->addDays(30))
        );
        $later = $allProgress->filter(fn($p) => $p->next_review_date->gt(Carbon::today()->addDays(30)));

        $surahFilter = $request->get('surah');
        if ($surahFilter) {
            $filterFn = fn($col) => $col->filter(fn($item) => $item->ayah->surah_id == $surahFilter);
            $overdue = $filterFn($overdue);
            $today = $filterFn($today);
            $thisWeek = $filterFn($thisWeek);
            $thisMonth = $filterFn($thisMonth);
            $later = $filterFn($later);
        }

        $surahs = $allProgress->pluck('ayah.surah')->unique('id')->sortBy('number')->values();

        $nextReview = $allProgress->first();
        $nextReviewIn = $nextReview ? $nextReview->next_review_date->diffForHumans() : null;

        $dailyLoad = [];
        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::today()->addDays($i);
            $count = $allProgress->filter(fn($p) => $p->next_review_date->isSameDay($date))->count();
            $dailyLoad[] = [
                'date' => $date,
                'label' => $i === 0 ? __('messages.srs.today') : ($i === 1 ? __('messages.srs.tomorrow') : $date->translatedFormat('l')),
                'short' => $date->translatedFormat('d M'),
                'count' => $count,
                'is_today' => $i === 0,
            ];
        }

        return view('user.reviews.schedule', compact(
            'overdue', 'today', 'thisWeek', 'thisMonth', 'later',
            'memorizedCount', 'learningCount', 'totalCount',
            'avgEasiness', 'avgInterval', 'dailyLoad',
            'surahs', 'surahFilter', 'nextReviewIn'
        ));
    }
}
