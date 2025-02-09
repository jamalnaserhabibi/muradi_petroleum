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
use App\Http\Controllers\paymentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\salesController;
use App\Http\Controllers\towerController;
use App\Http\Controllers\serial_numbersController;
use App\Models\Serial_Numbers;

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
    Route::get('/customers/customers-nonactive',[CustomerController::class, 'customer0'])->name('customers0');
    Route::get('/customers/customers_type/',[CustomerController::class, 'typefilter'])->name('typefilter');
    Route::get('/customer/customerinfo/{id}', [salesController::class, 'singlecustomerinfo'])->name('singlecustomerinfo');

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
    Route::post('/updatecontractstatus', [ContractController::class, 'updateStatus']);
    //towers
    Route::get('/towers/addtower', [towerController::class, 'towerform'])->name('addtowerform');
    Route::post('/towers/store', [towerController::class, 'store'])->name('addtower');
    Route::get('/towers', [towerController::class, 'towers'])->name('towers');
    Route::delete('/towers/{id}/delete', [towerController::class, 'destroy'])->name('tower.destroy');
    Route::get('/towers/{id}/edit', [towerController::class, 'edit'])->name('tower.edit');
    Route::patch('/towers/{id}/update', [towerController::class, 'update'])->name('towerupdate');
    
    Route::get('/towers/{id}/seek', [towerController::class, 'seeksale'])->name('tower.seeksale');
    
    
    
    //serial_numbers
    Route::post('/meter_reading/addsaleinfoform/store_serial', [serial_numbersController::class, 'store'])->name('serial_numbers_store');
    Route::get('/meter_reading', [serial_numbersController::class, 'meter_reading'])->name('meter_reading');
    Route::post('/meter_reading/addsaleinfoform/{id}/edit', [serial_numbersController::class, 'edit'])->name('editserialnumber');
    Route::patch('/meter_reading/addsaleinfoform/{id}/update', [serial_numbersController::class, 'update'])->name('serial_numbers_update');
    Route::get('/meter_reading/addsaleinfoform/serials', [serial_numbersController::class, 'sales'])->name('towers_info');
    Route::delete('/serialnumberdelete/{id}', [serial_numbersController::class, 'destroy'])->name('deleteserialnumber');


    //sales
    Route::get('/sales/form', [salesController::class, 'salesform'])->name('addnewsaleform');
    Route::get('/sales/sales', [salesController::class, 'sales'])->name('sales');
    Route::get('/sales/sales/singlecustomer/{id}', [salesController::class, 'singlecustomersalescustomer'])->name('singlecustomersalescustomer');
    Route::post('/sales/add', [salesController::class, 'store'])->name('addsale');
    Route::get('/sales/add', [salesController::class, 'edit'])->name('editsale');
    Route::patch('/sales/add', [salesController::class, 'update'])->name('updatesale');
    Route::delete('/sales/{id}', [salesController::class, 'delete'])->name('saledelete');
    


    //payment
    Route::get('/payment/payments', [paymentController::class, 'payment'])->name('payment');
    Route::get('/payment/addpaymentform', [paymentController::class, 'addpaymentform'])->name('addpaymentform');
    Route::patch('/payment/updatepayment', [paymentController::class, 'updatepayment'])->name('updatepayment');
    Route::post('/payment/addpayment', [paymentController::class, 'store'])->name('addpayment');
    Route::get('/payment/editpayment', [paymentController::class, 'editpayment'])->name('editpayment');
    Route::delete('/payment/delete', [paymentController::class, 'delete'])->name('deletepayment');
    Route::get('/payment/filter/', [paymentController::class, 'filtercustomer'])->name('filtercustomer');

    Route::get('/payment/payment/{id}', [paymentController::class, 'singlecustomerpayments'])->name('singlecustomerpayments');
    Route::get('/payment/paymentdatefilter/', [paymentController::class, 'filterpaymentdate'])->name('filterpaymentdate');


    //customer info

    
});

require __DIR__.'/auth.php';
