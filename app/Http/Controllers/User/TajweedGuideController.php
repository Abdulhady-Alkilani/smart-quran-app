<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\TajweedService;

class TajweedGuideController extends Controller
{
    public function index()
    {
        $tajweedService = app(TajweedService::class);
        $rulesByCategory = $tajweedService->getRulesByCategory();
        $allRules = $tajweedService->getRules();

        return view('user.quran.tajweed-guide', compact('rulesByCategory', 'allRules'));
    }
}
