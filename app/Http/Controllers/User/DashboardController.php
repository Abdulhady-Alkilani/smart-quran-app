<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

        // 2. جلب الآيات التي حان موعد مراجعتها اليوم (خوارزمية SRS)
        $dueReviews = $user->memorizationProgress()
            ->with('ayah.surah') // جلب بيانات الآية والسورة المرتبطة
            ->whereDate('next_review_date', '<=', Carbon::today())
            ->get();

        // 3. جلب آخر محاولات التسميع
        $recentAttempts = $user->recitationAttempts()
            ->with('ayah')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('memorizedCount', 'dueReviews', 'recentAttempts'));
    }
}
