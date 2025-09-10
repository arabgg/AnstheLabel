<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        // if(config('app.env') === 'local') {
        //     URL::forceScheme('https');
        // }

        View::composer('*', function ($view) {
            $cart = session()->get('cart', []);
            $view->with('cartCount', count($cart));
        });

        Validator::extend('email_active', function ($attribute, $value, $parameters, $validator) {
            return checkdnsrr(explode('@', $value)[1], 'MX');
        });
    }
}
