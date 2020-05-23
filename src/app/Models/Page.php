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
use Marshmallow\HelperFunctions\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Marshmallow\Nova\Flexible\Layouts\Layout;
use Marshmallow\Nova\Flexible\Casts\FlexibleCast;
use Marshmallow\Nova\Flexible\Layouts\Collection;
use Marshmallow\Nova\Flexible\Concerns\HasFlexible;
use Marshmallow\MultiLanguage\Traits\TranslatableRoute;
use Marshmallow\Nova\Flexible\Layouts\MarshmallowLayout;

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
        'layout' => FlexibleCast::class,
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
}
