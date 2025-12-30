<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

use App\Models\Setting;
use App\Observers\ModelObserver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
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
            \App\Models\User::class,
            \App\Models\Order::class,
            \App\Models\Invoice::class,
            \App\Models\Customer::class,
            \App\Models\MedicalStore::class,
            \App\Models\Restaurant::class,
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
