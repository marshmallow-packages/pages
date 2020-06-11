<?php
/**
 * Config file for Layouts available in Nova
 *
 * PHP version 7.4
 *
 * @category Config
 * @package  Marshmallow\Pages
 * @author   Stef van Esch <stef@marshmallow.dev>
 * @license  MIT Licence
 * @link     https://marshmallow.dev
 */

return [

    'view' => 'marshmallow::layout',

    'use_multi_languages' => true,

    /**
     * Which WYSIWYG editor should we load for you in our default
     * layouts? You can specify your own in your custom layout. Please
     * use `config('pages.wysiwyg')::make('Content');` on your custom layouts.
     */
    'wysiwyg' => env('NOVA_WYSIWYG', \Laravel\Nova\Fields\Trix::class),

    /**
     * Your custom layouts. Please check the readme.md file for more
     * information about these custom layouts.
     */
    'layouts' => [
        // 'test' => \App\Flexible\Layouts\TestLayout::class
    ],

    /**
     * If this is set to true, our default layouts will be loaded and
     * your custom layouts as specified in the array above will be merge
     * with them.
     */
    'merge_layouts' => true,

    'middleware' => \Marshmallow\Pages\Http\Middleware\PageMiddleware::class,
    'controller' => '\Marshmallow\Pages\Http\Controllers\PageController@show',
];
