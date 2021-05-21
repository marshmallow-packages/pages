<?php

namespace Marshmallow\Pages;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Marshmallow\Translatable\Models\Language;

class Page
{
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

    public function shouldLoadRoutes(): bool
    {
        if (!Schema::hasTable('pages')) {
            /**
             * Don't load the routes if the pages table
             * doesnt exist. If this is the case, the
             * migrations haven't fully run yet.
             */
            return false;
        }

        if (!Schema::hasColumn('pages', 'deleted_at')) {
            /**
             * Don't load the routes if deleted_at
             * doesnt exist. If this is the case, the
             * migrations haven't fully run yet.
             */
            return false;
        }

        return true;
    }

    protected function loadNoneTranslateableRoutes()
    {
        $pages = config('pages.model')::get();
        foreach ($pages as $page) {
            Route::middleware($this->getMiddlewareArray())
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
                Route::middleware($this->getMiddlewareArray())
                    ->get($page->localeRoute($language), config('pages.controller'))
                    ->name($page->route_name);
                /*
                 * Make sure we load an index route
                 */
                if (in_array($page->localeRoute($language), [
                    '/' . app()->getLocale() . '/',
                    '/' . app()->getLocale(),
                ])) {
                    Route::middleware($this->getMiddlewareArray())
                        ->get('/', config('pages.controller'))
                        ->name($page->route_name);
                }
            }
        }
    }

    protected function getMiddlewareArray()
    {
        return [
            'web',
            config('pages.middleware'),
        ];
    }
}
