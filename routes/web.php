<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\FoodOrderController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\MedicineOrderController;
use App\Http\Controllers\MedicineCategoryController;


// ---------------------------------
// Clear Cache (LOCAL ONLY)
// ---------------------------------
Route::get('/clear-all', function () {
    if (app()->environment('local')) {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return 'Cleared in local environment!';
    }
    abort(403);
});

Route::get('/', [HomeController::class, 'index'])->name('home');
// Using Route::view() keeps code simple and fast.
Route::view('/about', 'frontend.about')->name('about');
Route::view('/faq', 'frontend.faq')->name('faq');
Route::view('/support', 'frontend.support')->name('support');
Route::view('/contact', 'frontend.contact')->name('contact');
Route::view('/privacy-policy', 'frontend.privacy')->name('privacy');


// Redirect / to login if not authenticated
Route::get('/login', function () {
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

    //-----------Products-Food
    Route::get('/products-food', [FoodController::class, 'index'])->name('product.food.index');
    Route::get('/food-category', [FoodCategoryController::class, 'index'])->name('product.food.category');
    

    
    //--------------Products-Medicine
    Route::get('/products-medicine', [MedicineController::class, 'index'])->name('product.medicine.index');
    Route::get('/medicine-category', [MedicineCategoryController::class, 'index'])->name('product.medicine.category');

    //-------------Product-Orders
    //-------------Food Orders
    Route::get('/food-order-list', [FoodOrderController::class, 'index'])->name('orders.food.index');

    //-------------Medicine Orders
    Route::get('/medicine-order-list', [MedicineOrderController::class, 'index'])->name('orders.medicine.index');
 


    //--------------Settings
    Route::prefix('/settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('general');
        Route::post('/', [SettingController::class, 'store'])->name('store');
        Route::put('/{setting}', [SettingController::class, 'update'])->name('update');
        // Institutions Setup
        // Institutions routes
        Route::get('institutions', [InstitutionsController::class, 'index'])->name('institutions');
        Route::get('institutions/create', [InstitutionsController::class, 'create'])->name('institutions.create');
        Route::post('institutions', [InstitutionsController::class, 'store'])->name('institutions.store');
        Route::get('institutions/{institution}', [InstitutionsController::class, 'show'])->name('institutions.show');
        Route::get('institutions/{institution}/edit', [InstitutionsController::class, 'edit'])->name('institutions.edit');
        Route::put('institutions/{institution}', [InstitutionsController::class, 'update'])->name('institutions.update');
        Route::delete('institutions/{institution}', [InstitutionsController::class, 'destroy'])->name('institutions.destroy');
    });



//Designations
    Route::get('designations', [DesignationController::class, 'index'])->name('designations.index');
    Route::post('designations', [DesignationController::class, 'store'])->name('designations.store');
    Route::get('designations/{designation}/edit', [DesignationController::class, 'edit'])->name('designations.edit');
    Route::put('designations/{designation}', [DesignationController::class, 'update'])->name('designations.update');
    Route::delete('designations/{designation}', [DesignationController::class, 'destroy'])->name('designations.destroy');

    // user routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //Profile Route
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');

    // ====================
    // Department Routes
    // ====================
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
    

    //Audit Log
    Route::get('auditlog', [AuditLogController::class, 'index'])->name('auditlog.index');

});



Route::fallback(function () {
    return view('errors.404');
});
