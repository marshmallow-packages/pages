<?php

namespace Marshmallow\Pages\Casts;

use Marshmallow\Pages\Facades\Page;
use Marshmallow\Pages\Flexible\Layouts\WysiwygLayout;
use Marshmallow\Pages\Flexible\Layouts\UspFontawesomeLayout;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast as BaseFlexibleCast;

class FlexibleCast extends BaseFlexibleCast
{
    protected $layouts = [];

    public function __construct ()
    {
        $this->layouts = Page::getLayouts();
    }
}