<?php

use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\QuizController;
use App\Http\Controllers\User\QuranController;
use App\Http\Controllers\User\RecitationController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\HifzController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\TajweedGuideController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('quran')->name('quran.')->group(function () {
        Route::get('/', [QuranController::class, 'index'])->name('index');
        Route::get('/tajweed-guide', [TajweedGuideController::class, 'index'])->name('tajweed-guide');
        Route::get('/{surah}', [QuranController::class, 'show'])->name('show');
        Route::post('/{ayah}/start-memorizing', [QuranController::class, 'startMemorizing'])->name('start');
    });

    Route::prefix('recitation')->name('recitation.')->group(function () {
        Route::get('/surah/{surah}', [RecitationController::class, 'createSurah'])->name('surah');
        Route::post('/surah/{surah}', [RecitationController::class, 'storeSurah'])->name('surah.store');
        Route::get('/{ayah}', [RecitationController::class, 'create'])->name('create');
        Route::post('/{ayah}', [RecitationController::class, 'store'])->name('store');
    });

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/schedule', [ReviewController::class, 'schedule'])->name('reviews.schedule');

    Route::prefix('hifz')->name('hifz.')->group(function () {
        Route::get('/', [HifzController::class, 'index'])->name('index');
        
        Route::get('/surah/select', [HifzController::class, 'selectSurah'])->name('surah.select');
        Route::get('/surah/{surah}/recite', [HifzController::class, 'reciteSurah'])->name('surah.recite');
        Route::post('/surah/{surah}/submit', [HifzController::class, 'submitSurah'])->name('surah.submit');
        
        Route::get('/{ayah}', [HifzController::class, 'recite'])->name('recite');
        Route::post('/{ayah}', [HifzController::class, 'submit'])->name('submit');
    });

    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/{surah}', [QuizController::class, 'show'])->name('show');
        Route::get('/{surah}/complete', [QuizController::class, 'completeAyah'])->name('complete');
        Route::get('/{surah}/mcq', [QuizController::class, 'mcqQuiz'])->name('mcq');
        Route::post('/{question}/submit', [QuizController::class, 'submit'])->name('submit');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::get('/{id}/go', [NotificationController::class, 'readAndRedirect'])->name('go');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('user-profile')->name('user.profile.')->group(function () {
        Route::get('/', [UserProfileController::class, 'edit'])->name('edit');
        Route::put('/', [UserProfileController::class, 'update'])->name('update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
