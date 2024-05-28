<?php

namespace Marshmallow\Pages\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Marshmallow\Seoable\Seoable;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\MorphMany;
use Illuminate\Database\Eloquent\Model;
use Outl1ne\MultiselectField\Multiselect as MMMultiselect;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marshmallow\Pages\Facades\Page as PageFacade;
use Marshmallow\Nova\Flexible\Nova\Traits\HasFlexable;
use Marshmallow\Translatable\Facades\TranslatableTabs;
use Marshmallow\Translatable\Traits\TranslatableFields;

class Page extends Resource
{
    use HasFlexable;
    use TranslatableFields;
    use HasTabs;

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

    public static function group()
    {
        return __('Content');
    }

    public static function label()
    {
        return __('Page');
    }

    public static function singularLabel()
    {
        return __('Pages');
    }

    public function subtitle()
    {
        return $this->route();
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
        'slug',
        'layout',
    ];

    public function translatableFieldsEnabled()
    {
        return config('pages.nova_translatable_fields');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function translatableFields(NovaRequest $request)
    {
        $table = PageFacade::table();

        return [
            TranslatableTabs::make($this, 'Page editor', [
                'Main' => [
                    Text::make(__('Name'), 'name')->sortable()->rules(['required']),
                    $this->getFlex(__('Layout'), 'layout')->loadConfig(PageFacade::getFlexConfig()),
                ],
                Seoable::make('SEO content'),
                'Advanced' => [
                    Text::make(__('Slug'), 'slug')->rules(['required'])
                        ->help(
                            __('This is the URL of the page. This is not automaticly updated when you change the name of the page. Please don\'t change the url unless you really have to.')
                        )
                        ->hideWhenCreating()
                        ->creationRules("unique:{$table},slug")
                        ->updateRules("unique:{$table},slug,{{resourceId}}")
                        ->displayUsing(
                            function ($value, Model $model, $attribute) {
                                if ($model->hide_link_from_index) {
                                    return null;
                                }

                                $icon = '';
                                $route = $model->route();
                                if (!$model->active) {
                                    $route = $model->temporaryRoute();

                                    $icon = '<span title="' . __('Inactive') . '" class="inline-flex ml-3" style="color:#bbb;"><svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 0 24 24" width="18"><path fill="#bbb" d="M19.35 10.04C18.67 6.59 15.64 4 12 4c-1.48 0-2.85.43-4.01 1.17l1.46 1.46C10.21 6.23 11.08 6 12 6c3.04 0 5.5 2.46 5.5 5.5v.5H19c1.66 0 3 1.34 3 3 0 1.13-.64 2.11-1.56 2.62l1.45 1.45C23.16 18.16 24 16.68 24 15c0-2.64-2.05-4.78-4.65-4.96zM3 5.27l2.75 2.74C2.56 8.15 0 10.77 0 14c0 3.31 2.69 6 6 6h11.73l2 2L21 20.73 4.27 4 3 5.27zM7.73 10l8 8H6c-2.21 0-4-1.79-4-4s1.79-4 4-4h1.73z"/></svg>';
                                }

                                if (!$icon) {
                                    $icon =  ' <i class="far fa-external-link-alt"></i> ';
                                }

                                return sprintf(
                                    '<div class="flex"><a href="%s" class="font-semibold text-gray-500 no-underline dim hover:text-primary-500" target="_blank">%s</a>' . $icon . '</div>',
                                    $route,
                                    $value
                                );
                            }
                        )->asHtml(),

                    Text::make(__('Page view'), 'view')->help(
                        __('This is the view file we use as the base template. If you wish the use the view from the config you can leave this field empty or set it to "Default". Otherwise set it to the blade view selector.')
                    )->hideFromIndex(),

                    Boolean::make(__('Active'), 'active')->default(true)->help(
                        __('This page will result in a 404 when its inactive. If you click and the link to this page on the index of the pages module, a temporary url will be created where you can view the page. This can be very handy if you are creating a new page but it itsn\'t finshed yet.')
                    )->hideFromIndex(),

                    Boolean::make(__('Hide link from index'), 'hide_link_from_index')->help(
                        __('Some pages don\'t have a default url to visit from the index of the pages module because it needs more information. For instance user information or order information. If you enable this, the clickable link will be hidden on the pages index page.')
                    )->hideFromIndex(),

                    MMMultiselect::make(__('Middleware'), 'middleware')->hideFromIndex()->taggable()->help(__('You can add extra middleware to this page. This is useful if you want to add a middleware to a page that is not the default one. Please use the <strong>full namespace</strong> of the middleware. <strong>Eq: \App\Http\Middleware\HasActiveShoppingCartMiddleware</strong>')),
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
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
