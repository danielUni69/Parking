<?php
use App\Livewire\Dashboard;
use App\Livewire\EspaciosList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get("/", function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get("/login", [LoginController::class, "showLogin"])->name("login");
Route::post("/login", [LoginController::class, "login"])->name("login.post");

Route::get("/register", [LoginController::class, "showRegister"])->name("register");
Route::post("/register", [LoginController::class, "register"])->name("register.post");

Route::post("/logout", [LoginController::class, "logout"])->name("logout");
Route::get("/dashboard", Dashboard::class)->name("dashboard");

Route::middleware(["auth"])->group(function () {
    Route::get("/parking", EspaciosList::class)->name("parking");
    Route::get('/users', \App\Livewire\Users::class)->name('users');
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');
});
