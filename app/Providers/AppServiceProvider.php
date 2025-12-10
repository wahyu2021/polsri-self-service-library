<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\NotificationComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\BookRepositoryInterface::class,
            \App\Repositories\BookRepository::class
        );
        $this->app->bind(
            \App\Interfaces\LoanRepositoryInterface::class,
            \App\Repositories\LoanRepository::class
        );
        $this->app->bind(
            \App\Interfaces\LogbookRepositoryInterface::class,
            \App\Repositories\LogbookRepository::class
        );
        $this->app->bind(
            \App\Interfaces\SettingRepositoryInterface::class,
            \App\Repositories\SettingRepository::class
        );
        $this->app->bind(
            \App\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inject notifikasi ke layout utama kapanpun ia dirender
        View::composer('components.layouts.app', NotificationComposer::class);
    }
}
