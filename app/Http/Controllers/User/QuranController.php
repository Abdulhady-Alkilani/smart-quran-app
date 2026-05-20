<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use App\Models\Ayah;
use App\Services\TajweedService;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function __construct(
        private TajweedService $tajweedService,
    ) {}

    public function index()
    {
        $surahs = Surah::all();
        return view('user.quran.index', compact('surahs'));
    }

    public function show($surah, Request $request)
    {
        if (!$surah instanceof Surah) {
            $surah = Surah::where('number', $surah)->orWhere('id', $surah)->firstOrFail();
        }

        $user = $request->user();

        $ayahs = $surah->ayahs()->with(['memorizationProgress' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $tajweedRules = $this->tajweedService->getRules();

        return view('user.quran.show', compact('surah', 'ayahs', 'tajweedRules'));
    }

    public function startMemorizing(Request $request, Ayah $ayah)
    {
        $user = $request->user();

        $user->memorizationProgress()->firstOrCreate(
            ['ayah_id' => $ayah->id],
            [
                'status' => 'learning',
                'next_review_date' => now(),
            ]
        );

        return back()->with('success', 'تمت إضافة الآية لوردك اليومي بنجاح!');
    }
}
