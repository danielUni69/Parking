<?php

use App\Livewire\Dashboard;
use App\Livewire\EspaciosList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get("/", function () {
    return view("welcome");
});

// Login
Route::get("/login", [LoginController::class, "showLogin"])->name("login");
Route::post("/login", [LoginController::class, "login"])->name("login.post");

// Registro
Route::get("/register", [LoginController::class, "showRegister"])->name(
    "register",
);
Route::post("/register", [LoginController::class, "register"])->name(
    "register.post",
);

// Logout
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

// Protegidas
Route::middleware(["auth"])->group(function () {
    Route::get("/dashboard", Dashboard::class)->name("dashboard");
    Route::get("/parking", EspaciosList::class)->name("parking");
});
