<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
class AppServiceProvider extends ServiceProvider
{
    /**
      * Register any application services.
      */
     public function register(): void
     {
         // Repository bindings
         $this->app->bind(\App\Repositories\Contracts\IUserRepository::class, \App\Repositories\UserRepository::class);
         $this->app->bind(\App\Repositories\Contracts\IPlanRepository::class, \App\Repositories\PlanRepository::class);
         $this->app->bind(\App\Repositories\Contracts\ISubscriptionRepository::class, \App\Repositories\SubscriptionRepository::class);
         
         // Service bindings
         $this->app->bind(\App\Services\Contracts\IAuthService::class, \App\Services\AuthService::class);
         $this->app->bind(\App\Services\Contracts\IUserService::class, \App\Services\UserService::class);
         $this->app->bind(\App\Services\Contracts\IPlanService::class, \App\Services\PlanService::class);
         $this->app->bind(\App\Services\Contracts\ISubscriptionService::class, \App\Services\SubscriptionService::class);
     }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Scramble API security.
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(SecurityScheme::http('bearer'));
        });
    }
}
