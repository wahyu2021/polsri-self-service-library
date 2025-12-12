<?php

namespace App\Providers;

use App\Http\ViewComposers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
        
        // Custom Pagination View
        Paginator::defaultView('vendor.pagination.polsri');
        Paginator::defaultSimpleView('vendor.pagination.polsri');

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}
