<?php

namespace Marshmallow\Pages\Http\Controllers;

use Illuminate\Http\Request;
use Marshmallow\Pages\Models\Page;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
	public function show (Request $request)
	{
		$page = Page::getByUrl($request)->first();
		return view(config('pages.view'))->with([
			'page' => $page,
			'layouts' => $page->getLayoutCollection(),
		]);
	}
}
