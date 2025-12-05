<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use App\Models\Testimonial;

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
        \Illuminate\Support\Facades\Blade::component('admin-layout', \App\View\Components\AdminLayout::class);

        // View composer for welcome page
        View::composer('welcome', function ($view) {
            $view->with('featuredProducts', Product::with('category')->take(6)->get());
            $view->with('testimonials', Testimonial::with('user')->take(3)->get());
        });
    }
}
