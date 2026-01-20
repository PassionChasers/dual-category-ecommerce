<?php

namespace App\Providers;

use App\Models\Ad;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\MenuItem;
use App\Models\OrderItem;

use App\Models\Restaurant;
use App\Models\RewardCoin;
use App\Models\MedicalStore;
use App\Models\MenuCategory;
use App\Models\Notification;
use App\Models\MedicineCategory;
use App\Observers\ModelObserver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Cache;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // OPTIMIZED: Only observe critical models to reduce database overhead
        $observedModels = [
            Ad::class,
            Medicine::class,
            MedicineCategory::class,
            MenuCategory::class,
            MenuItem::class,
            Notification::class,
            OrderItem::class,
            Product::class,
            RewardCoin::class,
            Setting::class,
            User::class,
            Order::class,
            Invoice::class,
            Customer::class,
            MedicalStore::class,
            Restaurant::class,
        ];

        foreach ($observedModels as $model) {
            if (class_exists($model)) {
                $model::observe(ModelObserver::class);
            }
        }

        // Share 'setting' with all views
        // View::composer('*', function ($view) {
        //     $setting = Setting::first();
        //     $view->with('setting', $setting);
        // });
        $settings = Cache::rememberForever('settings', function () {
            return Setting::first();
        });

        view()->share('setting', $settings);


    }
}
