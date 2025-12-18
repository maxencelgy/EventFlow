<?php

use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PublicRegistrationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Public registration (magic link - no auth required)
Route::prefix('inscription/{token}')->name('public.')->group(function () {
    Route::get('/', [PublicRegistrationController::class, 'show'])->name('register');
    Route::post('/', [PublicRegistrationController::class, 'store'])->name('register.store');
    Route::get('/confirmation', [PublicRegistrationController::class, 'confirmation'])->name('confirmation');
});

// Auth routes (login, register, etc.)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials, request()->boolean('remember'))) {
            request()->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ])->onlyInput('email');
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function () {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'accueil',
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    });
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events - admin and chef_projet only
    Route::middleware('role:admin,chef_projet')->group(function () {
        Route::resource('events', EventController::class);

        // Registrations (nested under events)
        Route::prefix('events/{event}')->name('events.')->group(function () {
            Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
            Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
            Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
            Route::post('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
            Route::get('/statistics', [RegistrationController::class, 'statistics'])->name('statistics');
        });

        // Participants - admin and chef_projet only
        Route::resource('participants', ParticipantController::class);
    });

    // Check-in - accessible to all roles (admin, chef_projet, accueil)
    Route::prefix('checkin/{event}')->name('checkin.')->group(function () {
        Route::get('/', [CheckInController::class, 'index'])->name('index');
        Route::get('/search', [CheckInController::class, 'search'])->name('search');
        Route::post('/{registration}/checkin', [CheckInController::class, 'checkIn'])->name('checkin');
        Route::post('/{registration}/absent', [CheckInController::class, 'markAbsent'])->name('absent');
    });
});
