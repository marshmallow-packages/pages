<?php

namespace Marshmallow\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Marshmallow\Pages\Models\Page;
use App\Http\Controllers\Controller;
use Marshmallow\Breadcrumb\Facades\Breadcrumb;

class PageController extends Controller
{
    public function show(Request $request)
    {
        $page = Page::getByUrl($request)->first()->useForSeo();

        if (config('pages.breadcrumb')) {
            Breadcrumb::add($page->name, $page->getFullPublicPath());
        }

        return view($this->getView($page))->with(
            [
                'page' => $page,
                'layouts' => $page->flex('layout'),
            ]
        );
    }

    protected function getView(Page $page)
    {
        if (isset($page->view) && $page->view && 'default' !== strtolower($page->view)) {
            return $page->view;
        }

        return config('pages.view');
    }
}
