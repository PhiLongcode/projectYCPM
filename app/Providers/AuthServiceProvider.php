<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        if (Schema::hasTable('permissions')) {
            $permissions = Permission::all();
            foreach($permissions as $permission){
                Gate::define($permission->slug, function (User $user) use ($permission){
                    // dd('oke');
                    return $user->hasPermission($permission->slug);
                });
            }
        }
    }
}
