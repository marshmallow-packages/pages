<?php

namespace Marshmallow\Pages\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Marshmallow\Sluggable\HasSlug;
use Illuminate\Support\Facades\App;
use Laravel\Nova\Actions\Actionable;
use Marshmallow\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Seoable\Traits\Seoable;
use Illuminate\Database\Eloquent\Builder;
use Marshmallow\HelperFunctions\Facades\Str;
use Marshmallow\HelperFunctions\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Marshmallow\Seoable\Traits\SeoSitemapTrait;
use Marshmallow\Redirectable\Traits\Redirectable;
use Marshmallow\Translatable\Traits\Translatable;
use Marshmallow\Nova\Flexible\Concerns\HasFlexible;

class Page extends Model
{
    use HasSlug;
    use Seoable;
    use Actionable;
    use SoftDeletes;
    use HasFlexible;
    use Translatable;
    use Redirectable;
    use SeoSitemapTrait;

    protected $guarded = [];

    protected $casts = [
        'middleware' => 'array',
    ];

    public function notTranslateColumns(): array
    {
        return [
            'view',
            'route_name',
            'middleware',
            'active',
            'hide_link_from_index',
        ];
    }

    public function scopeGetByUrl(Builder $builder, Request $request)
    {
        $model_url_column = $this->getRouteKeyName();
        $url = $request->route()->uri;
        if (config('pages.use_multi_languages')) {
            $locale = App::getLocale();

            if (false !== strpos($url, $locale . '/')) {
                $url = substr($url, strlen($locale . '/'), strlen($url));
            }
            if ($url === $locale) {
                $url = '/';
            }

            $raw_select_column = DB::raw("REPLACE($model_url_column, ' ', '')");

            if ('/' !== $url) {
                /**
                 * We also check for escaped urls. Some mysql versions
                 * will store product/product-name as product\/product-name. Not all
                 * versions do this so we check on both.
                 */
                $escaped_url = URL::escape($url);

                $builder->where(
                    $raw_select_column,
                    'LIKE',
                    Str::removeSpaces(
                        '%"' . $locale . '":"' . $url . '"%'
                    )
                )->orWhere(
                    $raw_select_column,
                    'LIKE',
                    Str::removeSpaces(
                        '%"' . $locale . '":"' . $escaped_url . '"%'
                    )
                );
            } else {
                $builder->where(
                    $raw_select_column,
                    'LIKE',
                    Str::removeSpaces(
                        '%"' . $locale . '": null%'
                    )
                )->orWhere(
                    $raw_select_column,
                    'LIKE',
                    Str::removeSpaces(
                        '%"' . $locale . '": "/"%'
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
        if (config('pages.use_multi_languages') && false === $ignore_locale) {
            return $this->localeRoute();
        }

        $model_url_column = $this->getRouteKeyName();

        return URL::buildFromArray([
            $this->{$model_url_column},
        ]);
    }

    public function temporaryRoute($valid_ttl_minutes = 30)
    {
        return URL::signed(
            $this->route(),
            now()->addMinutes($valid_ttl_minutes)
        );
    }

    /**
     * getFullPublicPath() is a required method for the
     * GT Matrix package.
     */
    public function getFullPublicPath()
    {
        $route = $this->route();
        if (!URL::isInternal($route)) {
            return config('app.url') . $route;
        }

        return $route;
    }

    public function getView()
    {
        if (isset($this->view) && $this->view && 'default' !== strtolower($this->view)) {
            return $this->view;
        }

        return config('pages.view');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the Page url by item
     *
     * @return string
     */
    public function getSitemapItemUrl(): string
    {
        return $this->route();
    }

    /**
     * Query all the Page items which should be
     * part of the sitemap (crawlable for google).
     *
     * @return Builder
     */
    public static function getSitemapItems()
    {
        return static::get();
    }
}
