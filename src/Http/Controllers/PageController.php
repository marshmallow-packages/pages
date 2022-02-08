<?php

namespace Marshmallow\Pages\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Marshmallow\Breadcrumb\Facades\Breadcrumb;

class PageController extends Controller
{
    public function show(Request $request)
    {
        $page = config('pages.model')::getByUrl($request)->first();

        abort_unless($page, 404);

        if (!$page->active) {
            if (!$request->hasValidSignature(false)) {
                abort(404);
            }
        }

        $page->useForSeo();

        if (config('pages.breadcrumb')) {
            Breadcrumb::add($page->name, $page->getFullPublicPath());
        }

        if (config('pages.share_page_to_view_data')) {
            View::share('page', $page);
        }

        return view($page->getView())->with(
            [
                'page' => $page,
                'layouts' => $page->flex('layout'),
            ]
        );
    }
}
