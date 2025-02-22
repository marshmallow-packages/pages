![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Laravel Nova Pages
[![Version](https://img.shields.io/packagist/v/marshmallow/pages)](https://github.com/marshmallow-packages/pages)
[![Issues](https://img.shields.io/github/issues/marshmallow-packages/pages)](https://github.com/marshmallow-packages/pages)
[![Licence](https://img.shields.io/github/license/marshmallow-packages/pages)](https://github.com/marshmallow-packages/pages)
![PHP Syntax Checker](https://github.com/marshmallow-packages/pages/workflows/PHP%20Syntax%20Checker/badge.svg)

This page provides you with the ability to easily create and manage pages and its content in Laraval Nova.

## Installation

### Composer
You can install the package via composer:
```bash
composer require marshmallow/pages
```

#### Install for Nova 4
If you are using nova 4, you can install the package using the command below.
```bash
composer require marshmallow/pages "^4.0"
```

### Run the artisan commands
Publish the configs from the flexible package, migrate to create the pages table and create your Laravel Nova resource to manage your pages.
```bash
php artisan pages:install
```

## Usage
You can add `{!! Page::render($page) !!}` to your blade file to render the layouts that are connected to the page.
You can also loop through them yourself if that is helpfull like;
```php
@foreach ($layouts as $layout)
    {{ $layout->render() }}
@endforeach
```

## Add a new layout
You can generate new layouts via the command provided by the `marshmallow/flexible` package that is included in this package. Run the command below to generate a new layout.
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

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
