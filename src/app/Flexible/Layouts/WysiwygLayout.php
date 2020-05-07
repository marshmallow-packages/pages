<?php

namespace Marshmallow\Pages\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Markdown;
use Marshmallow\Pages\Flexible\Layouts\Layout;
use Marshmallow\Pages\Flexible\Layouts\Traits\EmptyLayout;

class WysiwygLayout extends Layout
{
	/**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'wysiwyg';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Title + Text';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
        	Text::make('Title'),
            config('pages.wysiwyg')::make('Content'),
        ];
    }

    protected function getComponentClass ()
    {
        return \Marshmallow\Pages\View\Components\Wysiwyg::class;
    }
}