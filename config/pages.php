<?php

return [

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

    'view' => 'marshmallow::layout',

    'use_multi_languages' => false,

    'wysiwyg' => env('NOVA_WYSIWYG', \Laravel\Nova\Fields\Trix::class),

    'model' => \Marshmallow\Pages\Models\Page::class,

    'middleware' => \Marshmallow\Pages\Http\Middleware\PageMiddleware::class,

    'controller' => '\Marshmallow\Pages\Http\Controllers\PageController@show',

    /**
     * Should the active page be added to the
     * marshmallow breadcrumb stack?
     */
    'breadcrumb' => false,
];
