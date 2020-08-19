<?php

namespace Marshmallow\Pages\Models;

use Illuminate\Http\Request;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Seoable\Traits\Seoable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Marshmallow\HelperFunctions\Facades\Str;
use Marshmallow\HelperFunctions\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Marshmallow\Nova\Flexible\Concerns\HasFlexible;
use Marshmallow\MultiLanguage\Traits\TranslatableRoute;

/**
 * Is sluggable
 * Factory maakt een prijs aan
 * Kan meerdere prijzen hebben
 * Geeft 0 euro terug als er geen prijs is
 * Slug is uniek
 */

class Page extends Model
{
	use HasSlug;
    use SoftDeletes;
    use HasFlexible;
    use HasTranslations;
    use TranslatableRoute;
    use Seoable;
    use Actionable;

    public $translatable = [];

	protected $guarded = [];

    protected $casts = [
        // 'layout' => FlexibleCast::class,
    ];

    public function __construct()
    {
        parent::__construct();

        if (config('pages.use_multi_languages')) {
            $this->translatable = ['layout', 'name', 'slug'];
        }
    }

    public function scopeGetByUrl(Builder $builder, Request $request)
    {
        $model_url_column = $this->getRouteKeyName();
        $url = $request->route()->uri;
        if (config('pages.use_multi_languages')) {
            $locale = App::getLocale();

            if (strpos($url, $locale . '/') !== false) {
                $url = substr($url, strlen($locale . '/'), strlen($url));
            }
            if ($url === $locale) {
                $url = '/';
            }

            $raw_select_column = DB::raw("REPLACE($model_url_column, ' ', '')");

            if ($url !== '/') {
            	/**
            	 * We also check for escaped urls. Some mysql versions
            	 * will store product/badpak as product\/badpak. Not all
            	 * versions do this so we check on both.
            	 */
            	$escaped_url = URL::escape($url);

                $builder->where(
					$raw_select_column,
					'LIKE',
					Str::removeSpaces(
						'%"'. $locale .'":"'. $url .'"%'
					)
				)->orWhere(
					$raw_select_column,
					'LIKE',
					Str::removeSpaces(
						'%"'. $locale .'":"'. $escaped_url .'"%'
					)
				);
            } else {
                $builder->where(
                	$raw_select_column,
                	'LIKE',
                	Str::removeSpaces(
						'%"'. $locale .'": null%'
					)
                )->orWhere(
                    $raw_select_column,
                    'LIKE',
                    Str::removeSpaces(
                        '%"'. $locale .'": "/"%'
                    )
                );
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

    public function route($ignore_locale = false)
    {
        if (config('pages.use_multi_languages') && $ignore_locale === false) {
            return $this->localeRoute();
        }

        $model_url_column = $this->getRouteKeyName();
        return URL::buildFromArray([
            $this->{$model_url_column}
        ]);
    }

    /**
     * getFullPublicPath() is a required method for the
     * GT Metrix package.
     */
    public function getFullPublicPath()
    {
        $route = $this->route();
        if (!URL::isInternal($route)) {
            return env('APP_URL') . $route;
        }
        return $route;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
