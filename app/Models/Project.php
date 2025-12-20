<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'overview',
        'location',
        'developer_name',
        'developer_info',
        'amenities',
        'payment_plan',
        'meta_title',
        'meta_description',
        'featured_image',
        'gallery',
        'floor_plans',
        'price_aed',
        'price_usd',
        'price_eur',
        'latitude',
        'longitude',
        'area',
        'property_type',
        'delivery_date',
        'is_featured',
        'status',
        'sort_order',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array<int, string>
     */
    public $translatable = [
        'name',
        'slug',
        'short_description',
        'description',
        'overview',
        'location',
        'developer_name',
        'developer_info',
        'amenities',
        'payment_plan',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amenities' => 'array',
        'payment_plan' => 'array',
        'gallery' => 'array',
        'floor_plans' => 'array',
        'price_aed' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_featured' => 'boolean',
        'delivery_date' => 'date',
        'sort_order' => 'integer',
    ];
}
