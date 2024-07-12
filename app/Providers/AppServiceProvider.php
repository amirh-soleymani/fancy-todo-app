<?php

namespace App\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
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
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Response::macro('successResponse', function ($message, $data) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data
            ]);
        });

        Response::macro('errorResponse', function ($message, $data, $statusCode) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
                'data' => $data
            ], $statusCode);
        });
    }
}
