<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;

Route::get('/', [SurveyController::class, 'home'])->name('home');
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth.survey');
Route::get('/surveys', [SurveyController::class, 'browse'])->name('surveys.browse');
Route::post('/surveys/search', [SurveyController::class, 'searchByCode'])->name('surveys.searchByCode');
Route::get('/survey/{code}', [SurveyController::class, 'show'])->name('surveys.show');
Route::post('/survey/{code}/submit', [SurveyController::class, 'submit'])->name('surveys.submit');

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register']);
});
Route::middleware('auth.survey')->group(function () {
    Route::get('/my-surveys', [SurveyController::class, 'mySurveys'])->name('surveys.my');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/survey/{code}/results', [SurveyController::class, 'results'])->name('surveys.results');
    Route::patch('/surveys/{survey}/toggle', [SurveyController::class, 'toggleActive'])->name('surveys.toggleActive');
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
});

