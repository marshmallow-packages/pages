<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    |
    | You can override almost every thing this package uses. We've added some
    | defaults for you. This should work out of the box. If you wish to change
    | any of these defaults you can do so here.
    |
    */

    'database' => [
        'connection' => null,
    ],

    'view' => 'marshmallow::layout',

    'wysiwyg' => env('NOVA_WYSIWYG', \Laravel\Nova\Fields\Trix::class),

    'model' => \Marshmallow\Pages\Models\Page::class,

    'middleware' => \Marshmallow\Pages\Http\Middleware\PageMiddleware::class,

    'controller' => '\Marshmallow\Pages\Http\Controllers\PageController@show',

    /*
    |--------------------------------------------------------------------------
    | Translate resources in Nova
    |--------------------------------------------------------------------------
    |
    | When this is set to true, you will see the language selector when editing
    | a resource in Laravel Nova.
    |
    */
    'nova_translatable_fields' => true,

    /*
    |--------------------------------------------------------------------------
    | Share the page data
    |--------------------------------------------------------------------------
    |
    | If this is anabled the full page will be added to all your View
    | components so you can use this data anyware you want.
    |
    */
    'share_page_to_view_data' => false,


    /*
    |--------------------------------------------------------------------------
    | Translatable
    |--------------------------------------------------------------------------
    |
    | If you are using our Translatable package you need to let us know here.
    | Once you've set this to true, we will load in translated routes and will
    | be able to get the correct page by a translated slug variable.
    |
    */
    'use_multi_languages' => false,


    /*
    |--------------------------------------------------------------------------
    | Breadcrumb
    |--------------------------------------------------------------------------
    |
    | Should pages be added to the Marshmallow Breadcrumb stack?
    |
    */
    'breadcrumb' => false,

    /*
    |--------------------------------------------------------------------------
    | Flexible config
    |--------------------------------------------------------------------------
    |
    | Add a config that will be loaded by the Flexible package if you want to
    | change the default flexible behaviour.
    |
    */
    'flexible_config' => [
        //
    ],
];
