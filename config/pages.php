<?php

return [

    'view' => 'marshmallow::layout',

    'use_multi_languages' => false,

    'wysiwyg' => env('NOVA_WYSIWYG', \Laravel\Nova\Fields\Trix::class),

    'middleware' => \Marshmallow\Pages\Http\Middleware\PageMiddleware::class,

    'controller' => '\Marshmallow\Pages\Http\Controllers\PageController@show',
];
