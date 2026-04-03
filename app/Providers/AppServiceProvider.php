<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
       if (Schema::hasTable('permissions')) {
            Permission::all()->each(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermission($permission->slug);
                });
            });
        }
            
        // Global Admin/SuperAdmin/Task-Manager Access Gate
        Gate::define('access-admin', function ($user) {
            return $user->hasRole(['admin', 'superadmin', 'task-manager']);
        });

        // God Mode: Admins & SuperAdmins bypass specific permission checks
        Gate::before(function ($user) {
            if ($user->hasRole(['admin', 'superadmin'])) {
                return true;
            }
        });
    }

    public const HOME = '/dashboard';
}