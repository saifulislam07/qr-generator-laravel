<?php

namespace Saifulislam07\QrGenerator;

use Illuminate\Support\ServiceProvider;

class QrGeneratorServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any package services.
     */
    public function boot(): void {
        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Load package views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'qr-generator');

        // Publish views
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/qr-generator'),
        ], 'qr-generator-views');
    }

    /**
     * Register any package services.
     */
    public function register(): void {
        //
    }
}
