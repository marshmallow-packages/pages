<?php

namespace Marshmallow\Pages;

use Marshmallow\Pages\Page;
use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/config/pages.php', 'pages'
        );

        $this->app->singleton(Page::class, function () {
            return new Page;
        });

        $this->app->alias(Page::class, 'page');
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
        $this->loadViewsFrom(__DIR__.'/views', 'marshmallow');

        $this->loadFactoriesFrom(__DIR__.'/database/factories');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/marshmallow'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }
}
