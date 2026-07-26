<?php

namespace App\Providers;

use App\Models\Transaction;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('components.sidebar', function ($view) {
            $pendingCount = Transaction::where('status', 'pending')->count();
            $view->with('pendingCount', $pendingCount);
        });
    }
}