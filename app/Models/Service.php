<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'icon',
        'image',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
        'order',
        'seo_meta',
    ];

    public $translatable = [
        'title',
        'slug',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer',
        'seo_meta' => 'json',
    ];

    /**
     * Scope to get only published services.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get featured services.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get service by slug.
     */
    public static function findBySlug($slug, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return static::where("slug->{$locale}", $slug)->first();
    }
}
