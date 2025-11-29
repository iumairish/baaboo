<?php

namespace App\Providers;

use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TicketRepositoryInterface::class,
            TicketRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Config::set('auth.guards.admin', [
            'driver' => 'session',
            'provider' => 'admins',
        ]);

        Config::set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model' => \App\Models\Admin::class,
        ]);

        Config::set('auth.passwords.admins', [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ]);
    }
}
