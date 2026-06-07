<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\FavouriteController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\TimetableController as UserTimetableController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TimetableController as AdminTimetableController;
use App\Http\Controllers\Admin\ScraperController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes (rate limited to prevent brute force attacks)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1');

// User routes (protected)
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Events (map route must come BEFORE resource to avoid route conflicts)
    Route::get('/events/map', [App\Http\Controllers\User\EventMapController::class, 'index'])->name('events.map');
    Route::resource('events', UserEventController::class);
    Route::post('/events/{event}/join', [UserEventController::class, 'join'])->name('events.join');
    Route::post('/events/{event}/join-external', [UserEventController::class, 'joinExternal'])->name('events.joinExternal');
    Route::delete('/events/{event}/leave', [UserEventController::class, 'leave'])->name('events.leave');

    // Favourites
    Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/favourites/{event}/toggle', [FavouriteController::class, 'toggle'])->name('favourites.toggle');

    // Reviews
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Timetable
    Route::get('/timetable', [UserTimetableController::class, 'index'])->name('timetable.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes (protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Events
    Route::resource('events', AdminEventController::class);

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Timetable
    Route::get('/timetable', [AdminTimetableController::class, 'index'])->name('timetable.index');

    // Web Scraper (rate limited to prevent abuse)
    Route::get('/scraper', [ScraperController::class, 'index'])->name('scraper.index');
    Route::post('/scraper/scrape', [ScraperController::class, 'scrape'])
        ->middleware('throttle:5,1')
        ->name('scraper.scrape');

    // Admin Profile
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
});