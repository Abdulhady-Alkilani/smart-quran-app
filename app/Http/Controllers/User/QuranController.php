<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use App\Models\Ayah;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    // عرض قائمة السور
    public function index()
    {
        $surahs = Surah::all();
        return view('user.quran.index', compact('surahs'));
    }

    // عرض آيات سورة معينة مع حالة حفظ المستخدم لها
    public function show($surah, Request $request)
    {
        // دعم التنقل بالرقم أو بالـ Model
        if (!$surah instanceof Surah) {
            $surah = Surah::where('number', $surah)->orWhere('id', $surah)->firstOrFail();
        }

        $user = $request->user();

        // جلب الآيات مع حالة الحفظ الخاصة بهذا المستخدم تحديداً
        $ayahs = $surah->ayahs()->with(['memorizationProgress' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('user.quran.show', compact('surah', 'ayahs'));
    }

    // إضافة آية إلى قائمة الحفظ (بدء التعلم)
    public function startMemorizing(Request $request, Ayah $ayah)
    {
        $user = $request->user();

        $user->memorizationProgress()->firstOrCreate(
            ['ayah_id' => $ayah->id],
            [
                'status' => 'learning',
                'next_review_date' => now(), // تبدأ المراجعة فوراً
            ]
        );

        return back()->with('success', 'تمت إضافة الآية لوردك اليومي بنجاح!');
    }
}
