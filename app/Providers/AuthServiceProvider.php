<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Patient;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register all Spatie permissions as Gates
        Gate::before(function (User $user, string $ability) {
            // Check if user has permission through Spatie
            if ($user->hasPermissionTo($ability)) {
                return true;
            }
        });

        // Define specific gates for patient deletion
        Gate::define('delete-patient', function (User $user, Patient $patient = null) {
            // Allow both admin and medic roles to delete patients
            return $user->hasRole('admin') ||
                $user->hasRole('medic') ||
                $user->hasPermissionTo('delete-patient');
        });
    }
}
