![alt text](https://cdn.marshmallow-office.com/media/images/logo/marshmallow.transparent.red.png "marshmallow.")

# Marshmallow Pages
Deze package geeft de mogelijkheid om gemakkelijk pagina's te beheren in Laravel. Dit is eigelijk een verzameling van handinge composer packages van derde en samengevoegd om snel te kunnen hergebruiken.

### Installatie
```bash
composer require marshmallow/pages

php artisan vendor:publish --provider="Marshmallow\Nova\Flexible\FieldServiceProvider"
php artisan migrate
php artisan marshmallow:resource Page Pages
```


Add `\Marshmallow\Pages\Facades\Page::loadRoutes();` at the absolute bottom of your `routes/web.php` file.

## Render the layouts
You can add `{!! Page::render($page) !!}` to your blade file to render the layouts that are connected to the page.
You can also loop through them yourself if that is helpfull like;
```php
@foreach ($layouts as $layout)
	{{ $layout->render() }}
@endforeach
```

## Multi language
Run `php artisan marshmallow:multi-language` to make the pages translateable. Please note that this will change your database structure. All the columns in your pages table will be changed to type `json`. The package `marshmallow/multi-language` will be required in composer.

## Make new layouts
Run `php artisan marshmallow:layout` and follow the wizard.
If this is completed, add your new `Layout::class` to your config

Add below to your `.env` file to use our TinyMCE plugin for you text fields. If not used, the default Nova Trix field will be used.
```
NOVA_WYSIWYG=\Marshmallow\Nova\TinyMCE\TinyMCE
```
