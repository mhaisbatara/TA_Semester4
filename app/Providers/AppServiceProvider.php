<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // Override cara Sanctum authenticate token
        Sanctum::authenticateAccessTokensUsing(
            function ($accessToken, bool $isValid) {
                return $isValid;
            }
        );

        // Paksa Sanctum pakai findToken kita via request macro
        app('auth')->viaRequest('sanctum', function (Request $request) {
            $bearerToken = $request->bearerToken();
            if (!$bearerToken) return null;

            $token = PersonalAccessToken::findToken($bearerToken);
            if (!$token) return null;

            $tokenable = $token->tokenable;
            if (!$tokenable) return null;

            return $tokenable->withAccessToken($token);
        });
    }
}
