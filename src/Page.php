<?php

namespace Marshmallow\Pages;

use Illuminate\Support\Facades\Route;
use Marshmallow\Translatable\Models\Language;

class Page
{
    public function loadRoutes()
    {
        if (config('pages.use_multi_languages')) {
            $this->loadTranslateableRoutes();
        } else {
            $this->loadNoneTranslateableRoutes();
        }
    }

    protected function loadNoneTranslateableRoutes()
    {
        $pages = \Marshmallow\Pages\Models\Page::get();
        foreach ($pages as $page) {
            Route::middleware($this->getMiddlewareArray())
                ->get($page->route(), config('pages.controller'))
                ->name($page->route_name);
        }
    }

    protected function loadTranslateableRoutes()
    {
        $pages = \Marshmallow\Pages\Models\Page::get();
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
                        '/'.app()->getLocale().'/',
                        '/'.app()->getLocale(),
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
