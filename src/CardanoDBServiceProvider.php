<?php

namespace Vampires\CardanoDB;

use Illuminate\Support\ServiceProvider;

class CardanoDBServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'vampires');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'vampires');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cardanodb.php', 'cardanodb');

        // Register the service the package provides.
        $this->app->singleton('cardanodb', function ($app) {
            return new CardanoDB;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cardanodb'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/cardanodb.php' => config_path('cardanodb.php'),
        ], 'cardanodb.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/vampires'),
        ], 'cardanodb.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/vampires'),
        ], 'cardanodb.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/vampires'),
        ], 'cardanodb.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
