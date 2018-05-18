<?php

namespace App\Providers;

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
        require_once app_path().'/Helpers/helpers.php';
        require_once app_path().'/Helpers/date_time.php';

        \Validator::extend('not_exists', function ($attribute, $value, $parameters) {
            return \DB::table($parameters[0])
                ->where($parameters[1], $value)
                ->count() < 1;
        });
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
