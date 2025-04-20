<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmail;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartModelRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(CartRepository::class, CartModelRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen([
            SendWelcomeEmail::class
        ]);
    }
}