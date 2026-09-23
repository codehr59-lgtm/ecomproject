<?php

namespace App\Providers;

use App\Models\Address;
use App\Policies\AddressPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class AppServiceProvider extends AuthServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Address::class => AddressPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        if (app()->environment('production') || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || isset($_SERVER['VERCEL']) || env('VERCEL')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
