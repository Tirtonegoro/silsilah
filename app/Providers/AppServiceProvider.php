<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        require_once app_path() . '/Helpers/functions.php';

        // Always redirect to https.
        if ($this->app->environment() === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }

        \Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            $user = \Auth::user();

            return $user && \Hash::check($value, $user->password);
        });

        \Validator::extend('same_password', function ($attribute, $value, $parameters, $validator) {
            $user = \Auth::user();

            return $user && !\Hash::check($value, $user->password);
        });

        $this->prepareLocaleOptions();
    }

    protected function prepareLocaleOptions(): void
    {
        $locales = config('app.available_locales', ['en', 'id']);

        if (is_string($locales)) {
            $locales = explode(',', $locales);
            array_walk($locales, 'trim');
        }

        View::share('available_locales', $locales);

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
