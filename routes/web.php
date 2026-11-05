<?php

use App\Http\Controllers\Admin\UserController;
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
