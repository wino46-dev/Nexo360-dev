<?php

namespace App\Providers;

use App\Http\Resources\Admin\GrabacionTarjetumResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Share panelPrefix globally so Blade templates can safely use it
        try {
            $isExternal = request()->routeIs('external.*');
            $prefix = $isExternal ? 'external' : 'admin';
            // When in external panel, search for views first under resources/views/external
            if ($isExternal) {
                try {
                    View::getFinder()->prependLocation(resource_path('views/external'));
                } catch (\Throwable $e) {
                    // ignore if view finder not available
                }
            }
        } catch (\Throwable $e) {
            $prefix = 'admin';
        }
        View::share('panelPrefix', $prefix);

        $roles            = Role::with('permissions')->get();
        $permissionsArray = [];

        foreach ($roles as $role) {
            foreach ($role->permissions as $permissions) {
                $permissionsArray[$permissions->title][] = $role->id;
            }
        }

        foreach ($permissionsArray as $title => $roles) {
            Gate::define($title, function (User $user) use ($roles) {
                return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
            });
        }

        // Share SweetAlert flag for Totem role/type mismatch for privileged users at view render time
        View::composer('*', function ($view) {
            try {
                $showTotemTypeAlert = false;
                $totemTypeMismatchCount = 0;
                if (auth()->check()) {
                    $current = auth()->user();
                    if ($current->can('user_create') || $current->can('user_edit')) {
                        $totemTypeMismatchCount = User::whereHas('roles', function($q) {
                            $q->whereRaw('LOWER(title) = ?', ['totem']);
                        })->where(function($q) {
                            $q->whereNull('type')
                              ->orWhereRaw('LOWER(type) <> ?', ['totem']);
                        })->count();
                        $showTotemTypeAlert = $totemTypeMismatchCount > 0;
                    }
                }
                $view->with('showTotemTypeAlert', $showTotemTypeAlert)
                     ->with('totemTypeMismatchCount', $totemTypeMismatchCount);
            } catch (\Throwable $e) {
                // ignore if not in a web request
            }
        });

    }
}
