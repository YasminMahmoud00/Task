<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Services\ProductService;
use App\Services\OrderService;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository();
        });

        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class));
        });


    $this->app->bind(OrderRepository::class, function ($app) {
        return new OrderRepository();
    });

    $this->app->bind(OrderService::class, function ($app) {
        return new OrderService($app->make(OrderRepository::class));
    });
    }

    public function boot(): void
    {
        //
    }
}
