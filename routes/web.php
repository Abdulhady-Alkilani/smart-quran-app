<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\QuizController;
use App\Http\Controllers\User\QuranController;
use App\Http\Controllers\User\RecitationController;
use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('quran')->name('quran.')->group(function () {
        Route::get('/', [QuranController::class, 'index'])->name('index');
        Route::get('/{surah}', [QuranController::class, 'show'])->name('show');
        Route::post('/{ayah}/start-memorizing', [QuranController::class, 'startMemorizing'])->name('start');
    });

    Route::prefix('recitation')->name('recitation.')->group(function () {
        Route::get('/{ayah}', [RecitationController::class, 'create'])->name('create');
        Route::post('/{ayah}', [RecitationController::class, 'store'])->name('store');
    });

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/{surah}', [QuizController::class, 'show'])->name('show');
        Route::post('/{question}/submit', [QuizController::class, 'submit'])->name('submit');
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
