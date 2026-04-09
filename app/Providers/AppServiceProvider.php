<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('components.public-navbar', function ($view) {
            $view->with(
                'categories',
                collect([
                    (object) ['name' => 'Bayi', 'slug' => 'bayi'],
                    (object) ['name' => 'Remaja', 'slug' => 'remaja'],
                    (object) ['name' => 'Dewasa', 'slug' => 'dewasa'],
                    (object) ['name' => 'Lansia', 'slug' => 'lansia'],
                ])
            );
        });
    }
}
