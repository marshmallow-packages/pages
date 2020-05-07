<?php

namespace Marshmallow\Pages;

use Illuminate\Support\Facades\Route;
use Marshmallow\MultiLanguage\Models\Language;

class Page
{
    private $default_layouts = [
        'wysiwyg' => \Marshmallow\Pages\Flexible\Layouts\WysiwygLayout::class,
        'uspfontawesome' => \Marshmallow\Pages\Flexible\Layouts\UspFontawesomeLayout::class,
    ];

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

    public function getLayouts ()
    {
        if (!empty(config('pages.layouts'))) {
            if (config('pages.merge_layouts') === true) {
                return array_merge(
                    config('pages.layouts'),
                    $this->default_layouts
                );
            }
            return config('pages.layouts');
        }
        return $this->default_layouts;
    }

    public function render (\Marshmallow\Pages\Models\Page $page)
    {
        $html = '';
        foreach ($page->layout as $layout) {
            $html .= $layout->render();
        }
        return $html;
    }
}
