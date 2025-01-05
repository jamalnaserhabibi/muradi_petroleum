<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContractController;

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
    Route::get('/expenses/filter', [ExpenseController::class, 'filterdate'])->name('expensefilterdate');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::patch('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::get('/expenses/form/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::get('/expenses/form', [ExpenseController::class, 'expenseadd'])->name('expenseaddform');
    Route::get('/admin/table', [AdminController::class, 'table'])->name('admin.table');
    Route::get('/admin/useraccounts', [AdminController::class, 'useraccounts'])->name('admin.useraccounts');
    Route::get('/expenses', [ExpenseController::class, 'expenses'])->name('expenses');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //employee
    Route::get('/employees/employees',[EmployeeController::class, 'employees'])->name('employees');
    //load form
    Route::get('/employees/employees/addemployee',[EmployeeController::class, 'addemployee'])->name('addemployee');
    //load for update
    Route::get('/employees/employees/{employee}',[EmployeeController::class, 'edit'])->name('editemployee');
    //add to db
    Route::post('/employees/employees',[EmployeeController::class, 'store'])->name('employeeadd');

    Route::delete('/employees/employees/{employee}',[EmployeeController::class, 'destroy'])->name('employee.delete');

    Route::patch('/employees/form/{employee}', [EmployeeController::class, 'update'])->name('employee.update');


    //customer
    //customerTableView
    Route::get('/customers/customers',[CustomerController::class, 'customer'])->name('customers');
    //customerAddForm
    Route::get('/customers/form',[CustomerController::class, 'customeraddform'])->name('customeradd');
    //customerStore
    Route::post('/customers/form', [CustomerController::class, 'store'])->name('customerstore');
    // load to update form
    Route::get('/customers/form/{customer}/edit',[CustomerController::class, 'edit'])->name('customer.edit');
    
    Route::delete('/customers/customers/{customer}',[CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->name('customer.update');


    //purchase
    Route::get('/purchase/purchase', [PurchaseController::class, 'purchase'])->name('purchase');
    Route::get('/purchase/form', [PurchaseController::class, 'purchaseform'])->name('addpurchaseform');
    Route::post('/purchase/form/adds', [PurchaseController::class, 'purchaseadd'])->name('purchaseadd');
    Route::delete('/purchase/{id}/delete', [PurchaseController::class, 'purchasedelete'])->name('purchasedelete');
    
    Route::patch('/purchase/form/{id}/update', [PurchaseController::class, 'purchaseupdate'])->name('purchaseupdate');
    Route::get('/purchase/form/{id}/edit', [PurchaseController::class, 'purchaseedit'])->name('purchaseedit');

    //product
    Route::get('/filter-purchases', [PurchaseController::class, 'filter'])->name('purchasefilter');


    //contractForm
    Route::get('/customers/CustomerContractForm', [ContractController::class, 'findCustomer'])->name('CustomerContract');
    Route::get('/customers/CustomerContractForm/{id}', [ContractController::class, 'contractedit'])->name('contractedit');
    Route::patch('/customers/CustomerContractForm/{id}/update', [ContractController::class, 'update'])->name('contractUpdate');
    Route::post('/customers/CustomerContractForm/store', [ContractController::class, 'store'])->name('contractstore');



});

require __DIR__.'/auth.php';
