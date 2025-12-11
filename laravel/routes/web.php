<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CompanyRegisterController;
use App\Http\Controllers\Auth\CompanyVerifyController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;

// Landing
Route::get('/', fn() => view('welcome'));

// Auth
Route::get('login',[LoginController::class,'showLoginForm'])->name('login');
Route::post('login',[LoginController::class,'login']);
Route::post('logout',[LoginController::class,'logout'])->name('logout');

// Company registration & verification
Route::get('register/company',[CompanyRegisterController::class,'showCompanyForm'])->name('register.company');
Route::post('register/company',[CompanyRegisterController::class,'registerCompany']);
Route::get('company/verify/{company}',[CompanyVerifyController::class,'verify'])->name('company.verify');

// Admin registration
Route::get('register/admin/{company}',[AdminRegisterController::class,'showAdminForm'])->name('register.admin');
Route::post('register/admin/{company}',[AdminRegisterController::class,'registerAdmin']);

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Products (company-admin and super-admin)
    Route::middleware(['role:super-admin|company-admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('inventory', [InventoryController::class,'index'])->name('inventory.index');
        Route::post('inventory/{inventory}/add', [InventoryController::class,'addStock'])->name('inventory.add');
        Route::post('inventory/{inventory}/reduce', [InventoryController::class,'reduceStock'])->name('inventory.reduce');
        Route::get('stock-movements', [StockMovementController::class,'index'])->name('stockmovements.index');
    });

});
