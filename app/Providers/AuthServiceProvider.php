<?php

namespace App\Providers;

use App\Models\Booking; // Importa Booking
use App\Policies\BookingPolicy; // Importa BookingPolicy
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Esempio originale
        Booking::class => BookingPolicy::class, // Aggiungi questa riga
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}