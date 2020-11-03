<?php

namespace Marshmallow\Pages;

use Illuminate\Support\ServiceProvider;
use Marshmallow\Pages\Commands\InstallPagesCommand;

class PagesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/../config/pages.php',
            'pages'
        );

        $this->app->singleton(Page::class, function () {
            return new Page;
        });

        $this->app->alias(Page::class, 'page');

        $this->commands([
            InstallPagesCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Views
         */
        $this->loadViewsFrom(__DIR__.'/../views', 'marshmallow');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/marshmallow'),
        ]);

        $this->publishes([
            __DIR__ . '/../config/pages.php' => config_path('pages.php'),
        ]);
    }
}
