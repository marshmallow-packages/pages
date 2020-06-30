<?php

namespace Marshmallow\Pages\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Marshmallow\Seoable\Seoable;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Nova\Flexible\Nova\Traits\HasFlexable;

class Page extends Resource
{
    use HasFlexable;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Marshmallow\Pages\Models\Page';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name')->sortable()->rules(['required']),
            Text::make('Slug')->sortable()
                ->help(
                    'This is the URL of the page.'.
                    'This is not automaticly updated when you change the name of '.
                    'the page. Please don\'t change the url unless you '.
                    'really have to.'
                )
                ->hideWhenCreating()
                ->displayUsing(
                    function ($value, Model $model, $attribute) {
                        return sprintf(
                            '<a href="%s" target="_blank">%s</a>',
                            $model->route(),
                            $value
                        );
                    }
                )->asHtml(),

            $this->getFlex(),

            Seoable::make('Seo'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}