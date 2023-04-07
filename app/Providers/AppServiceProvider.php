<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LogViewer::auth(function ($request) {
            // dd($request->user()->roles());
            // $request->user()
            // && in_array($request->user()->role(), [
            //     'john@example.com',
            // ]);
            return true;
        });

    }
}
