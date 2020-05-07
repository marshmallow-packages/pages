<?php

namespace Marshmallow\Pages\Flexible\Layouts\Traits;

use Laravel\Nova\Fields\Heading;

trait EmptyLayout
{
	public function fields()
    {
        return [
        	Heading::make('This layout has no settings'),
        ];
    }
}