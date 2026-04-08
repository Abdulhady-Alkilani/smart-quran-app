<?php

use Illuminate\Support\Facades\Route;

// استدعاء المتحكمات الخاصة بالمستخدم
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\QuranController;
use App\Http\Controllers\User\RecitationController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\QuizController;
use App\Http\Controllers\User\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| مسارات واجهة الموقع العامة والمستخدمين (الطلاب)
*/

// الصفحة الرئيسية للموقع (للزوار - Visitor)
Route::get('/', function () {
    return view('welcome'); // صفحة هبوط تشرح ميزات المنصة
})->name('home');

// =========================================================
// مسارات المستخدم (الطالب) - محمية بتسجيل الدخول (auth)
// =========================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. لوحة التحكم الرئيسية للطالب
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. تصفح القرآن الكريم واختيار الورد
    Route::prefix('quran')->name('quran.')->group(function () {
        Route::get('/', [QuranController::class, 'index'])->name('index'); // عرض السور
        Route::get('/{surah}', [QuranController::class, 'show'])->name('show'); // عرض آيات السورة
        Route::post('/{ayah}/start-memorizing', [QuranController::class, 'startMemorizing'])->name('start'); // إضافة للورد
    });

    // 3. التسميع والمطابقة الصوتية (AI)
    Route::prefix('recitation')->name('recitation.')->group(function () {
        Route::get('/{ayah}', [RecitationController::class, 'create'])->name('create'); // صفحة الميكروفون
        Route::post('/{ayah}', [RecitationController::class, 'store'])->name('store'); // رفع الصوت ومعالجته
    });

    // 4. نظام المراجعة والتكرار المتباعد (SRS)
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index'); // الورد اليومي المستحق

    // 5. الاختبارات المولدة تلقائياً
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/{surah}', [QuizController::class, 'show'])->name('show'); // عرض الأسئلة
        Route::post('/{question}/submit', [QuizController::class, 'submit'])->name('submit'); // إرسال الإجابة
    });

    // 6. الملف الشخصي والإعدادات
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php'; // مسارات تسجيل الدخول والتسجيل (Laravel Breeze)
