<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdController;
// use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuItemController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MedicineController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\FoodOrderController;
use App\Http\Controllers\DepartmentController;

use App\Http\Controllers\DesignationController;
use App\Http\Controllers\FoodCategoryController;

use App\Http\Controllers\InstitutionsController;
use App\Http\Controllers\MedicineOrderController;

use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\MedicalStoreController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RewardTransactionController;



/*
|--------------------------------------------------------------------------
| Maintenance / Utility Routes
|--------------------------------------------------------------------------
*/

// Clear cache (LOCAL ONLY)
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

/*
|--------------------------------------------------------------------------
| Public (Frontend) Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Static frontend pages
Route::view('/about', 'frontend.about')->name('about');
Route::view('/faq', 'frontend.faq')->name('faq');
Route::view('/support', 'frontend.support')->name('support');
Route::view('/contact', 'frontend.contact')->name('contact');
Route::view('/privacy-policy', 'frontend.privacy')->name('privacy');

/*
|--------------------------------------------------------------------------
| Guest Routes (Not Logged In)
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard & Auth
    |----------------------------------------------------------------------
    */
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');

    // NOTE: In Laravel it's recommended to make logout a POST route.
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Product Routes - Food
    |----------------------------------------------------------------------
    */
    // Route::resource('food', FoodController::class);
    // menu-items resource and products-food page now use MenuItemController
    Route::resource('menu-items', MenuItemController::class);
    Route::get('/products-food', [\App\Http\Controllers\MenuItemController::class, 'index'])->name('product.food.index');
    Route::get('/food-category', [\App\Http\Controllers\MenuCategoryController::class, 'index'])->name('product.food.category');
    Route::post('/food-category', [\App\Http\Controllers\MenuCategoryController::class, 'store'])->name('product.food.category.store');
    Route::put('/food-category/{id}', [\App\Http\Controllers\MenuCategoryController::class, 'update'])->name('product.food.category.update');
    Route::delete('/food-category/{id}', [\App\Http\Controllers\MenuCategoryController::class, 'destroy'])->name('product.food.category.destroy');

    /*
    |----------------------------------------------------------------------
    | Product Routes - Medicine
    |----------------------------------------------------------------------
    */
    Route::get('/products-medicine', [MedicineController::class, 'index'])->name('product.medicine.index');
    Route::post('/product-medicine',[MedicineController::class, 'create'])->middleware('auth')->name('product.medicine.store');
    Route::get('/medicine-category', [MedicineCategoryController::class, 'index'])->name('product.medicine.category');

    /*
    |----------------------------------------------------------------------
    | Orders
    |----------------------------------------------------------------------
    */
    // All product orders
    Route::get('/product-order-list', [OrderController::class, 'allOrders'])->name('orders.index');

    // Food orders
    Route::get('/food-order-list', [OrderController::class, 'foodOrders'])->name('orders.food.index');

     // Food orders for Restaurants business
    Route::get('/restaurant-food-order-list', [OrderController::class, 'restaurantOrders'])->name('orders.restaurant-food.index');

    // Medicine orders
    Route::get('/medicine-order-list', [OrderController::class, 'medicineOrders'])->name('orders.medicine.index');

    //Medicine orders for medicalstores business
     Route::get('/medicalstore-medicine-order-list', [OrderController::class, 'medicalstoreOrders'])->name('orders.medicalstore-medicine.index');

    //All product order details Route
    Route::get('all-product-orders-details/{id}', [OrderController::class, 'showProductDetails'])->name('orders.showProductDetail');

    //Food order Details route
    Route::get('food-orders-details/{id}', [OrderController::class, 'showFoodDetails'])->name('orders.showFoodDetail');

    //Medicine order details route
    Route::get('medicine-orders-details/{id}', [OrderController::class, 'showMedicineDetails'])->name('orders.showMedicineDetail');
    
    //Delete order route
    Route::delete('delete-product-orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    //search order route
    Route::get('orders/search', [OrderController::class, 'search'])->name('orders.search');

    //update order route
    Route::put('/orders/update', [OrderController::class, 'update'])->name('orders.update');

    // Update order status to Cancelled when cancell by admin
    Route::patch('orders/cancel/{id}', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Update order status to Rejected when reject by business
    Route::patch('orders/reject/{id}', [OrderController::class, 'reject'])->name('orders.reject');

    // Update order status to Accepted when Accept by business
    Route::patch('orders/accept/{id}', [OrderController::class, 'accept'])->name('orders.accept');

    //Assign Medical Store to Medicine Order
    Route::post('/orders/assign-store', [OrderController::class, 'assignStore'])->name('orders.assign-store');
    
    // Update order status (general)
    Route::post('/orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    //Create and store order in orderitems table
    Route::post('/order-items', [OrderController::class, 'store'])->name('order-items.store');

    Route::post('/order-items/store-multiple', [OrderController::class, 'storeMultiple'])->name('order-items.storeMultiple');




    /*
    |----------------------------------------------------------------------
    | Settings & Institutions
    |----------------------------------------------------------------------
    */
    Route::prefix('settings')->name('settings.')->group(function () {
        // General settings
        Route::get('/', [SettingController::class, 'index'])->name('general');
        Route::post('/', [SettingController::class, 'store'])->name('store');
        Route::put('/{setting}', [SettingController::class, 'update'])->name('update');

        // Institutions
        Route::get('/institutions', [InstitutionsController::class, 'index'])->name('institutions');
        Route::get('/institutions/create', [InstitutionsController::class, 'create'])->name('institutions.create');
        Route::post('/institutions', [InstitutionsController::class, 'store'])->name('institutions.store');
        Route::get('/institutions/{institution}', [InstitutionsController::class, 'show'])->name('institutions.show');
        Route::get('/institutions/{institution}/edit', [InstitutionsController::class, 'edit'])->name('institutions.edit');
        Route::put('/institutions/{institution}', [InstitutionsController::class, 'update'])->name('institutions.update');
        Route::delete('/institutions/{institution}', [InstitutionsController::class, 'destroy'])->name('institutions.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Designations
    |----------------------------------------------------------------------
    */
    Route::prefix('designations')->name('designations.')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::post('/', [DesignationController::class, 'store'])->name('store');
        Route::get('/{designation}/edit', [DesignationController::class, 'edit'])->name('edit');
        Route::put('/{designation}', [DesignationController::class, 'update'])->name('update');
        Route::delete('/{designation}', [DesignationController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Users (admin, customers, medicalstores, restaurants)
    |----------------------------------------------------------------------
    */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/admin', [UserController::class, 'admin'])->name('admin.index');
        Route::get('/customers', [UserController::class, 'customers'])->name('customers.index');
        Route::get('/restaurants', [UserController::class, 'restaurants'])->name('restaurants.index');
        Route::get('/medicalstores', [UserController::class, 'medicalstores'])->name('medicalstores.index');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

      /*
    |----------------------------------------------------------------------
    | Customers
    |----------------------------------------------------------------------
    */
    // Route::prefix('customers')->name('customers.')->group(function () {
    //     Route::get('/', [CustomerController::class, 'index'])->name('index');
    //     Route::post('/store', [CustomerController::class, 'store'])->name('store');
    //     Route::put('/update/{customer}', [CustomerController::class, 'update'])->name('update');
    //     Route::delete('/destroy/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    // });

      /*
    |----------------------------------------------------------------------
    | MedicalStores
    |----------------------------------------------------------------------
    */
    Route::prefix('medicalStores')->name('medicalStores.')->group(function () {
        Route::post('/store', [MedicalStoreController::class, 'store'])->name('store');
        Route::put('/update/{customer}', [MedicalStoreController::class, 'update'])->name('update');
        Route::delete('/destroy/{customer}', [MedicalStoreController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Profile
    |----------------------------------------------------------------------
    |
    | NOTE: You had two GET routes for "profile" (with and without a slash),
    | which conflict. Here we keep a single, clear pair:
    |   - GET /profile  -> show edit form
    |   - PUT /profile  -> update profile
    |
    */
    Route::get('/admin.profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    /*
    |----------------------------------------------------------------------
    | Departments
    |----------------------------------------------------------------------
    */
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Audit Log
    |----------------------------------------------------------------------
    */
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog.index');

    /*
    |----------------------------------------------------------------------
    | Reward Transactions
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('reward-transactions', [RewardTransactionController::class, 'index'])->name('reward-transactions.index');
        Route::get('reward-transactions/{rewardTransaction}', [RewardTransactionController::class, 'show'])->name('reward-transactions.show');
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});


// Rough routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Resourceful routes
    Route::get('medicine-categories', [MedicineCategoryController::class, 'index'])->name('medicine-categories.index');
    Route::post('medicine-categories', [MedicineCategoryController::class, 'store'])->name('medicine-categories.store');
    Route::put('medicine-categories/{id}', [MedicineCategoryController::class, 'update'])->name('medicine-categories.update');
    Route::delete('medicine-categories/{id}', [MedicineCategoryController::class, 'destroy'])->name('medicine-categories.destroy');

    // inside your admin group
    Route::get('medicines/{id}', [MedicineController::class, 'show'])->name('medicines.show');
    Route::get('medicines/{id}/print', [MedicineController::class, 'print'])->name('medicines.print');
    Route::get('medicines/{id}/export-pdf', [MedicineController::class, 'exportPdf'])->name('medicines.exportPdf');

    // Additional actions
    // Route::post('medicine-categories/{id}/restore', [MedicineCategoryController::class, 'restore'])->name('medicine-categories.restore');
    // Route::delete('medicine-categories/{id}/force-delete', [MedicineCategoryController::class, 'forceDelete'])->name('medicine-categories.forceDelete');
    // Route::post('medicine-categories/{id}/toggle-active', [MedicineCategoryController::class, 'toggleActive'])->name('medicine-categories.toggleActive');
    Route::post('medicine-categories/{id}/toggle-active', [MedicineCategoryController::class, 'toggleActive'])
        ->name('medicine-categories.toggleActive');
});

// use App\Http\Controllers\MedicineController;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::post('medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::put('medicines/{id}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('medicines/{id}', [MedicineController::class, 'destroy'])->name('medicines.destroy');

    Route::post('medicines/{id}/toggle-active', [MedicineController::class, 'toggleActive'])->name('medicines.toggleActive');
});

// use App\Http\Controllers\MedicalStoreController;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('allmedical-stores', [MedicalStoreController::class, 'allMedicalstores'])->name('medicalstores.list');
    Route::get('medical-stores', [MedicalStoreController::class, 'index'])->name('medicalstores.index');
    Route::post('medical-stores', [MedicalStoreController::class, 'store'])->name('medicalstores.store');
    Route::get('medical-stores/{id}', [MedicalStoreController::class, 'show'])->name('medicalstores.show');
    Route::put('medical-stores/{id}', [MedicalStoreController::class, 'update'])->name('medicalstores.update');
    Route::delete('medical-stores/{id}', [MedicalStoreController::class, 'destroy'])->name('medicalstores.destroy');

    Route::post('medical-stores/{id}/toggle-active', [MedicalStoreController::class, 'toggleActive'])->name('medicalstores.toggleActive');


    
});

// use App\Http\Controllers\RestaurantController;
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/restaurants', [RestaurantController::class, 'allRestaurants'])->name('restaurants.list');
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update'])->name('restaurants.update');
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
});


// use App\Http\Controllers\AdController;
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::patch('/ads/{ad}/toggle', [AdController::class, 'toggle'])->name('ads.toggle');
});