<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TestResponse::macro('assertGqlValidationErrorFor', function ($key) {
            return $this->assertJsonStructure([
                'errors' => [
                    [
                        'extensions' => [
                            'validation' => [
                                $key,
                            ],
                        ],
                    ],
                ],
            ]);
        });
        TestResponse::macro('assertGqlUnauthorized', function () {
            return $this->assertJson([
                'errors' => [
                    [
                        'extensions' => [
                            'category' => 'authorization',
                        ],
                    ],
                ],
            ]);
        });
    }
}
