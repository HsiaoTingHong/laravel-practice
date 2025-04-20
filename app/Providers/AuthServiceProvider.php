<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        // Passport 預設 Token 核發後一年過期，這邊修改自訂過期時間
        // access_token 設定核發後 15 天過期
        Passport::tokensExpireIn(now()->addDay(15));

        // refresh_token 設定核發後 30 天過期
        Passport::refreshTokensExpireIn(now()->addDay(30));
    }
}
