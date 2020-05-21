<?php

namespace Marshmallow\Pages\Models;

use Illuminate\Http\Request;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Seoable\Traits\Seoable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Marshmallow\Pages\Casts\FlexibleCast;
use Marshmallow\HelperFunctions\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whitecube\NovaFlexibleContent\Layouts\Layout;
use Whitecube\NovaFlexibleContent\Layouts\Collection;
use Marshmallow\MultiLanguage\Traits\TranslatableRoute;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

/**
 * Is sluggable
 * Factory maakt een prijs aan
 * Kan meerdere prijzen hebben
 * Geeft 0 euro terug als er geen prijs is
 * Slug is uniek
 */

class Page extends Model
{
	use HasSlug, SoftDeletes, HasFlexible,
        HasTranslations, TranslatableRoute,
        Seoable;

    public $translatable = ['layout', 'name', 'slug'];

	protected $guarded = [];

    protected $casts = [
        'layout' => FlexibleCast::class
    ];

    public function scopeGetByUrl (Builder $builder, Request $request)
    {
        $model_url_column = $this->getRouteKeyName();
        $url = join('/', $request->segments());
        if (config('pages.use_multi_languages')) {
            $locale = App::getLocale();
            $url = ltrim($url, $locale . '/');
            if ($url) {
                $builder->where($model_url_column, 'LIKE', '%"'. $locale .'": "'. $url .'"%');
            } else {
                $builder->where($model_url_column, 'LIKE', '%"'. $locale .'": null%');
            }

        } else {
            $builder->where($model_url_column, $url);
        }
    }

	/**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('name')
            ->saveSlugsTo($this->getRouteKeyName());
    }

    public function route ()
    {
        $model_url_column = $this->getRouteKeyName();
        return URL::buildFromArray([
            $this->{$model_url_column}
        ]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getLayoutCollection ()
    {
        $flexible = $this->layout;

        if ($flexible instanceof Collection) {
            return $flexible;
        }

        if (is_null($flexible)) {
            return new Collection();
        }

        if (!is_array($flexible)) {
            return new Collection();
        }

        return new Collection(
            array_filter(
                $this->getMappedFlexibleLayouts(
                    $flexible, \Marshmallow\Pages\Facades\Page::getLayouts()
                )
            )
        );
    }

    /**
     * [getMappedFlexibleLayouts description]
     *
     * @param array $flexible      [description]
     * @param array $layoutMapping [description]
     *
     * @return array                [description]
     */
    protected function getMappedFlexibleLayouts(array $flexible, array $layoutMapping)
    {
        return array_map(
            function ($item) use ($layoutMapping) {
                return $this->getMappedLayout($item, $layoutMapping);
            }, $flexible
        );
    }

    protected function getMappedLayout($item, array $layoutMapping)
    {
        $name = null;
        $key = null;
        $attributes = [];

        if (is_string($item)) {
            $item = json_decode($item);
        }

        if (is_array($item)) {
            $name = $item['layout'] ?? null;
            $key = $item['key'] ?? null;
            $attributes = (array) $item['attributes'] ?? [];

        } elseif (is_a($item, \stdClass::class)) {
            $name = $item->layout ?? null;
            $key = $item->key ?? null;
            $attributes = (array) $item->attributes ?? [];

        } elseif (is_a($item, Layout::class)) {
            $name = $item->name();
            $key = $item->key();
            $attributes = $item->getAttributes();
        }

        if (is_null($name) || !$attributes) {
            return;
        }

        return $this->createMappedLayout($name, $key, $attributes, $layoutMapping);
    }

    /**
     * [createMappedLayout description]
     *
     * @param [type] $name          [description]
     * @param [type] $key           [description]
     * @param [type] $attributes    [description]
     * @param array  $layoutMapping [description]
     *
     * @return [type]                [description]
     */
    protected function createMappedLayout($name, $key, $attributes, array $layoutMapping)
    {
        $classname = array_key_exists($name, $layoutMapping)
            ? $layoutMapping[$name]
            : Layout::class;

        $layout = new $classname($name, $name, [], $key, $attributes);

        $model = is_a($this, \Whitecube\NovaFlexibleContent\Value\FlexibleCast::class)
            ? $this->model
            : $this;

        $layout->setModel($model);

        return $layout;
    }
}
