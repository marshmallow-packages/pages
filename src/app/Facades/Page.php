<?php 

namespace Marshmallow\Pages\Facades;

class Page extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Marshmallow\Pages\Page::class;
    }
}