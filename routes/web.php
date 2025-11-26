<?php
use App\Livewire\Dashboard;
use App\Livewire\EspaciosList;
use App\Livewire\ReportesPagos;
use App\Livewire\ReportesPisos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get("/", function () {
    return redirect()->route("dashboard");
});

Route::get("/login", [LoginController::class, "showLogin"])->name("login");
Route::post("/login", [LoginController::class, "login"])->name("login.post");

Route::get("/register", [LoginController::class, "showRegister"])->name(
    "register",
);
Route::post("/register", [LoginController::class, "register"])->name(
    "register.post",
);

Route::post("/logout", [LoginController::class, "logout"])->name("logout");
Route::get("/dashboard", Dashboard::class)->name("dashboard");

Route::middleware(["auth"])->group(function () {
    Route::get("/parking", EspaciosList::class)->name("parking");
    Route::get("/users", \App\Livewire\Users::class)->name("users");
    Route::get("/profile", \App\Livewire\Profile::class)->name("profile");
    Route::get("/reportes/pagos", ReportesPagos::class)->name("reportes.pagos");
    Route::get("/reportes/pisos", ReportesPisos::class)->name("reportes.pisos");
});
