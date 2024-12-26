<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ExpenseController;

Route::get('/', function () {
    return view('admin/login');
});

Route::get('/admin/login', [AdminController::class, 'index'])->name('admin.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    

    Route::get('/admin/register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('addnewuser');
    Route::delete('/admin/useraccounts/{user}', [RegisteredUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/expenses/form', [ExpenseController::class, 'store'])->name('expenseadd');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::patch('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::get('/expenses/form{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');

    Route::get('/expenses/form', [ExpenseController::class, 'expenseadd'])->name('expenseaddform');
    Route::get('/admin/table', [AdminController::class, 'table'])->name('admin.table');
    Route::get('/admin/useraccounts', [AdminController::class, 'useraccounts'])->name('admin.useraccounts');
    Route::get('/expenses', [ExpenseController::class, 'expenses'])->name('expenses');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
