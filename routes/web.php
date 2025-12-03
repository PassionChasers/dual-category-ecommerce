<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');


//Products
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/my-products', [ProductController::class, 'myProducts'])->name('product.myproducts');



//Product Category
Route::get('category', [ProductCategoryController::class, 'index'])->name('product.category');


// Settings Routes
 Route::prefix('/settings')->name('settings.')->group(function () {

        // General routes
        Route::get('/', [SettingController::class, 'index'])->name('general');
        // Route::post('/', [SettingController::class, 'store'])->name('store');
        // Route::put('/{setting}', [SettingController::class, 'update'])->name('update');

        // Institutions routes
        Route::get('institutions', [InstitutionsController::class, 'index'])->name('institutions');
        // Route::get('institutions/create', [InstitutionsController::class, 'create'])->name('institutions.create');
        // Route::post('institutions', [InstitutionsController::class, 'store'])->name('institutions.store');
        // Route::get('institutions/{institution}', [InstitutionsController::class, 'show'])->name('institutions.show');
        // Route::get('institutions/{institution}/edit', [InstitutionsController::class, 'edit'])->name('institutions.edit');
        // Route::put('institutions/{institution}', [InstitutionsController::class, 'update'])->name('institutions.update');
        // Route::delete('institutions/{institution}', [InstitutionsController::class, 'destroy'])->name('institutions.destroy');
    });



// user routes
Route::get('users', [UserController::class, 'index'])->name('users.index');

//Designations
Route::get('designations', [DesignationController::class, 'index'])->name('designations.index');

// Department Routes
Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');

// Admin Profile Route
Route::get('admin/profile', function () {
    return view('admin.profile');
})->name('admin.profile');