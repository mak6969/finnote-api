<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        $credentials = request()->only('email', 'password');

        if (Auth::attempt($credentials, true)) {
            request()->session()->regenerate(true);
            return redirect('/dashboard');
        }

        return back()->with('error', 'Email atau password salah!')->withInput(request()->only('email'));
    });

    Route::get('/register', [WebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [WebController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
    Route::get('/transactions', [WebController::class, 'transactions'])->name('transactions');
    Route::post('/transactions', [WebController::class, 'storeTransaction'])->name('transactions.store');
    Route::put('/transactions/{transaction}', [WebController::class, 'updateTransaction'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [WebController::class, 'destroyTransaction'])->name('transactions.destroy');

    Route::get('/categories', [WebController::class, 'categories'])->name('categories');
    Route::post('/categories', [WebController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{category}', [WebController::class, 'destroyCategory'])->name('categories.destroy');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');