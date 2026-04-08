<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewController extends Controller
{
    // عرض قائمة الآيات التي حان موعد مراجعتها اليوم
    public function index(Request $request)
    {
        $user = $request->user();

        // استخراج الآيات التي تاريخ مراجعتها القادم يطابق اليوم أو قبل اليوم (تأخر في المراجعة)
        $dueReviews = $user->memorizationProgress()
            ->with('ayah.surah') // جلب بيانات الآية والسورة للعرض
            ->whereNotNull('next_review_date')
            ->whereDate('next_review_date', '<=', Carbon::today())
            ->orderBy('next_review_date', 'asc') // إعطاء الأولوية للآيات المتأخرة
            ->get();

        $totalDue = $dueReviews->count();

        return view('user.reviews.index', compact('dueReviews', 'totalDue'));
    }

    /*
     * ملاحظة: عندما يختار الطالب آية لمراجعتها، سيتم توجيهه إلى صفحة
     * RecitationController@create لتسجيل صوته.
     * وحالما ينجح في التسميع، سيقوم RecitationController بتحديث
     * تاريخ next_review_date تلقائياً بفضل الكود الذي كتبناه سابقاً.
     */
}
