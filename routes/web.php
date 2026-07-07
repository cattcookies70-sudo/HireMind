<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StagiaireController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('stagiaires.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('stagiaires.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route d'extraction (AJAX) - placée AVANT resource
    Route::post('/stagiaires/parse-cv', [StagiaireController::class, 'parseCv'])
        ->name('stagiaires.parse-cv');

    // Ressource stagiaires (CRUD)
    Route::resource('stagiaires', StagiaireController::class);
});

require __DIR__.'/auth.php';