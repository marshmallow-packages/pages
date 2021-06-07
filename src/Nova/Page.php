<?php

namespace Marshmallow\Pages\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Eminiarts\Tabs\TabsOnEdit;
use Marshmallow\Seoable\Seoable;
use Laravel\Nova\Fields\MorphMany;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\GTMetrix\GTMetrixField;
use Marshmallow\GTMetrix\Actions\CheckGTMetrixScore;
use Marshmallow\Nova\Flexible\Nova\Traits\HasFlexable;
use Marshmallow\Translatable\Facades\TranslatableTabs;
use Marshmallow\Translatable\Traits\TranslatableFields;

class Page extends Resource
{
    use TabsOnEdit;
    use HasFlexable;
    use TranslatableFields;

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

    public static function label()
    {
        return __('Page');
    }

    public static function singularLabel()
    {
        return __('Pages');
    }

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
    public function translatableFields(Request $request)
    {
        return [
            TranslatableTabs::make($this, 'Page editor', [
                'Main' => [
                    Text::make(__('Name'), 'name')->sortable()->rules(['required']),
                    $this->getFlex(__('Layout'), 'layout'),
                ],
                'SEO' => [
                    Text::make(__('Slug'), 'slug')->rules(['required'])
                        ->help(
                            __('This is the URL of the page. This is not automaticly updated when you change the name of the page. Please don\'t change the url unless you really have to.')
                        )
                        ->hideWhenCreating()
                        ->creationRules('unique:pages,slug')
                        ->updateRules('unique:pages,slug,{{resourceId}}')
                        ->displayUsing(
                            function ($value, Model $model, $attribute) {
                                return sprintf(
                                    '<a href="%s" class="link" target="_blank">%s</a>',
                                    $model->route(),
                                    $value
                                );
                            }
                        )->asHtml(),

                    Seoable::make('Seo'),
                ],
                'Advanced' => [

                    GTMetrixField::make('GT Metrix'),

                    Text::make(__('Page view'), 'view')->help(
                        __('This is the view file we use as the base template. If you wish the use the view from the config you can leave this field empty or set it to "Default". Otherwise set it to the blade view selector.')
                    ),
                ],

                MorphMany::make(__('Redirect'), 'redirectable'),
            ])->withToolbar(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new CheckGTMetrixScore(),
        ];
    }
}
