<?php

namespace Marshmallow\Pages\Classes;

use Marshmallow\Pages\Models\Page;
use OptimistDigital\MenuBuilder\Classes\MenuLinkable;

class PageLinkable extends MenuLinkable
{
    public static function getIdentifier(): string
    {
        return 'page';
    }

    public static function getName(): string
    {
        return 'Page Link';
    }

    public static function getOptions($locale): array
    {
        return Page::all()->pluck('name', 'id')->toArray();
    }

    public static function getDisplayValue($value = null, array $parameters = null, array $data = null)
    {
        return 'Page: ' . Page::find($value)->name;
    }

    public static function getValue($value = null, array $parameters = null)
    {
        return Page::find($value);
    }

    public static function getFields(): array
    {
        return [];
    }

    public static function getRules(): array
    {
        return [];
    }

    public static function getData($data = null, array $parameters = null)
    {
        return $data;
    }
}
