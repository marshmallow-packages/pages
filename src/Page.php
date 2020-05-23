<?php

namespace Marshmallow\Pages;

use Illuminate\Support\Facades\Route;
use Marshmallow\MultiLanguage\Models\Language;

class Page
{
	public function loadRoutes ()
    {
        if (config('pages.use_multi_languages')) {
            $this->loadTranslateableRoutes();
        } else {
            $this->loadNoneTranslateableRoutes();
        }
        $this->loadNoneTranslateableRoutes();
    }

    protected function loadNoneTranslateableRoutes ()
    {
        $pages = \Marshmallow\Pages\Models\Page::get();
        foreach ($pages as $page) {
            Route::middleware($this->getMiddlewareArray())
                ->get($page->route(), config('pages.controller'));
        }
    }

    protected function loadTranslateableRoutes ()
    {
        $pages = \Marshmallow\Pages\Models\Page::get();
        $languages = Language::orderBy('code', 'asc')->get();

        foreach ($languages as $language) {
            foreach ($pages as $page) {
                Route::middleware($this->getMiddlewareArray())
                            ->get($page->localeRoute($language), config('pages.controller'));
            }
        }
    }

    protected function getMiddlewareArray ()
    {
    	return [
    		'web',
    		config('pages.middleware'),
    	];
    }
}
