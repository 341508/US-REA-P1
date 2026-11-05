<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\MagazijnController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Schermen voor ingelogde magazijnmedewerkers.
Route::middleware('auth')->group(function () {
    Route::get('/magazijn', [MagazijnController::class, 'index'])->name('magazijn.index');
    Route::get('/magazijn/{product}/leveringen', [MagazijnController::class, 'leveringen'])->name('magazijn.leveringen');
    Route::get('/magazijn/{product}/allergenen', [MagazijnController::class, 'allergenen'])->name('magazijn.allergenen');

    // Opdracht 2 - userstory 1: leveranciers en hun geleverde producten.
    Route::get('/leveranciers', [LeverancierController::class, 'index'])->name('leveranciers.index');
    Route::get('/leveranciers/{leverancier}/producten', [LeverancierController::class, 'producten'])->name('leveranciers.producten');
    Route::get('/leveranciers/{leverancier}/producten/{product}/levering', [LeverancierController::class, 'createLevering'])->name('leveranciers.leveringen.create');
    Route::post('/leveranciers/{leverancier}/producten/{product}/levering', [LeverancierController::class, 'storeLevering'])->name('leveranciers.leveringen.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Alleen administrators mogen gebruikersrollen bekijken en aanpassen.
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/gebruikers', [UserController::class, 'index'])->name('users.index');
    Route::patch('/gebruikers/{user}/rol', [UserController::class, 'updateRole'])->name('users.role');
});

require __DIR__.'/auth.php';
