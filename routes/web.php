<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/sections');

// Language Switcher
Route::get('/language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('doLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/sections', [AuthController::class, 'sections'])->name('sections');
});