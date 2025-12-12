<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CompanyRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;

// Landing - show welcome page, or redirect to dashboard if authenticated
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

// Auth
Route::get('login',[LoginController::class,'showLoginForm'])->name('login');
Route::post('login',[LoginController::class,'login']);
Route::post('logout',[LoginController::class,'logout'])->name('logout');

// Company registration
Route::get('register/company',[CompanyRegisterController::class,'showCompanyForm'])->name('register.company');
Route::post('register/company',[CompanyRegisterController::class,'registerCompany']);

// Admin registration
Route::get('register/admin/{company}',[AdminRegisterController::class,'showAdminForm'])->name('register.admin');
Route::post('register/admin/{company}',[AdminRegisterController::class,'registerAdmin']);

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Dashboard routes based on role
    Route::get('dashboard', function () {
        $user = auth()->user();
        $role = $user->role ?? 'staff';
        
        if ($role === 'super-admin') {
            return redirect()->route('superadmin.dashboard');
        }
        if ($role === 'company-admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('staff.dashboard');
    })->name('dashboard');

    Route::get('superadmin/dashboard', function () {
        $companies = \App\Models\Company::all();
        $users = \App\Models\User::all();
        $totalUsers = $users->count();
        return view('dashboard.superadmin', compact('companies', 'users', 'totalUsers'));
    })->name('superadmin.dashboard')->middleware('role:super-admin');
    
    // Company management (super-admin only)
    Route::middleware('role:super-admin')->group(function () {
        Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    });
    Route::get('admin/dashboard', function () {
        $user = auth()->user();
        $company = $user->company;
        $products = \App\Models\Product::where('company_id', $company->id)->paginate(10);
        $staff = \App\Models\User::where('company_id', $company->id)->where('role', 'staff')->paginate(10);
        $admins = \App\Models\User::where('company_id', $company->id)->where('role', 'company-admin')->paginate(10);
        return view('dashboard.admin', compact('company', 'products', 'staff', 'admins'));
    })->name('admin.dashboard')->middleware('role:super-admin|company-admin');
    Route::get('staff/dashboard', function () {
        $user = auth()->user();
        $company = $user->company;
        $products = \App\Models\Product::where('company_id', $company->id)->with('inventory')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'image' => $product->image_path,
                'quantity' => $product->inventory?->quantity ?? 0,
                'price' => $product->inventory?->unit_price ?? 0
            ];
        });
        return view('dashboard.staff', compact('products'));
    })->name('staff.dashboard');

    // Products (company-admin and super-admin)
    Route::middleware(['role:super-admin|company-admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('inventory', [InventoryController::class,'index'])->name('inventory.index');
        Route::post('inventory/{inventory}/add', [InventoryController::class,'addStock'])->name('inventory.add');
        Route::post('inventory/{inventory}/reduce', [InventoryController::class,'reduceStock'])->name('inventory.reduce');
        Route::get('stock-movements', [StockMovementController::class,'index'])->name('stockmovements.index');
        
        // Staff management
        Route::resource('staff', StaffController::class);
        Route::post('staff/{staff}/toggle-status', [StaffController::class,'toggleStatus'])->name('staff.toggle-status');
        
        // Admin management (for top executives)
        Route::resource('admin', AdminController::class);
    });

});


