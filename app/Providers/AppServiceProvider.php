<?php

namespace App\Providers;

use App;
use Auth;
use MongoId;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        session_start();

        
        if (Auth::check()) {
            App::setLocale(Auth::user()->language);
        } else {
            App::setLocale(session('lang'));
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
