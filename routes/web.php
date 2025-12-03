<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;


// Redirect / to login if not authenticated
Route::get('/', function () {
    return redirect('/login');
});


/*
|--------------------------------------------------------------------------
| Guest Routes (Not Logged In)
|--------------------------------------------------------------------------
*/

// Login Page
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Logged In Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    //Products
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/my-products', [ProductController::class, 'myProducts'])->name('product.myproducts');

    //Product Category
    Route::get('category', [ProductCategoryController::class, 'index'])->name('product.category');

    //Settings
    Route::prefix('/settings')->name('settings.')->group(function () {

        Route::get('/', [SettingController::class, 'index'])->name('general');

        Route::get('institutions', [InstitutionsController::class, 'index'])->name('institutions');

    });

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::put('users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.destroy');



    //Designations
    Route::get('designations', [DesignationController::class, 'index'])->name('designations.index');

    // Departments
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');

    //Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');

    //Audit Log
    Route::get('auditlog', [AuditLogController::class, 'index'])->name('auditlog.index');

});
