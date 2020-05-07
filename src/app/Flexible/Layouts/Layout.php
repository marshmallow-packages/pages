<?php

namespace Marshmallow\Pages\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Markdown;
use Mdixon18\Fontawesome\Fontawesome;
use Whitecube\NovaFlexibleContent\Layouts\Layout as BaseLayout;

class Layout extends BaseLayout
{
	public function render ()
    {
    	$component_class_name = $this->getComponentClass();
        return (new $component_class_name($this))->render();
    }
}