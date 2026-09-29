<?php

namespace App\Providers;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
       // 2. TAMBAHKAN INI AGAR PANAH TIDAK RAKSASA LAGI
        Paginator::useBootstrapFour();

        // 1. Izin Input Arsip Vital (Admin & UP)
        Gate::define('input-vital', function (User $user) {
            return in_array($user->role, ['admin', 'arsiparis_up']);
        });

        // 2. Izin Input Arsip Terjaga (Admin & UP)
        Gate::define('input-terjaga', function (User $user) {
            return in_array($user->role, ['admin', 'arsiparis_up']);
        });

        // 3. Izin Input Arsip Inaktif (Admin & UK)
        Gate::define('input-inaktif', function (User $user) {
            return in_array($user->role, ['admin', 'arsiparis_uk']);
        });

        // 4. Izin Khusus Admin
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}