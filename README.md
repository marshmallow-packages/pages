![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Laravel Nova Pages
[![Version](https://img.shields.io/packagist/v/marshmallow/pages)](https://packagist.org/packages/marshmallow/pages)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/pages)](https://packagist.org/packages/marshmallow/pages)
[![Issues](https://img.shields.io/github/issues/marshmallow-packages/pages)](https://github.com/marshmallow-packages/pages/issues)
[![Licence](https://img.shields.io/github/license/marshmallow-packages/pages)](https://github.com/marshmallow-packages/pages)
![PHP Syntax Checker](https://github.com/marshmallow-packages/pages/workflows/PHP%20Syntax%20Checker/badge.svg)

This package provides you with the ability to easily create and manage pages and their content in Laravel Nova. It is essentially a curated bundle of handy Composer packages — both third-party and Marshmallow's own — combined so they can be reused quickly. The page body is built with [`marshmallow/nova-flexible`](https://github.com/marshmallow-packages/nova-flexible) layouts and is rendered to your front-end via a Blade view.

## Requirements

- PHP `^8.1`
- [Laravel Nova](https://nova.laravel.com) `^5.0`

The package pulls in a number of Marshmallow packages it builds on, including `sluggable`, `seoable`, `translatable`, `redirectable`, `nova-flexible`, `nova-multiselect-field` and `nova-fontawesome`. These are installed automatically via Composer.

## Installation

### Composer
You can install the package via composer:
```bash
composer require marshmallow/pages
```

#### Install for Nova 4
If you are using Nova 4, you can install the package using the command below.
```bash
composer require marshmallow/pages "^4.0"
```

### Run the artisan command
Publish the configs from the Flexible package, migrate to create the `pages` table, create your Laravel Nova resource to manage your pages, and register the page routes in `routes/web.php`. This is all handled by a single install command:
```bash
php artisan pages:install
```

Under the hood `pages:install` publishes the package's assets, publishes the Flexible field assets, runs `migrate`, generates a `Page` Nova resource, and appends `\Marshmallow\Pages\Facades\Page::routes();` to your `routes/web.php`.

## Usage
You can add `{!! Page::render($page) !!}` to your blade file to render the layouts that are connected to the page.
You can also loop through them yourself if that is helpful, like:
```php
@foreach ($layouts as $layout)
    {{ $layout->render() }}
@endforeach
```

The `Page` facade also exposes a few helpers:

```php
use Marshmallow\Pages\Facades\Page;

Page::routes();      // Register a route for every page (call this in routes/web.php)
Page::find($id);     // Retrieve a single cached page by its id
```

## Add a new layout
You can generate new layouts via the command provided by the `marshmallow/nova-flexible` package that is included in this package. Run the command below to generate a new layout.
```bash
php artisan marshmallow:layout
```

Next you will need to add the newly generated layout to your `flexible` config.
```php
/**
 * config/flexible.php
 */
return [
    /**
     * Your custom layouts. Please check the readme.md file for more
     * information about these custom layouts.
     */
    'layouts' => [
        'sluggable-name-of-your-layout' => \App\Flexible\Layouts\LayoutClassName::class
    ],
];
```

## Configuration

After running `pages:install` (or publishing manually with
`php artisan vendor:publish --provider="Marshmallow\Pages\PagesServiceProvider"`),
the config file is available at `config/pages.php`. The available options are:

| Key | Default | Description |
| --- | --- | --- |
| `database.connection` | `null` | Database connection used for the `pages` table. `null` uses the default connection. |
| `view` | `marshmallow::layout` | The Blade view used to render a page. |
| `wysiwyg` | `env('NOVA_WYSIWYG', \Laravel\Nova\Fields\Trix::class)` | The Nova field class used for WYSIWYG content. |
| `model` | `\Marshmallow\Pages\Models\Page::class` | The Eloquent model representing a page. Override to use your own. |
| `middleware` | `\Marshmallow\Pages\Http\Middleware\PageMiddleware::class` | Middleware applied to every page route. |
| `controller` | `\Marshmallow\Pages\Http\Controllers\PageController@show` | The controller action that handles page requests. |
| `nova_translatable_fields` | `true` | Show the language selector when editing a resource in Laravel Nova. |
| `share_page_to_view_data` | `false` | When enabled, the full page is shared with all your view components. |
| `use_multi_languages` | `false` | Enable when using Marshmallow's Translatable package to load translated routes and resolve pages by a translated slug. |
| `breadcrumb` | `false` | Whether pages should be added to the Marshmallow Breadcrumb stack. |
| `flexible_config` | `[]` | Extra config loaded by the Flexible package to change its default behaviour. |

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## Credits

- [Stef](https://marshmallow.dev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
