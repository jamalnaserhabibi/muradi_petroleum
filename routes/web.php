<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
Route::get('/', function () {
    return view('admin/login');
});

Route::get('/admin/login', [AdminController::class, 'index'])->name('admin.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('add', [RegisteredUserController::class, 'store']);

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/form', [AdminController::class, 'form'])->name('admin.form');
    Route::get('/admin/table', [AdminController::class, 'table'])->name('admin.table');
    Route::get('/admin/useraccounts', [AdminController::class, 'useraccounts'])->name('admin.useraccounts');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
