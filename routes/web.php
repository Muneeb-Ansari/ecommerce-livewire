<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserCreate;
// use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/dashboard', 'dashboard')->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/posts', 'posts')->middleware(['auth', 'verified'])
    ->name('posts');

// Auth::routes(['verify' => true]);
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/users/create', UserCreate::class)->name('admin.users.create');
});

require __DIR__ . '/auth.php';
