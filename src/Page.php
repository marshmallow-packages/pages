<?php

namespace Marshmallow\Pages;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Marshmallow\Translatable\Models\Language;
use Marshmallow\Pages\Models\Page as PageModel;

class Page
{
    public $pages;

    public function __construct()
    {
        if ($this->migrationsAreRun()) {
            $this->pages = config('pages.model')::get();
        }
    }

    public function find($key)
    {
        return $this->pages->where('id', $key)->first();
    }

    public function routes()
    {
        if ($this->shouldLoadRoutes()) {
            if (config('pages.use_multi_languages')) {
                $this->loadTranslateableRoutes();
            } else {
                $this->loadNoneTranslateableRoutes();
            }
        }
    }

    public function loadRoutes()
    {
        $this->routes();
    }

    public function migrationsAreRun(): bool
    {
        $connection = config('pages.database.connection');
        $schema_builder = Schema::connection($connection);

        if (!$schema_builder->hasTable('pages')) {
            /**
             * Don't load the routes if the pages table
             * doesnt exist. If this is the case, the
             * migrations haven't fully run yet.
             */
            return false;
        }

        if (!$schema_builder->hasColumn('pages', 'deleted_at')) {
            /**
             * Don't load the routes if deleted_at
             * doesnt exist. If this is the case, the
             * migrations haven't fully run yet.
             */
            return false;
        }

        return true;
    }

    public function shouldLoadRoutes(): bool
    {
        return $this->migrationsAreRun();
    }

    protected function loadNoneTranslateableRoutes()
    {
        $pages = config('pages.model')::get();
        foreach ($pages as $page) {
            Route::middleware($this->getMiddlewareArray($page))
                ->get($page->route(), config('pages.controller'))
                ->name($page->route_name);
        }
    }

    protected function loadTranslateableRoutes()
    {
        $pages = config('pages.model')::get();
        $languages = Language::orderBy('name', 'asc')->get();

        foreach ($languages as $language) {
            foreach ($pages as $page) {
                Route::middleware($this->getMiddlewareArray($page))
                    ->get($page->localeRoute($language), config('pages.controller'))
                    ->name($page->route_name);
                /*
                 * Make sure we load an index route
                 */
                if (in_array($page->localeRoute($language), [
                    '/' . app()->getLocale() . '/',
                    '/' . app()->getLocale(),
                ])) {
                    Route::middleware($this->getMiddlewareArray($page))
                        ->get('/', config('pages.controller'))
                        ->name($page->route_name);
                }
            }
        }
    }

    public function getFlexConfig()
    {
        return config('pages.flexible_config') ?? [];
    }

    protected function getMiddlewareArray(PageModel $page = null)
    {
        $middleware = [
            config('pages.middleware')
        ];

        if ($page && $page->middleware && is_array($page->middleware)) {
            $middleware = array_merge($middleware, $page->middleware);
        }

        return $middleware;
    }

    public function table()
    {
        if (config('pages.database.connection')) {
            return config('pages.database.connection') . '.pages';
        }

        return 'pages';
    }
}
