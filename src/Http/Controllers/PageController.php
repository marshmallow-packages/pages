<?php

namespace Marshmallow\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Marshmallow\Pages\Models\Page;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function show(Request $request)
    {
        $page = Page::getByUrl($request)->first()->useForSeo();
        return view($this->getView($page))->with(
            [
                'page' => $page,
                'layouts' => $page->flex('layout'),
            ]
        );
    }

    protected function getView(Page $page)
    {
        if (isset($page->view) && $page->view && strtolower($page->view) !== 'default') {
            return $page->view;
        }
        return config('pages.view');
    }
}
