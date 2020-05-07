<?php

namespace Marshmallow\Pages\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Markdown;
use Mdixon18\Fontawesome\Fontawesome;
use Whitecube\NovaFlexibleContent\Flexible;
use Marshmallow\Pages\Flexible\Layouts\Layout;
use Marshmallow\Pages\Flexible\Layouts\Traits\HasItems;

class UspFontawesomeLayout extends Layout
{
    use HasItems;

    protected $items_attribute = 'usps';

	/**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'uspfontawesome';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'USP (Fontawesome)';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Flexible::make('USPS')
                ->addLayout('USP', 'uspfontawesome', [
                    Text::make('Title'),
                    Fontawesome::make('Icon'),
                    config('pages.wysiwyg')::make('Content'),
                ])->button('Add USP')->fullWidth()
        ];
    }

    protected function getComponentClass ()
    {
        return \Marshmallow\Pages\View\Components\UspFontawesome::class;
    }
}